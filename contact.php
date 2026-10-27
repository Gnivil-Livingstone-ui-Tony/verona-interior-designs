<?php
$pageTitle = "Contact Us | VERONA Interior Designs";

$whatsappNumber = "254740807081";

$whatsappMessage = "Hello VERONA Interior Designs, I would like to enquire about your services.";
$whatsappLink = "https://wa.me/" . $whatsappNumber
    . "?text=" . urlencode($whatsappMessage);

$services = [
    "Ceiling Board Designs",
    "Modern Kitchen Design",
    "Interior Decoration",
    "House Interior Finishing",
    "Other Enquiry"
];

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $service = trim($_POST["service"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (
        $name === "" ||
        $phone === "" ||
        $service === "" ||
        $message === ""
    ) {
        $errorMessage = "Please complete all fields.";

    } elseif (!in_array($service, $services, true)) {
        $errorMessage = "Please select a valid service.";

    } elseif (
        strlen($name) > 100 ||
        strlen($phone) > 30 ||
        strlen($message) > 2000
    ) {
        $errorMessage = "Some information is too long. Please shorten it.";

    } else {
        require_once __DIR__ . "/db.php";

        $stmt = $conn->prepare(
            "INSERT INTO enquiries
            (customer_name, phone, service, message)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt === false) {
            $errorMessage = "Unable to prepare your enquiry. Please try again.";

        } else {
            $stmt->bind_param(
                "ssss",
                $name,
                $phone,
                $service,
                $message
            );

            if ($stmt->execute()) {
                $enquiry = "Hello VERONA Interior Designs!\n\n"
                    . "Name: " . $name . "\n"
                    . "Phone: " . $phone . "\n"
                    . "Service: " . $service . "\n"
                    . "Message: " . $message;

                $whatsappLink = "https://wa.me/" . $whatsappNumber
                    . "?text=" . urlencode($enquiry);

                $successMessage = "Your enquiry has been saved successfully. "
                    . "Select the WhatsApp button below to send it to VERONA.";

            } else {
                $errorMessage = "We couldn't save your enquiry. Please try again.";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        .contact-hero {
            background: #20211f;
            color: #fff;
            text-align: center;
            padding: 75px 7%;
        }

        .contact-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 5vw, 56px);
            font-weight: 400;
        }

        .contact-hero h1 span {
            color: #c6a56a;
        }

        .contact-hero p:not(.eyebrow) {
            max-width: 600px;
            margin: 15px auto 0;
            color: #d0d0c9;
            font-size: 14px;
        }

        .contact-layout {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 50px;
            align-items: start;
        }

        .contact-info h2,
        .contact-form h2 {
            font-family: 'Playfair Display', serif;
            font-size: 31px;
            font-weight: 400;
            margin-bottom: 18px;
        }

        .contact-info > p {
            color: #777870;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .contact-item {
            padding: 18px 0;
            border-bottom: 1px solid #dedbd2;
        }

        .contact-item strong {
            display: block;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .contact-item p,
        .contact-item a {
            color: #777870;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .contact-form {
            background: #fff;
            padding: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #dedbd2;
            background: #fff;
            color: #20211f;
            font-family: inherit;
            font-size: 13px;
            border-radius: 0;
        }

        .form-group textarea {
            min-height: 130px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: 1px solid #c6a56a;
            border-color: #c6a56a;
        }

        .form-submit {
            width: 100%;
            border: 0;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
        }

        .notice {
            padding: 14px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .notice-success {
            background: #e7f5e9;
            color: #245c32;
        }

        .notice-error {
            background: #fbe9e7;
            color: #922d24;
        }

        .whatsapp-link {
            display: inline-block;
            margin-top: 20px;
            padding: 13px 18px;
            background: #238b55;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 700px) {
            .contact-layout {
                grid-template-columns: 1fr;
                gap: 35px;
            }

            .contact-form {
                padding: 23px;
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
        <a href="projects.php">Our Projects</a>
        <a href="contact.php" class="active">Contact</a>
    </nav>

    <a href="#enquiry-form" class="header-btn">Get a Quote</a>
</header>

<main>
    <section class="contact-hero">
        <p class="eyebrow">LET'S TALK ABOUT YOUR SPACE</p>
        <h1>Let's Create Something <span>Beautiful.</span></h1>
        <p>
            Have an idea for your ceiling, kitchen or home interior?
            Tell us about your project and get in touch with VERONA.
        </p>
    </section>

    <section class="section-padding">
        <div class="contact-layout">

            <div class="contact-info">
                <p class="eyebrow">GET IN TOUCH</p>
                <h2>We'd love to hear from you.</h2>

                <p>
                    Share your ideas, ask about our services or tell us
                    what you'd like to transform in your home.
                </p>

                <div class="contact-item">
                    <strong>Our Services</strong>
                    <p>Ceilings, kitchens and interior finishing</p>
                </div>

                <div class="contact-item">
                    <strong>WhatsApp</strong>
                    <a href="<?php echo htmlspecialchars($whatsappLink); ?>"
                       target="_blank"
                       rel="noopener noreferrer">
                        Chat with VERONA
                    </a>
                </div>

                <div class="contact-item">
                    <strong>Business Hours</strong>
                    <p>Contact us to arrange a consultation.</p>
                </div>
            </div>

            <div class="contact-form" id="enquiry-form">
                <h2>Send an Enquiry</h2>

                <?php if ($successMessage !== ""): ?>
                    <div class="notice notice-success">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>

                    <a class="whatsapp-link"
                       href="<?php echo htmlspecialchars($whatsappLink); ?>"
                       target="_blank"
                       rel="noopener noreferrer">
                        Continue to WhatsApp →
                    </a>
                <?php endif; ?>

                <?php if ($errorMessage !== ""): ?>
                    <div class="notice notice-error">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="100"
                            value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            maxlength="30"
                            value="<?php echo htmlspecialchars($_POST["phone"] ?? ""); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="service">Service Required</label>
                        <select id="service" name="service" required>
                            <option value="">Choose a service</option>

                            <?php foreach ($services as $option): ?>
                                <option
                                    value="<?php echo htmlspecialchars($option); ?>"
                                    <?php echo (($_POST["service"] ?? "") === $option) ? "selected" : ""; ?>
                                >
                                    <?php echo htmlspecialchars($option); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Tell Us About Your Project</label>
                        <textarea
                            id="message"
                            name="message"
                            maxlength="2000"
                            required
                        ><?php echo htmlspecialchars($_POST["message"] ?? ""); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-gold form-submit">
                        Prepare WhatsApp Enquiry
                    </button>
                </form>
            </div>

        </div>
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

</body>
</html>