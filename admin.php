<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "transport");

// Check database connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proper admin authentication - replace temporary session with real login check
if (!isset($_SESSION['admin_id'])) {
    // Remove this temporary bypass in production
    $_SESSION['admin_id'] = 1; // Temporary for demo
    $_SESSION['admin_name'] = 'Admin';
    // Redirect to admin login page instead
    // header("Location: admin_login.php");
    // exit();
}

// Handle various admin actions with proper validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Delete user action
    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);
        $user_type = mysqli_real_escape_string($con, $_POST['user_type']);
        
        if ($user_type === 'customer') {
            $stmt = mysqli_prepare($con, "DELETE FROM customer_tbl WHERE cus_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
        } elseif ($user_type === 'renter') {
            $stmt = mysqli_prepare($con, "DELETE FROM renter_tbl WHERE ren_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
        }
    }
    
    // Delete vehicle action
    if (isset($_POST['delete_vehicle'])) {
        $veh_id = intval($_POST['veh_id']);
        $stmt = mysqli_prepare($con, "DELETE FROM vehicle_tbl WHERE veh_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $veh_id);
        mysqli_stmt_execute($stmt);
    }
    
    // Update booking status
    if (isset($_POST['update_booking_status'])) {
        $boo_id = intval($_POST['boo_id']);
        $new_status = mysqli_real_escape_string($con, $_POST['new_status']);
        $allowed_statuses = ['pending', 'booked', 'available'];
        
        if (in_array($new_status, $allowed_statuses)) {
            $stmt = mysqli_prepare($con, "UPDATE booking_tbl SET boo_status = ? WHERE boo_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_status, $boo_id);
            mysqli_stmt_execute($stmt);
        }
    }
    
    // Add category
    if (isset($_POST['add_category'])) {
        $cat_name = mysqli_real_escape_string($con, $_POST['cat_name']);
        if (!empty($cat_name)) {
            $stmt = mysqli_prepare($con, "INSERT INTO category_tbl (cat_name) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $cat_name);
            mysqli_stmt_execute($stmt);
        }
    }
    
    // Delete category
    if (isset($_POST['delete_category'])) {
        $cat_id = intval($_POST['cat_id']);
        $stmt = mysqli_prepare($con, "DELETE FROM category_tbl WHERE cat_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $cat_id);
        mysqli_stmt_execute($stmt);
    }
}

// Fetch statistics with proper error handling
function getStatistic($con, $query, $default = 0) {
    $result = mysqli_query($con, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['count'];
    }
    return $default;
}

$total_customers = getStatistic($con, "SELECT COUNT(*) as count FROM customer_tbl");
$total_renters = getStatistic($con, "SELECT COUNT(*) as count FROM renter_tbl");
$total_vehicles = getStatistic($con, "SELECT COUNT(*) as count FROM vehicle_tbl");
$total_bookings = getStatistic($con, "SELECT COUNT(*) as count FROM booking_tbl");
$pending_bookings = getStatistic($con, "SELECT COUNT(*) as count FROM booking_tbl WHERE boo_status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - EasyRent</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            z-index: 1000;
        }

        .sidebar h2 {
            color: #00d4ff;
            margin-bottom: 30px;
            text-align: center;
            font-size: 24px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 15px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(0, 212, 255, 0.2);
            transform: translateX(10px);
        }

        .sidebar i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #00d4ff;
        }

        .stat-card h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: none;
        }

        .section.active {
            display: block;
        }

        .section h2 {
            color: white;
            margin-bottom: 25px;
            border-bottom: 2px solid #00d4ff;
            padding-bottom: 10px;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            min-width: 800px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        th {
            background: rgba(0, 212, 255, 0.2);
            font-weight: 600;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: #ff4757;
            color: white;
        }

        .btn-warning {
            background: #ffa502;
            color: white;
        }

        .btn-success {
            background: #2ed573;
            color: white;
        }

        .btn-primary {
            background: #3742fa;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: white;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group select {
            width: 100%;
            max-width: 300px;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 14px;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-inline {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .vehicle-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #ffa502;
            color: white;
        }

        .status-booked {
            background: #2ed573;
            color: white;
        }

        .status-available {
            background: #747d8c;
            color: white;
        }

        .status-completed {
            background: #2ed573;
            color: white;
        }

        .status-failed {
            background: #ff4757;
            color: white;
        }

        .logout-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: #ff4757;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ff3838;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid transparent;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .no-data {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-cog"></i> Admin Panel</h2>
        <ul>
            <li><a href="#" onclick="showSection('dashboard')" class="active" id="dashboard-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="#" onclick="showSection('users')" id="users-link">
                <i class="fas fa-users"></i> Manage Users
            </a></li>
            <li><a href="#" onclick="showSection('vehicles')" id="vehicles-link">
                <i class="fas fa-car"></i> Manage Vehicles
            </a></li>
            <li><a href="#" onclick="showSection('bookings')" id="bookings-link">
                <i class="fas fa-calendar-check"></i> Manage Bookings
            </a></li>
            <li><a href="#" onclick="showSection('categories')" id="categories-link">
                <i class="fas fa-tags"></i> Manage Categories
            </a></li>
            <li><a href="#" onclick="showSection('payments')" id="payments-link">
                <i class="fas fa-credit-card"></i> View Payments
            </a></li>
        </ul>
        <a href="login.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> EasyRent Admin Dashboard</h1>
            <p>Welcome to the administrative control panel</p>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard" class="section active">
            <h2>Dashboard Overview</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-user-friends"></i>
                    <h3><?php echo $total_customers; ?></h3>
                    <p>Total Customers</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-tie"></i>
                    <h3><?php echo $total_renters; ?></h3>
                    <p>Total Renters</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-car"></i>
                    <h3><?php echo $total_vehicles; ?></h3>
                    <p>Total Vehicles</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <h3><?php echo $pending_bookings; ?></h3>
                    <p>Pending Bookings</p>
                </div>
            </div>
        </div>

        <!-- Users Management Section -->
        <div id="users" class="section">
            <h2>User Management</h2>
            
            <h3 style="color: white; margin: 20px 0;">Customers</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $customers = mysqli_query($con, "SELECT * FROM customer_tbl ORDER BY cus_id DESC");
                        if ($customers && mysqli_num_rows($customers) > 0) {
                            while ($customer = mysqli_fetch_assoc($customers)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($customer['cus_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($customer['cus_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($customer['cus_mail']) . "</td>";
                                echo "<td>" . htmlspecialchars($customer['cus_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($customer['cus_gen']) . "</td>";
                                echo "<td>
                                    <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this customer?\")'>
                                        <input type='hidden' name='user_id' value='" . htmlspecialchars($customer['cus_id']) . "'>
                                        <input type='hidden' name='user_type' value='customer'>
                                        <button type='submit' name='delete_user' class='btn btn-danger'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='no-data'>No customers found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h3 style="color: white; margin: 20px 0;">Renters</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $renters = mysqli_query($con, "SELECT * FROM renter_tbl ORDER BY ren_id DESC");
                        if ($renters && mysqli_num_rows($renters) > 0) {
                            while ($renter = mysqli_fetch_assoc($renters)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($renter['ren_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($renter['ren_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($renter['ren_mail']) . "</td>";
                                echo "<td>" . htmlspecialchars($renter['ren_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($renter['ren_gen']) . "</td>";
                                echo "<td>
                                    <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this renter?\")'>
                                        <input type='hidden' name='user_id' value='" . htmlspecialchars($renter['ren_id']) . "'>
                                        <input type='hidden' name='user_type' value='renter'>
                                        <button type='submit' name='delete_user' class='btn btn-danger'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='no-data'>No renters found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vehicles Management Section -->
        <div id="vehicles" class="section">
            <h2>Vehicle Management</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Owner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $vehicles = mysqli_query($con, "
                            SELECT v.*, r.ren_name 
                            FROM vehicle_tbl v 
                            LEFT JOIN renter_tbl r ON v.ren_id = r.ren_id 
                            ORDER BY v.veh_id DESC
                        ");
                        if ($vehicles && mysqli_num_rows($vehicles) > 0) {
                            while ($vehicle = mysqli_fetch_assoc($vehicles)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($vehicle['veh_id']) . "</td>";
                                echo "<td><img src='" . htmlspecialchars($vehicle['veh_img']) . "' class='vehicle-img' alt='Vehicle'></td>";
                                echo "<td>" . htmlspecialchars($vehicle['veh_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($vehicle['veh_model']) . "</td>";
                                echo "<td>₹" . htmlspecialchars($vehicle['veh_price']) . "</td>";
                                echo "<td>" . htmlspecialchars($vehicle['cat_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($vehicle['veh_loc']) . "</td>";
                                echo "<td>" . htmlspecialchars($vehicle['ren_name']) . "</td>";
                                echo "<td>
                                    <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this vehicle?\")'>
                                        <input type='hidden' name='veh_id' value='" . htmlspecialchars($vehicle['veh_id']) . "'>
                                        <button type='submit' name='delete_vehicle' class='btn btn-danger'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-data'>No vehicles found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bookings Management Section -->
        <div id="bookings" class="section">
            <h2>Booking Management</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $bookings = mysqli_query($con, "
                            SELECT b.*, c.cus_name, v.veh_name, r.ren_name
                            FROM booking_tbl b
                            LEFT JOIN customer_tbl c ON b.cus_id = c.cus_id
                            LEFT JOIN vehicle_tbl v ON b.veh_id = v.veh_id
                            LEFT JOIN renter_tbl r ON b.ren_id = r.ren_id
                            ORDER BY b.boo_id DESC
                        ");
                        if ($bookings && mysqli_num_rows($bookings) > 0) {
                            while ($booking = mysqli_fetch_assoc($bookings)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($booking['boo_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($booking['cus_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($booking['veh_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($booking['date']) . "</td>";
                                echo "<td><span class='status-badge status-" . htmlspecialchars($booking['boo_status']) . "'>" . ucfirst(htmlspecialchars($booking['boo_status'])) . "</span></td>";
                                echo "<td>";
                                if ($booking['cus_doc']) {
                                    echo "<a href='" . htmlspecialchars($booking['cus_doc']) . "' target='_blank' class='btn btn-primary'>View Doc</a>";
                                } else {
                                    echo "No document";
                                }
                                echo "</td>";
                                echo "<td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='boo_id' value='" . htmlspecialchars($booking['boo_id']) . "'>
                                        <select name='new_status' class='btn btn-warning' style='padding: 4px 8px; font-size: 12px;'>
                                            <option value='pending'" . ($booking['boo_status'] == 'pending' ? ' selected' : '') . ">Pending</option>
                                            <option value='booked'" . ($booking['boo_status'] == 'booked' ? ' selected' : '') . ">Booked</option>
                                            <option value='available'" . ($booking['boo_status'] == 'available' ? ' selected' : '') . ">Available</option>
                                        </select>
                                        <button type='submit' name='update_booking_status' class='btn btn-success'>Update</button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='no-data'>No bookings found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories Management Section -->
        <div id="categories" class="section">
            <h2>Category Management</h2>
            
            <div class="form-group">
                <form method="post">
                    <label>Add New Category:</label>
                    <div class="form-inline">
                        <input type="text" name="cat_name" placeholder="Category Name" required>
                        <button type="submit" name="add_category" class="btn btn-success">Add Category</button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fixed query to select cat_name as the primary key
                        $categories = mysqli_query($con, "SELECT cat_name FROM category_tbl ORDER BY cat_name ASC");
                        if ($categories && mysqli_num_rows($categories) > 0) {
                            while ($category = mysqli_fetch_assoc($categories)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($category['cat_name']) . "</td>";
                                echo "<td>
                                    <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this category?\")'>
                                        <input type='hidden' name='cat_id' value='" . htmlspecialchars($category['cat_name']) . "'>
                                        <button type='submit' name='delete_category' class='btn btn-danger'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='no-data'>No categories found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments Section -->
        <div id="payments" class="section">
            <h2>Payment History</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Renter</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $payments = mysqli_query($con, "
                            SELECT p.*, c.cus_name, r.ren_name
                            FROM payment_tbl p
                            LEFT JOIN customer_tbl c ON p.cus_id = c.cus_id
                            LEFT JOIN renter_tbl r ON p.ren_id = r.ren_id
                            ORDER BY p.pay_id DESC
                        ");
                        
                        if ($payments && mysqli_num_rows($payments) > 0) {
                            while ($payment = mysqli_fetch_assoc($payments)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($payment['pay_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($payment['boo_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($payment['cus_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($payment['ren_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($payment['method']) . "</td>";
                                echo "<td>₹" . htmlspecialchars($payment['amount']) . "</td>";
                                echo "<td><span class='status-badge status-" . strtolower(htmlspecialchars($payment['status'])) . "'>" . ucfirst(htmlspecialchars($payment['status'])) . "</span></td>";
                                echo "<td>" . date('Y-m-d H:i', strtotime($payment['pay_date'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='no-data'>No payments found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all links
            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => {
                link.classList.remove('active');
            });

            // Show selected section
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Add active class to clicked link
            const targetLink = document.getElementById(sectionId + '-link');
            if (targetLink) {
                targetLink.classList.add('active');
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent forms from submitting without confirmation
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to perform this action?')) {
                        e.preventDefault();
                    }
                });
            });
        });

        // Auto-refresh stats every 60 seconds (optional)
        setInterval(function() {
            if (document.getElementById('dashboard').classList.contains('active')) {
                // Only refresh if on dashboard to avoid disrupting user work
                location.reload();
            }
        }, 60000);
    </script>
</body>
</html>