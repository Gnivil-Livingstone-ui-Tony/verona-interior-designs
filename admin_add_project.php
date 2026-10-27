<?php
session_start();

if (empty($_SESSION["verona_admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once __DIR__ . "/db.php";

$error = "";
$success = "";

$title = "";
$description = "";
$category = "";
$imageUrl = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $imageUrl = trim($_POST["image_url"] ?? "");

    $categories = [
        "Ceiling Design",
        "Kitchen Design",
        "Interior Decoration",
        "House Finishing",
        "Other"
    ];

    if ($title === "" || $category === "") {
        $error = "Please enter a project title and category.";
    } elseif (!in_array($category, $categories, true)) {
        $error = "Please select a valid project category.";
    } elseif (
        strlen($title) > 150 ||
        strlen($category) > 100 ||
        strlen($description) > 5000 ||
        strlen($imageUrl) > 255
    ) {
        $error = "Some information is too long.";
    } elseif ($imageUrl !== "") {
        // Accept a local path inside the website folder or an HTTP(S) URL.
        $isRemoteUrl = filter_var($imageUrl, FILTER_VALIDATE_URL)
            && in_array(
                strtolower((string) parse_url($imageUrl, PHP_URL_SCHEME)),
                ["http", "https"],
                true
            );

        $isLocalPath =
            !preg_match('/^[a-z][a-z0-9+.-]*:/i', $imageUrl) &&
            !str_contains($imageUrl, '..') &&
            !str_contains($imageUrl, '\\') &&
            !str_contains($imageUrl, "\0") &&
            !str_starts_with($imageUrl, '/') &&
            !str_starts_with($imageUrl, '//') &&
            preg_match(
                '/^[a-zA-Z0-9_\-\/. ]+\.(jpg|jpeg|png|gif|webp)$/i',
                $imageUrl
            );

        if (!$isRemoteUrl && !$isLocalPath) {
            $error = "Enter a valid HTTP/HTTPS image URL or a relative image path such as assets/images/kitchen.jpg.";
        }
    }

    if ($error === "") {
        $stmt = $conn->prepare(
            "INSERT INTO projects (title, description, image_url, category)
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            $error = "Unable to prepare the project. Please try again.";
        } else {
            $stmt->bind_param(
                "ssss",
                $title,
                $description,
                $imageUrl,
                $category
            );

            if ($stmt->execute()) {
                $success = "Project added successfully!";
                $title = "";
                $description = "";
                $category = "";
                $imageUrl = "";
            } else {
                $error = "Unable to save the project. Please try again.";
            }

            $stmt->close();
        }
    }
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Project | VERONA Admin</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        body {
            background: #f5f4f0;
            color: #20211f;
        }

        .admin-top {
            background: #20211f;
            color: #fff;
            padding: 22px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-top h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
        }

        .admin-top span {
            color: #c6a56a;
        }

        .admin-top a {
            color: #fff;
            font-size: 13px;
        }

        .project-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .project-card {
            background: #fff;
            border: 1px solid #e7e4dc;
            padding: 30px;
        }

        .project-card h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .project-card > p {
            color: #777870;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 13px;
            border: 1px solid #dedbd2;
            background: #fff;
            color: #20211f;
            font: inherit;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .help-text {
            display: block;
            color: #777870;
            font-size: 12px;
            line-height: 1.6;
            margin-top: 7px;
        }

        .notice {
            padding: 13px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .success {
            background: #e7f5e9;
            color: #245c32;
        }

        .error {
            background: #fbe9e7;
            color: #922d24;
        }

        .project-card button {
            cursor: pointer;
        }

        .image-preview {
            display: none;
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            margin-top: 12px;
            border: 1px solid #dedbd2;
        }

        @media (max-width: 600px) {
            .project-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header class="admin-top">
    <h1>VERONA<span>.</span> Admin</h1>

    <div style="display:flex;gap:18px;flex-wrap:wrap;">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="projects.php">View Projects Page</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</header>

<main class="project-container">
    <section class="project-card">
        <h2>Add a Project</h2>

        <p>
            Enter details for an interior design project to display
            on VERONA's website.
        </p>

        <?php if ($success !== ""): ?>
            <div class="notice success">
                <?php echo e($success); ?>
                <a href="projects.php">View projects</a>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="notice error">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="admin_add_project.php">

            <div class="form-group">
                <label for="title">Project Title *</label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    maxlength="150"
                    value="<?php echo e($title); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="category">Category *</label>

                <select id="category" name="category" required>
                    <option value="">Choose category</option>

                    <?php
                    $categories = [
                        "Ceiling Design",
                        "Kitchen Design",
                        "Interior Decoration",
                        "House Finishing",
                        "Other"
                    ];
                    ?>

                    <?php foreach ($categories as $option): ?>
                        <option
                            value="<?php echo e($option); ?>"
                            <?php echo $category === $option ? "selected" : ""; ?>
                        >
                            <?php echo e($option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Project Description</label>

                <textarea
                    id="description"
                    name="description"
                    maxlength="5000"
                ><?php echo e($description); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image_url">Project Image (optional)</label>

                <input
                    type="text"
                    id="image_url"
                    name="image_url"
                    maxlength="255"
                    placeholder="assets/images/kitchen.jpg"
                    value="<?php echo e($imageUrl); ?>"
                >

                <span class="help-text">
                    Use a local path such as
                    assets/images/kitchen.jpg, or an online image URL
                    beginning with https:// or http://.
                </span>

                <img
                    id="imagePreview"
                    class="image-preview"
                    alt="Project image preview"
                >
            </div>

            <button type="submit" class="btn btn-gold">
                Save Project
            </button>
        </form>
    </section>
</main>

<script>
const imageInput = document.getElementById("image_url");
const imagePreview = document.getElementById("imagePreview");

imageInput.addEventListener("input", function () {
    const path = this.value.trim();

    if (path !== "") {
        imagePreview.src = path;
        imagePreview.style.display = "block";
    } else {
        imagePreview.removeAttribute("src");
        imagePreview.style.display = "none";
    }
});

imagePreview.addEventListener("error", function () {
    this.style.display = "none";
});

imagePreview.addEventListener("load", function () {
    this.style.display = "block";
});
</script>

</body>
</html>