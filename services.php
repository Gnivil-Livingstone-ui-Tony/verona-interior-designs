<?php
$pageTitle = "Our Services | VERONA Interior Designs";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .services-hero {
            background: #20211f;
            color: #fff;
            text-align: center;
            padding: 85px 7%;
        }

        .services-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 5vw, 58px);
            font-weight: 400;
            margin-bottom: 18px;
        }

        .services-hero h1 span {
            color: #c6a56a;
        }

        .services-hero p:not(.eyebrow) {
            color: #d0d0c9;
            max-width: 650px;
            margin: auto;
            font-size: 14px;
        }

        .services-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px;
        }

        .service-detail {
            background: #fff;
            padding: 35px;
            border-bottom: 3px solid #c6a56a;
        }

        .service-detail .number {
            color: #c6a56a;
            letter-spacing: 3px;
            font-size: 12px;
        }

        .service-detail h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            font-size: 28px;
            margin: 15px 0;
        }

        .service-detail p {
            color: #777870;
            font-size: 14px;
        }

        .service-detail ul {
            padding-left: 20px;
            margin: 20px 0;
            color: #55564f;
            font-size: 13px;
        }

        .service-detail li {
            margin: 8px 0;
        }

        .service-detail a {
            color: #8e6d36;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 650px) {
            .services-list {
                grid-template-columns: 1fr;
            }

            .service-detail {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">
        VERONA<span>.</span>
        <small>INTERIOR DESIGNS</small>
    </a>

    <nav>
        <a href="index.php">Home</a>
        <a href="services.php" class="active">Services</a>
        <a href="projects.php">Our Projects</a>
        <a href="contact.php">Contact</a>
    </nav>

    <a href="contact.php" class="header-btn">Get a Quote</a>
</header>

<main>
    <section class="services-hero">
        <p class="eyebrow">OUR EXPERTISE</p>
        <h1>Designed for Living.<br><span>Finished with Care.</span></h1>
        <p>
            Explore our interior design and finishing services.
            We help transform your ideas into beautiful, practical spaces.
        </p>
    </section>

    <section class="section-padding">
        <div class="services-list">

            <article class="service-detail">
                <span class="number">01 / CEILING SOLUTIONS</span>
                <h2>Ceiling Board Designs</h2>
                <p>
                    Give your rooms a refined appearance with stylish
                    ceiling designs and carefully planned finishing.
                </p>
                <ul>
                    <li>Gypsum ceiling designs</li>
                    <li>Decorative ceiling patterns</li>
                    <li>Lighting layout coordination</li>
                    <li>Ceiling installation and finishing</li>
                </ul>
                <a href="contact.php">Enquire about this service →</a>
            </article>

            <article class="service-detail">
                <span class="number">02 / KITCHEN SOLUTIONS</span>
                <h2>Modern Kitchen Design</h2>
                <p>
                    Create a welcoming, organized kitchen with a layout
                    that combines appearance and everyday functionality.
                </p>
                <ul>
                    <li>Kitchen layout planning</li>
                    <li>Cabinet and storage concepts</li>
                    <li>Countertop and finish selection</li>
                    <li>Modern kitchen styling</li>
                </ul>
                <a href="contact.php">Enquire about this service →</a>
            </article>

            <article class="service-detail">
                <span class="number">03 / INTERIOR FINISHING</span>
                <h2>Interior Decoration</h2>
                <p>
                    Bring rooms together through thoughtful colours,
                    materials, textures and decorative details.
                </p>
                <ul>
                    <li>Colour scheme consultation</li>
                    <li>Wall decoration concepts</li>
                    <li>Material and finish selection</li>
                    <li>Interior styling ideas</li>
                </ul>
                <a href="contact.php">Enquire about this service →</a>
            </article>

            <article class="service-detail">
                <span class="number">04 / COMPLETE INTERIORS</span>
                <h2>House Interior Finishing</h2>
                <p>
                    Plan a consistent look across your home, from
                    individual rooms to coordinated interior spaces.
                </p>
                <ul>
                    <li>Living room improvements</li>
                    <li>Bedroom interior concepts</li>
                    <li>Space planning</li>
                    <li>Interior finishing coordination</li>
                </ul>
                <a href="contact.php">Enquire about this service →</a>
            </article>

        </div>
    </section>

    <section class="cta section-padding">
        <p class="eyebrow">LET'S CREATE SOMETHING BEAUTIFUL</p>
        <h2>Have a project in mind?</h2>
        <p>Talk to VERONA about your ideas, requirements and budget.</p>
        <a href="contact.php" class="btn btn-gold">Contact Our Team</a>
    </section>
</main>

<footer class="site-footer">
    <a href="index.php" class="logo">VERONA<span>.</span></a>
    <p>Interior design and finishing for inspired living.</p>
    <p class="copyright">
        &copy; <?php echo date("Y"); ?> VERONA Interior Designs.
        All rights reserved.
    </p>
</footer>

<a class="whatsapp-float"
   href="https://wa.me/254740807081?text=Hello%20VERONA%2C%20I%20would%20like%20to%20ask%20about%20your%20services."
   target="_blank"
   rel="noopener noreferrer">
    WhatsApp
</a>

</body>
</html>