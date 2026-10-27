<?php
session_start();

if (empty($_SESSION["verona_admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once __DIR__ . "/db.php";

// Get total enquiries
$totalResult = $conn->query(
    "SELECT COUNT(*) AS total FROM enquiries"
);
$totalEnquiries = $totalResult
    ? (int) $totalResult->fetch_assoc()["total"]
    : 0;

// Get enquiries received today
$todayResult = $conn->query(
    "SELECT COUNT(*) AS total
     FROM enquiries
     WHERE DATE(created_at) = CURDATE()"
);
$todayEnquiries = $todayResult
    ? (int) $todayResult->fetch_assoc()["total"]
    : 0;

// Get recent enquiries
$enquiriesResult = $conn->query(
    "SELECT id, customer_name, phone, service, message, created_at
     FROM enquiries
     ORDER BY created_at DESC, id DESC
     LIMIT 20"
);

$recentEnquiries = $enquiriesResult
    ? $enquiriesResult->fetch_all(MYSQLI_ASSOC)
    : [];

function escapeHtml($value) {
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

    <title>VERONA | Admin Dashboard</title>

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
            gap: 20px;
            flex-wrap: wrap;
        }

        .admin-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            font-size: 27px;
        }

        .admin-header h1 span {
            color: #c6a56a;
        }

        .admin-header a {
            color: #fff;
            font-size: 13px;
        }

        .admin-container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 45px 6%;
        }

        .admin-intro {
            margin-bottom: 30px;
        }

        .admin-intro h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            font-size: 34px;
            margin-bottom: 8px;
        }

        .admin-intro p {
            color: #777870;
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: #fff;
            padding: 28px;
            border: 1px solid #e7e4dc;
        }

        .stat-card p {
            color: #777870;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .stat-card h3 {
            font-size: 36px;
            font-weight: 500;
            color: #b08d4f;
        }

        .table-panel {
            background: #fff;
            border: 1px solid #e7e4dc;
            padding: 25px;
        }

        .table-panel h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            font-size: 25px;
            margin-bottom: 22px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            text-align: left;
            padding: 15px 12px;
            border-bottom: 1px solid #e7e4dc;
            vertical-align: top;
            font-size: 13px;
        }

        th {
            background: #f5f4f0;
            font-weight: 700;
        }

        td {
            color: #55564f;
        }

        .customer-name {
            color: #20211f;
            font-weight: 700;
        }

        .empty-state {
            color: #777870;
            padding: 25px 0;
        }

        .admin-footer {
            text-align: center;
            color: #777870;
            padding: 25px;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .admin-container {
                padding: 30px 5%;
            }

            .table-panel {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

<header class="admin-header">
    <h1>VERONA<span>.</span> Admin</h1>
    <div style="display:flex;gap:18px;align-items:center;">
    <a href="index.php">View Website →</a>
    <a href="admin_add_project.php" class="btn btn-gold"> +Add Project </a>
    <a href="admin_manage_projects.php">Manage Projects</a>
    <a href="admin_logout.php">Logout</a>
</div>
</header>

<main class="admin-container">

    <section class="admin-intro">
        <h2>Dashboard Overview</h2>
        <p>Monitor customer enquiries submitted through VERONA's website.</p>
    </section>

    <section class="stats-grid">
        <div class="stat-card">
            <p>Total Customer Enquiries</p>
            <h3><?php echo $totalEnquiries; ?></h3>
        </div>

        <div class="stat-card">
            <p>Enquiries Received Today</p>
            <h3><?php echo $todayEnquiries; ?></h3>
        </div>
    </section>

    <section class="table-panel">
        <h3>Recent Customer Enquiries</h3>

        <?php if (count($recentEnquiries) > 0): ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Service</th>
                            <th>Message</th>
                            <th>Date Received</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($recentEnquiries as $enquiry): ?>
                            <tr>
                                <td><?php echo (int) $enquiry["id"]; ?></td>
                                <td class="customer-name">
                                    <?php echo escapeHtml($enquiry["customer_name"]); ?>
                                </td>
                                <td>
                                    <?php echo escapeHtml($enquiry["phone"]); ?>
                                </td>
                                <td>
                                    <?php echo escapeHtml($enquiry["service"]); ?>
                                </td>
                                <td>
                                    <?php echo nl2br(escapeHtml($enquiry["message"])); ?>
                                </td>
                                <td>
                                    <?php echo escapeHtml($enquiry["created_at"]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-state">
                No customer enquiries have been received yet.
            </p>
        <?php endif; ?>
    </section>

</main>

<footer class="admin-footer">
    &copy; <?php echo date("Y"); ?> VERONA Interior Designs — Admin Dashboard
</footer>

</body>
</html>