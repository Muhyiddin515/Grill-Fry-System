<?php
session_start();


if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit;
}


require 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - GRILL & FRY</title>
    <link rel="stylesheet" href="services.css">
</head>
<style>

.service-request{
    max-width: 700px;
    margin: 35px auto;
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 10px 24px rgba(0,0,0,.12);
}

.service-request h2{
    text-align:center;
    font-size: 26px;
    margin: 0 0 18px 0;
    color:#222;
}


.success-box{
    margin: 0 auto 16px auto;
    background: #e8fff3;
    color:#1e824c;
    padding: 12px 14px;
    border-radius: 12px;
    text-align:center;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(0,0,0,.10);
}


.service-form{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}


.service-form label{
    grid-column: span 2;
    font-weight: 700;
    font-size: 14px;
    color:#333;
    margin-top: 4px;
}


.field{
    position: relative;
}


.field input,
.field select{
    width: 100%;
    height: 46px;
    padding: 0 14px 0 44px;  
    border-radius: 12px;
    border: 1.5px solid #ddd;
    font-size: 14px;
    background: #fff;
    transition: .2s ease;
    position: center;
}

.field textarea{
    width: 50%;
    min-height: 95px;
    height:46px;
    padding: 12px ;
    border-radius: 12px;
    border: 1.5px solid #ddd;
    font-size: 14px;
    resize: none;
    transition: .2s ease;
}

.field .icon{
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: .75;
}

.field.full{
    grid-column: span 2;
}

.field input:focus,
.field select:focus,
.field textarea:focus{
    outline: none;
    border-color: #ff511c;
    box-shadow: 0 0 0 4px rgba(255,81,28,.15);
}

.service-form button{
    grid-column: span 2;
    height: 48px;
    border: none;
    border-radius: 12px;
    background: #ff511c;
    color: #fff;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
}

.service-form button:hover{
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(255,81,28,.35);
    background: #f24814;
}

@media (max-width: 650px){
    .service-request{
        max-width: 92%;
        padding: 18px;
    }
    .service-form{
        grid-template-columns: 1fr;
    }
    .service-form label,
    .field.full,
    .service-form button{
        grid-column: span 1;
    }
}
.view-requests-box{
    text-align: center;
    margin-top: 25px;
    width: 100%;
    height: 100px;
}

.view-requests-btn{
    display: inline-block;
    padding: 14px 28px;
    background: #2c7be5;
    color: white;
    font-weight: bold;
    border-radius: 14px;
    text-decoration: none;
    transition: .2s ease;
    
}

.view-requests-btn:hover{
    background: #1b5fc1;
    transform: translateY(-2px);
}

</style>
<body>
    <section class="Menu">
        <?php include 'nav.php'; ?>

    </section>

    <section class="services">
        <div class="container">
            <h2>Our Services</h2>
            <div class="service-card">
                <h3>Dine-In Experience</h3>
                <p>Enjoy a cozy and inviting atmosphere with our gourmet dishes and attentive service.</p>
            </div>
            <div class="service-card">
                <h3>Takeout & Delivery</h3>
                <p>Order your favorite meals to-go or have them delivered right to your doorstep.</p>
                <div class="soon-banner">Coming Soon!</div>
            </div>
            <div class="service-card">
                <h3>Catering Services</h3>
                <p>Let us cater your special events with our exquisite menu options and professional service.</p>
            </div>
            <div class="service-card">
                <h3>Special Events</h3>
                <p>Host your celebrations with us and enjoy a tailored menu and dedicated event planning.</p>
            </div>
        </div>
    </section>
    <section class="service-request">

    <h2>Request a Service</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-box">
            ✅ Your service request has been sent successfully!<br>
            We will contact you soon.
        </div>
    <?php endif; ?>

    <form action="submit_service_request.php" method="POST" class="service-form">

        <label>Service Type</label>
        <div class="field">
            <span class="icon">🍽</span>
            <select name="service_type" required>
                <option value="">-- Select Service --</option>
                <option value="Dine-In">Dine-In</option>
                <option value="Catering">Catering</option>
                <option value="Delivery">Delivery</option>
                <option value="Special Event">Special Event</option>
            </select>
        </div>

        <label>Preferred Date</label>
        <div class="field">
            <span class="icon">📅</span>
            <input type="date" name="event_date" required>
        </div>

        <label>From Time</label>
        <div class="field">
            <span class="icon">⏰</span>
            <input type="time" name="time_from" required>
        </div>

        <label>To Time</label>
        <div class="field">
            <span class="icon">⏱</span>
            <input type="time" name="time_to" required>
        </div>

        <label>Number of People</label>
        <div class="field">
            <span class="icon">👥</span>
            <input type="number" name="people" min="1" placeholder="Number of people">
        </div>

        <label>Notes / Special Requests</label>
        <div class="field full">
            <span class="icon">📝</span>
            <textarea name="notes" placeholder="Write any details here..."></textarea>
        </div>

        <button type="submit">Submit Request</button>
        <div class="view-requests-box">
    <a href="my_service_requests.php" class="view-requests-btn">
        📋 View My Service Requests
    </a>
</div>

    </form>
</section>


</body>
</html>
