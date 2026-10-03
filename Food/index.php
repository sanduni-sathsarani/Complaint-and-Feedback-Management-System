<?php
require_once "includes/functions.php";
include "includes/header.php";
?>
<section class="hero">
    <div class="hero-content">
        <span class="badge">FOODCARE RESTAURANT</span>
        <h1>Your Experience Matters.</h1>
        <p>Tell us about your dining experience. Submit a complaint, share feedback, or track an existing complaint quickly and easily.</p>
        <div class="hero-buttons">
            <a class="btn btn-primary" href="complaint.php">Make a Complaint</a>
            <a class="btn btn-light" href="feedback.php">Give Feedback</a>
        </div>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <span>HOW IT WORKS</span>
        <h2>We listen. We investigate. We improve.</h2>
    </div>
    <div class="card-grid">
        <div class="info-card"><div class="icon">01</div><h3>Submit</h3><p>Send your complaint or feedback without creating an account.</p></div>
        <div class="info-card"><div class="icon">02</div><h3>Investigate</h3><p>Our staff review the issue and take appropriate action.</p></div>
        <div class="info-card"><div class="icon">03</div><h3>Resolve</h3><p>Track your complaint using the complaint number provided.</p></div>
    </div>
</section>

<section class="container highlight">
    <div>
        <span class="badge">QUICK ACCESS</span>
        <h2>Already submitted a complaint?</h2>
        <p>Use your complaint number to check the latest status.</p>
    </div>
    <a class="btn btn-dark" href="track.php">Track Complaint</a>
</section>
<?php include "includes/footer.php"; ?>