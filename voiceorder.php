<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Voice Order - GRILL & FRY</title>
<link rel="stylesheet" href="stylesheet.css">

<style>
.voice-container{
    text-align:center;
    margin:80px auto;
    width:80%;
    max-width:600px;
    background:#fff7f2;
    border-radius:20px;
    padding:40px;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}
.mic-button{
    background:#c94b2b;
    color:#fff;
    border:none;
    border-radius:50%;
    width:90px;
    height:90px;
    font-size:36px;
    cursor:pointer;
}
.listening{
    background:#ff5e00;
    animation:pulse 1s infinite;
}
@keyframes pulse{
    0%{box-shadow:0 0 0 0 rgba(255,94,0,.6);}
    70%{box-shadow:0 0 0 20px rgba(255,94,0,0);}
    100%{box-shadow:0 0 0 0 rgba(255,94,0,0);}
}
#recognized-text{
    background:#fff;
    border-radius:8px;
    padding:15px;
    margin-top:20px;
    min-height:80px;
    font-size:17px;
    border:1px solid #ccc;
    text-align:left;
    white-space:pre-line;
}
#confirmBtn{
    margin-top:25px;
    padding:12px 25px;
    background:#ff4d4d;
    color:white;
    border:none;
    border-radius:8px;
    font-size:18px;
    cursor:pointer;
}
</style>
</head>

<body>

<?php include 'nav.php'; ?>

<section class="voice-container">
    <h2>🎙️ Voice Order</h2>
    <p>Press record, speak your order, then confirm.</p>
    <br><br>

    <button id="micBtn" class="mic-button">🎙️</button>

    <div id="recognized-text">Your order will appear here...</div>

    <textarea id="manualText"
          placeholder="✍️ Type your order here..."
          style="display:none;width:100%;height:100px;margin-top:15px;"></textarea>

    <button id="confirmBtn">Confirm & Add to Cart</button>
</section>

<script>
let mediaRecorder;
let audioChunks = [];
let recordedBlob = null;
let lastAIResponse = null;

const micBtn = document.getElementById("micBtn");
const confirmBtn = document.getElementById("confirmBtn");
const box = document.getElementById("recognized-text");
const manualText = document.getElementById("manualText");


micBtn.onclick = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    audioChunks = [];

    micBtn.classList.add("listening");
    box.textContent = "🎧 Listening...";

    mediaRecorder.ondataavailable = e => {
      if (e.data.size > 0) audioChunks.push(e.data);
    };

    mediaRecorder.onstop = () => {
      micBtn.classList.remove("listening");
      recordedBlob = new Blob(audioChunks, { type: "audio/webm" });
      box.textContent = "🎙️ Voice recorded. Click Confirm.";
    };
    recordedBlob = new Blob(audioChunks, { type: "audio/webm" });
    audioChunks = [];


    mediaRecorder.start();

    setTimeout(() => {
      if (mediaRecorder.state === "recording") {
        mediaRecorder.stop();
      }
    }, 5000);

  } catch (err) {
    alert("❌ Microphone access denied");
  }
};


confirmBtn.onclick = () => {
  const fd = new FormData();

  if (recordedBlob) {
    fd.append("audio", recordedBlob);
  } else if (manualText.value.trim() !== "") {
    fd.append("text", manualText.value.trim());
  } else {
    alert("Please record or type your order");
    return;
  }

  box.textContent = "⏳ Sending to AI...";

  fetch("voice_ai_order.php", {
    method: "POST",
    body: fd
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      box.textContent =
        "🛒 Added to cart:\n" +
        data.items.map(i => `${i.qty} × ${i.name}`).join("\n");

      setTimeout(() => {
        window.location.href = "cart.php";
      }, 1500);
    } else {
      box.textContent = data.message;
      manualText.style.display = "block";
    }
  })
  .catch(() => {
    box.textContent = "❌ Server error";
  });
};
</script>


</body>
</html>
