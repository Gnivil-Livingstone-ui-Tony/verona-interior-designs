<?php
session_start();

if (!isset($_SESSION["verona_admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once "db.php";

$allowed_categories = [
    "Living Room",
    "Bedroom",
    "Kitchen",
    "Office",
    "Bathroom",
    "Other"
];

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: admin_manage_projects.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, title, description, image_url, category
     FROM projects WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

if (!$project) {
    header("Location: admin_manage_projects.php");
    exit;
}

$title = $project["title"];
$description = $project["description"] ?? "";
$image_url = $project["image_url"] ?? "";
$category = $project["category"] ?? "Other";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $image_url = trim($_POST["image_url"] ?? "");
    $category = trim($_POST["category"] ?? "");

    if ($title === "") {
        $errors[] = "Project title is required.";
    }

    if (!in_array($category, $allowed_categories, true)) {
        $errors[] = "Please select a valid category.";
    }

    if ($image_url !== "") {
        $is_url = filter_var($image_url, FILTER_VALIDATE_URL)
            && in_array(
                strtolower((string) parse_url($image_url, PHP_URL_SCHEME)),
                ["http", "https"],
                true
            );

        $is_local_path = preg_match(
            '~^assets/images/[A-Za-z0-9._/-]+$~',
            $image_url
        ) && strpos($image_url, "..") === false;

        if (!$is_url && !$is_local_path) {
            $errors[] = "Use an HTTP/HTTPS image URL or a path like assets/images/kitchen.jpg.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "UPDATE projects
             SET title = ?, description = ?, image_url = ?, category = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $title,
            $description,
            $image_url,
            $category,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: admin_manage_projects.php?updated=1");
            exit;
        } else {
            $errors[] = "Unable to update the project. Please try again.";
            $stmt->close();
        }
    }
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project | VERONA Interior Designs</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .edit-project-container {
            max-width: 750px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            color: #222;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .edit-project-container h1 {
            margin-bottom: 10px;
        }
        .edit-project-container label {
            display: block;
            margin: 18px 0 7px;
            font-weight: bold;
        }
        .edit-project-container input,
        .edit-project-container textarea,
        .edit-project-container select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font: inherit;
        }
        .edit-project-container textarea {
            min-height: 120px;
            resize: vertical;
        }
        .edit-project-container button {
            margin-top: 20px;
            padding: 12px 22px;
            border: 0;
            border-radius: 5px;
            background: #b08d57;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }
        .edit-project-container .back-link {
            display: inline-block;
            margin-left: 12px;
            color: #70501e;
        }
        .edit-project-container .error {
            padding: 12px;
            margin: 15px 0;
            background: #fff0f0;
            color: #9b1c1c;
            border-radius: 5px;
        }
        .edit-project-container .current-image {
            max-width: 220px;
            max-height: 150px;
            object-fit: cover;
            margin-top: 10px;
            display: block;
        }
        @media (max-width: 600px) {
            .edit-project-container {
                margin: 20px 12px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="edit-project-container">
    <h1>Edit Project</h1>
    <p>Update the details of this interior design project.</p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="title">Project Title *</label>
        <input
            type="text"
            id="title"
            name="title"
            maxlength="150"
            value="<?= e($title) ?>"
            required
        >

        <label for="category">Category *</label>
        <select id="category" name="category" required>
            <?php foreach ($allowed_categories as $option): ?>
                <option
                    value="<?= e($option) ?>"
                    <?= $category === $option ? "selected" : "" ?>
                >
                    <?= e($option) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
        ><?= e($description) ?></textarea>

        <label for="image_url">Image URL or Local Image Path</label>
        <input
            type="text"
            id="image_url"
            name="image_url"
            value="<?= e($image_url) ?>"
            placeholder="assets/images/kitchen.jpg"
        >

        <?php if ($image_url !== ""): ?>
            <p>Current image:</p>
            <img
                class="current-image"
                src="<?= e($image_url) ?>"
                alt="Current project image"
                onerror="this.style.display='none';"
            >
        <?php endif; ?>

        <button type="submit">Save Changes</button>
        <a class="back-link" href="admin_manage_projects.php">
            Cancel
        </a>
    </form>
</div>

</body>
</html>