<?php
session_start();
require "connection.php";

header("Content-Type: application/json");


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];


$OPENAI_API_KEY = "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"; // ✅ already done by you


$spokenText = "";

if (isset($_FILES["audio"])) {

    $audioPath = $_FILES["audio"]["tmp_name"];

   
    $ch = curl_init("https://api.openai.com/v1/audio/transcriptions");

    $postFields = [
        "file"  => new CURLFile($audioPath, "audio/webm", "voice.webm"),
        "model" => "whisper-1",
        "language" => "en"
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $OPENAI_API_KEY"
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["status"=>"error","message"=>"Curl error"]);
        exit;
    }

    curl_close($ch);

    $json = json_decode($response, true);

    if (!isset($json["text"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Transcription failed"
        ]);
        exit;
    }

    $spokenText = strtolower(trim($json["text"]));
}

elseif (isset($_POST["text"])) {
    $spokenText = strtolower(trim($_POST["text"]));
}

else {
    echo json_encode(["status"=>"error","message"=>"No input"]);
    exit;
}




$numbers = [
    "one"=>1, "two"=>2, "three"=>3,
    "1"=>1, "2"=>2, "3"=>3,
    "واحد"=>1, "اثنين"=>2, "اثنتين"=>2, "ثلاثة"=>3
];


$menu = [];
$q = $connection->query("
    SELECT m.item_name, m.price, t.type_name
    FROM menu_items m
    JOIN types t ON m.type_id = t.id
    WHERE m.is_active = 1 AND t.is_active = 1
");

while ($row = $q->fetch_assoc()) {
    $menu[] = $row;
}

$addedItems = [];

foreach ($menu as $item) {
    $name = strtolower($item["item_name"]);

    if (strpos($spokenText, $name) !== false) {

        $qty = 1;
        foreach ($numbers as $word => $num) {
            if (strpos($spokenText, $word) !== false) {
                $qty = $num;
                break;
            }
        }

        $totalPrice = $qty * $item["price"];

        $stmt = $connection->prepare("
            INSERT INTO cart (user_id, type, item_name, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issid",
            $user_id,
            $item["type_name"],
            $item["item_name"],
            $qty,
            $totalPrice
        );
        $stmt->execute();
        $stmt->close();

        $addedItems[] = [
            "name" => $item["item_name"],
            "qty"  => $qty
        ];
    }
}


if (empty($addedItems)) {
    echo json_encode([
        "status" => "error",
        "message" => "No items recognized"
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "items"  => $addedItems
    ]);
}
