<?php
$pageTitle = "VERONA Interior Designs | Transform Your Space";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="VERONA Interior Designs offers stylish ceiling installations, modern kitchens and elegant interior finishing.">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">VERONA<span>.</span>
        <small>INTERIOR DESIGNS</small>
    </a>

    <nav>
        <a href="index.php" class="active">Home</a>
        <a href="services.php">Services</a>
        <a href="projects.php">Our Projects</a>
        <a href="contact.php">Contact</a>
    </nav>

    <a href="contact.php" class="header-btn">Get a Quote</a>
</header>

<main>
    <section class="hero">
        <div class="hero-content">
            <p class="eyebrow">DESIGN • DETAIL • DISTINCTION</p>

            <h1>Beautiful Spaces.<br>
                <span>Exceptional Living.</span>
            </h1>

            <p class="hero-description">
                We transform ordinary spaces into extraordinary interiors
                through modern ceiling designs, elegant kitchens and
                carefully crafted interior finishes.
            </p>

            <div class="hero-actions">
                <a href="projects.php" class="btn btn-gold">
                    Explore Our Projects
                </a>
                <a href="services.php" class="btn btn-outline">
                    Discover Our Services
                </a>
            </div>

            <div class="hero-note">
                <span class="gold-line"></span>
                Thoughtful design. Quality finishing. Timeless style.
            </div>
        </div>

        <div class="hero-image">
            <img
                src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1400&q=85"
                alt="Elegant modern living room interior"
            >
            <div class="image-caption">
                <span>01 / OUR PHILOSOPHY</span>
                <p>Interiors made for living.</p>
            </div>
        </div>
    </section>

    <section class="intro section-padding">
        <div>
            <p class="eyebrow">WELCOME TO VERONA</p>
            <h2>We bring your interior vision to life.</h2>
        </div>

        <div class="intro-copy">
            <p>
                Your home deserves more than ordinary finishing.
                VERONA Interior Designs helps create attractive,
                functional spaces that reflect your taste and lifestyle.
            </p>
            <a href="contact.php" class="text-link">
                Let's discuss your project →
            </a>
        </div>
    </section>

    <section class="services-preview section-padding">
        <div class="section-heading">
            <div>
                <p class="eyebrow">WHAT WE DO</p>
                <h2>Our Expertise</h2>
            </div>
            <a href="services.php" class="text-link">All Services →</a>
        </div>

        <div class="service-grid">
            <article class="service-card">
                <div class="service-number">01</div>
                <h3>Ceiling Designs</h3>
                <p>
                    Modern gypsum ceilings, decorative finishes
                    and stylish ceiling concepts.
                </p>
                <a href="services.php">Discover more →</a>
            </article>

            <article class="service-card">
                <div class="service-number">02</div>
                <h3>Kitchen Design</h3>
                <p>
                    Contemporary kitchen layouts, cabinetry
                    and attractive, practical finishes.
                </p>
                <a href="services.php">Discover more →</a>
            </article>

            <article class="service-card">
                <div class="service-number">03</div>
                <h3>Interior Finishing</h3>
                <p>
                    Carefully selected colours, decorative details
                    and finishes for beautiful spaces.
                </p>
                <a href="services.php">Discover more →</a>
            </article>
        </div>
    </section>

    <section class="cta section-padding">
        <p class="eyebrow">YOUR SPACE, REIMAGINED</p>
        <h2>Ready to transform your home?</h2>
        <p>Tell us about your ideas and let's plan your next project.</p>
        <a href="contact.php" class="btn btn-gold">Talk to VERONA</a>
    </section>
</main>

<footer class="site-footer">
    <a href="index.php" class="logo">
        VERONA<span>.</span>
    </a>

    <p>Interior design and finishing for inspired living.</p>

    <p>
        <a href="admin_login.php" class="admin-footer-link">
            Admin Login
        </a>
    </p>

    <p class="copyright">
        &copy; <?php echo date("Y"); ?> VERONA Interior Designs.
        All rights reserved.
    </p>
</footer>

<a class="whatsapp-float"
   href="https://wa.me/254740807081?text=Hello%20VERONA%20Interior%20Designs%2C%20I%20would%20like%20to%20make%20an%20enquiry."
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Contact VERONA on WhatsApp">
    WhatsApp
</a>

</body>
</html>