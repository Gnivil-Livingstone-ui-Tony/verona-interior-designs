<?php
$pageTitle = "Our Projects | VERONA Interior Designs";

require_once __DIR__ . "/db.php";

// Fetch projects saved by the administrator.
$projects = [];

$sql = "SELECT title, category, image_url, description
        FROM projects
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Escape text before displaying it on the page.
function escapeProjectText($value)
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// Convert local image paths into paths usable by the browser.
// Remote images are accepted only over HTTPS or HTTP.
function projectImagePath($image)
{
    $image = trim((string) $image);

    if ($image === '') {
        return '';
    }

    if (preg_match('~^https?://~i', $image)) {
        return filter_var($image, FILTER_VALIDATE_URL) ? $image : '';
    }

    // Allow relative paths such as assets/images/kitchen.jpg.
    $image = str_replace('\\', '/', $image);
    $image = ltrim($image, '/');

    // Reject paths that attempt to move outside the project folder.
    $segments = explode('/', $image);

    if (
        preg_match('/^[a-z][a-z0-9+.-]*:/i', $image) ||
        str_contains($image, '..') ||
        str_contains($image, "\0") ||
        str_starts_with($image, '//')
    ) {
        return '';
    }

    return $image;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo escapeProjectText($pageTitle); ?></title>

    <meta
        name="description"
        content="Explore interior design inspiration from VERONA Interior Designs, including kitchens, living spaces, ceilings and house finishing."
    >

    <link rel="stylesheet" href="assets/style.css">

    <style>
        .gallery-hero {
            background: #20211f;
            color: #fff;
            text-align: center;
            padding: 80px 7%;
        }

        .gallery-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 5vw, 58px);
            font-weight: 400;
        }

        .gallery-hero h1 span {
            color: #c6a56a;
        }

        .gallery-hero p:not(.eyebrow) {
            max-width: 650px;
            margin: 18px auto 0;
            color: #d0d0c9;
            font-size: 14px;
        }

        .gallery-section {
            padding: 70px 7%;
        }

        .gallery-intro {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 32px;
        }

        .gallery-intro h2 {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 400;
        }

        .gallery-intro p {
            color: #777870;
            font-size: 13px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .project-card {
            background: #fff;
            overflow: hidden;
        }

        .project-image {
            overflow: hidden;
            background: #e5e1d8;
        }

        .project-card img {
            display: block;
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .project-card:hover img {
            transform: scale(1.04);
        }

        .project-image-placeholder {
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777870;
            font-size: 13px;
        }

        .project-info {
            padding: 23px;
        }

        .project-category {
            color: #a27c3c;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .project-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 23px;
            font-weight: 500;
            margin: 9px 0;
        }

        .project-info p {
            color: #777870;
            font-size: 13px;
            line-height: 1.7;
        }

        .project-contact {
            margin-top: 18px;
            display: inline-block;
            color: #8e6d36;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .project-contact:hover {
            color: #c6a56a;
        }

        .gallery-empty {
            grid-column: 1 / -1;
            padding: 45px 20px;
            background: #f5f3ee;
            text-align: center;
            color: #777870;
        }

        .gallery-empty h3 {
            color: #20211f;
            font-family: 'Playfair Display', serif;
            font-size: 25px;
            font-weight: 400;
        }

        .gallery-empty a {
            color: #8e6d36;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-intro {
                align-items: flex-start;
                flex-direction: column;
            }

            .project-card img,
            .project-image-placeholder {
                height: 260px;
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
        <a href="services.php">Services</a>
        <a href="projects.php" class="active">Our Projects</a>
        <a href="contact.php">Contact</a>
    </nav>

    <a href="contact.php" class="header-btn">Get a Quote</a>
</header>

<main>
    <section class="gallery-hero">
        <p class="eyebrow">OUR PORTFOLIO</p>

        <h1>
            Spaces That Inspire.<br>
            <span>Designs That Last.</span>
        </h1>

        <p>
            Explore interior inspiration for kitchens, living rooms
            and contemporary homes. Let your next project begin here.
        </p>
    </section>

    <section class="gallery-section">
        <div class="gallery-intro">
            <div>
                <p class="eyebrow">OUR COLLECTION</p>
                <h2>Explore Our Gallery</h2>
            </div>

            <p>Interior concepts for modern living.</p>
        </div>

        <div class="gallery-grid">

            <?php if (empty($projects)): ?>

                <div class="gallery-empty">
                    <h3>Our Gallery Is Growing</h3>

                    <p>
                        We are preparing our project collection.
                        Contact us to discuss your interior design ideas.
                    </p>

                    <a href="contact.php">Contact VERONA →</a>
                </div>

            <?php else: ?>

                <?php foreach ($projects as $project): ?>
                    <?php
                    $title = escapeProjectText($project['title']);
                    $category = escapeProjectText(
                        $project['category'] ?: 'Interior Design'
                    );
                    $description = escapeProjectText(
                        $project['description']
                    );
                    $image = projectImagePath($project['image_url']);
                    ?>

                    <article class="project-card">
                        <div class="project-image">

                            <?php if ($image !== ''): ?>
                                <img
                                    src="<?php echo escapeProjectText($image); ?>"
                                    alt="<?php echo $title; ?>"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="project-image-placeholder">
                                    Image coming soon
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="project-info">
                            <span class="project-category">
                                <?php echo $category; ?>
                            </span>

                            <h3><?php echo $title; ?></h3>

                            <p><?php echo $description; ?></p>

                            <a
                                class="project-contact"
                                href="contact.php"
                            >
                                Discuss a Similar Design →
                            </a>
                        </div>
                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </section>

    <section class="cta section-padding">
        <p class="eyebrow">YOUR VISION, OUR NEXT PROJECT</p>

        <h2>Let's design something beautiful.</h2>

        <p>
            Contact VERONA to discuss your interior design ideas.
        </p>

        <a href="contact.php" class="btn btn-gold">
            Start an Enquiry
        </a>
    </section>
</main>

<footer class="site-footer">
    <a href="index.php" class="logo">
        VERONA<span>.</span>
    </a>

    <p>Interior design and finishing for inspired living.</p>

    <p class="copyright">
        &copy; <?php echo date("Y"); ?> VERONA Interior Designs.
        All rights reserved.
    </p>
</footer>

<a
    class="whatsapp-float"
    href="https://wa.me/254740807081?text=Hello%20VERONA%2C%20I%20am%20interested%20in%20your%20interior%20design%20services."
    target="_blank"
    rel="noopener noreferrer"
>
    WhatsApp
</a>

</body>
</html>