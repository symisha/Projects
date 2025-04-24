
<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="homee.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
    <img src="building2.jpg" alt="Hero Image">
    <div class="hero-text">
        <h1>HOUSING MANAGEMENT SYSTEM</h1>
        <p>We help apprtments/society to manage almost all their work  <br> From managing staff to make an annoncement we make them possible<br><br><br><br><br><br><br></p>
    </div>
    <!-- Navigation Links -->
    <div class="nav-links-container">
        <ul class="nav-links">
        <li><a href="../Admin/login - Admin.php">ADMIN</a></li>
            <li><a href="../Residents/login - Resident.php">RESIDENT</a></li>
            <li><a href="../Staff/login - Staff.php">STAFF</a></li>
            <!-- <li><a href="">EMERGENCY</a></li> -->
            <li class="dropdown">
                <a href="#">EMERGENCY</a>
                <ul class="dropdown-menu">
                <li><a href="tel:+92-3183369473">Fire</a></li>
                <li><a href="tel:+92-3183369473">Ambulance</a></li>
                <li><a href="tel:+92-3183369473">Police</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <section id="about" class="about-us">
        <h2>About Us</h2>
        <p>
        The Society Management System streamlines daily tasks in residential communities, automating maintenance requests, payments, facility bookings, and visitor logs. It enhances communication, simplifies operations, and fosters a more connected and efficient living environment for residents and management alike.
        </p>
    </section>
    <!-- Our Services Section -->
<section id="services" class="our-services">
    <h2>Our Services</h2>
    <div class="services-list">
        <div class="service-item">
            <h3>Resident Management</h3>
            <p>Manage and store detailed profiles for each resident, including personal information, apartment details, and contact numbers.</p>
        </div>
        <div class="service-item">
            <h3>Visitor Management</h3>
            <p>Log visitor details and send notifications to the resident upon visitor arrival.</p>
        </div>
        <div class="service-item">
            <h3>Maintenance Request System</h3>
            <p>Allow residents to submit and track maintenance requests while enabling the admin to manage and assign tasks.</p>
        </div>
        <div class="service-item">
            <h3>Payment and Billing System</h3>
            <p>Facilitate the collection of society fees and utility payments, with the ability to view payment history and generate receipts.</p>
        </div>
        <div class="service-item">
            <h3>Facility Booking Management</h3>
            <p>Manage the booking and availability of shared facilities, such as the gym, swimming pool, and community hall.</p>
        </div>
        <div class="service-item">
            <h3>Complaint/Feedback Management</h3>
            <p>Log and resolve resident complaints or grievances about services or issues within the society.</p>
        </div>
        <div class="service-item">
            <h3>Emergency Contact Information</h3>
            <p>Provide essential emergency numbers for easy access during urgent situations.</p>
        </div>
        <div class="service-item">
            <h3>Monthly Reports and Analytics</h3>
            <p>Generate detailed reports on society operations, including maintenance requests, payments, and facility usage.</p>
        </div>
    </div>
    <section id="faq" class="faq-section">
        <h2>Frequently Asked Questions (FAQs)</h2>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h4>How do I log in to my account?</h4>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Visit the home page, select your domain, enter your credentials, and click "Login."</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Can I submit multiple maintenance requests?</h4>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Yes, you can submit as many requests as needed.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>How do I book a facility?</h4>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Navigate to the Facility Booking section, select your preferred facility, and confirm the booking details.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Housing Management System. All Rights Reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
