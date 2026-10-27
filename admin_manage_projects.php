<?php
session_start();

if (empty($_SESSION["verona_admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once __DIR__ . "/db.php";
$notice = "";
$notice_type = "";

if (isset($_GET["updated"]) && $_GET["updated"] === "1") {
    $notice = "Project updated successfully!";
    $notice_type = "success";
} elseif (isset($_GET["deleted"]) && $_GET["deleted"] === "1") {
    $notice = "Project deleted successfully!";
    $notice_type = "success";
} elseif (isset($_GET["deleted"]) && $_GET["deleted"] === "0") {
    $notice = "Project was not found or could not be deleted.";
    $notice_type = "error";
}

// Retrieve all saved projects.
$projects = [];
$error = "";

$result = $conn->query(
    "SELECT id, title, category, description, image_url, created_at
     FROM projects
     ORDER BY created_at DESC, id DESC"
);

if ($result) {
    $projects = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Unable to load projects. Please check your database.";
}

function e($value) {
    return htmlspecialchars(
        (string) ($value ?? ""),
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
    <?php if ($notice !== ""): ?>
    <div class="project-notice <?= htmlspecialchars($notice_type, ENT_QUOTES, 'UTF-8') ?>">
        <?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
    <title>Manage Projects | VERONA Admin</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        body {
            background: #f5f4f0;
            color: #20211f;
        }

        .admin-header {
            background: #20211f;
            color: white;
            padding: 22px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        .admin-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 27px;
            font-weight: 400;
        }

        .admin-header h1 span {
            color: #c6a56a;
        }

        .admin-header a {
            color: #fff;
            font-size: 13px;
        }

        .manage-container {
            max-width: 1250px;
            margin: auto;
            padding: 40px 6%;
        }

        .manage-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .manage-heading h2 {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 400;
        }

        .manage-heading p {
            color: #777870;
            font-size: 14px;
            margin-top: 8px;
        }

        .project-panel {
            background: #fff;
            border: 1px solid #e7e4dc;
            padding: 24px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #e7e4dc;
            text-align: left;
            vertical-align: top;
            font-size: 13px;
        }

        th {
            background: #f5f4f0;
        }

        .project-thumb {
            width: 85px;
            height: 65px;
            object-fit: cover;
            background: #e5e1d8;
        }

        .project-title {
            font-weight: 700;
            color: #20211f;
        }

        .action-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .edit-link {
            color: #8e6d36;
            font-weight: 700;
            text-decoration: none;
        }

        .delete-button {
            border: 1px solid #b53c32;
            color: #b53c32;
            background: white;
            padding: 7px 10px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
        }

        .delete-button:hover {
            background: #b53c32;
            color: white;
        }

        .empty-state, .error-message {
            padding: 20px 0;
            color: #777870;
        }

        .error-message {
            color: #922d24;
        }

        .manage-footer {
            text-align: center;
            padding: 25px;
            color: #777870;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .manage-container {
                padding: 28px 5%;
            }

            .project-panel {
                padding: 14px;
            }
        }
        .project-notice {
    padding: 14px 18px;
    margin: 15px 0 20px;
    border-radius: 6px;
    font-weight: bold;
}

.project-notice.success {
    background: #e8f5e9;
    color: #216e39;
    border: 1px solid #b7dfbd;
}

.project-notice.error {
    background: #fdeaea;
    color: #a12626;
    border: 1px solid #f2bcbc;
}
    </style>
</head>
<body>

<header class="admin-header">
    <h1>VERONA<span>.</span> Admin</h1>

    <div style="display:flex;gap:18px;flex-wrap:wrap;">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_add_project.php">Add Project</a>
        <a href="projects.php">View Website</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</header>

<main class="manage-container">

    <section class="manage-heading">
        <div>
            <h2>Manage Projects</h2>
            <p>View and manage projects displayed in your public gallery.</p>
        </div>

        <a href="admin_add_project.php" class="btn btn-gold">
            + Add Project
        </a>
    </section>

    <section class="project-panel">

        <?php if ($error !== ""): ?>

            <p class="error-message"><?php echo e($error); ?></p>

        <?php elseif (empty($projects)): ?>

            <p class="empty-state">
                No projects have been added yet.
                <a href="admin_add_project.php">Add your first project</a>.
            </p>

        <?php else: ?>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($project["image_url"])): ?>
                                        <img
                                            class="project-thumb"
                                            src="<?php echo e($project["image_url"]); ?>"
                                            alt="<?php echo e($project["title"]); ?>"
                                            loading="lazy"
                                        >
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>

                                <td class="project-title">
                                    <?php echo e($project["title"]); ?>
                                </td>

                                <td>
                                    <?php echo e($project["category"]); ?>
                                </td>

                                <td>
                                    <?php
                                    $description = (string) ($project["description"] ?? "");
                                    echo e(
                                        strlen($description) > 100
                                            ? substr($description, 0, 100) . "..."
                                            : $description
                                    );
                                    ?>
                                </td>

                                <td><?php echo e($project["created_at"]); ?></td>

                                <td>
                                    <div class="action-links">
                                        <a
                                            class="edit-link"
                                            href="admin_edit_project.php?id=<?php echo (int) $project["id"]; ?>"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="admin_delete_project.php"
                                            onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?php echo (int) $project["id"]; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?php
                                                if (empty($_SESSION["verona_csrf"])) {
                                                    $_SESSION["verona_csrf"] = bin2hex(random_bytes(32));
                                                }
                                                echo e($_SESSION["verona_csrf"]);
                                                ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="delete-button"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </section>

</main>

<footer class="manage-footer">
    &copy; <?php echo date("Y"); ?> VERONA Interior Designs — Project Management
</footer>

</body>
</html>