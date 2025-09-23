<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "transport");

if (!isset($_SESSION['id'])) {
    die("You must log in to book");
}

if (isset($_POST['booking-submit'])) {
    $veh_id = intval($_POST['veh_id']);
    $user_id = $_SESSION['id'];
    $renter_id = intval($_POST['ren_id']);
    $date = $_POST['date'];

    // Handle file upload
    $doc = "";
    if (isset($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['doc']['name']);
        $targetPath = "document/" . $filename;
        
        if (move_uploaded_file($_FILES['doc']['tmp_name'], $targetPath)) {
            $doc = mysqli_real_escape_string($con, $targetPath);
        } else {
            die("Error uploading file");
        }
    }

    // Insert booking
    $sql = "INSERT INTO booking_tbl (veh_id, cus_id, boo_status, ren_id, cus_doc, date) 
            VALUES ($veh_id, $user_id, 'pending', $renter_id, '$doc', '$date')";

    if (mysqli_query($con, $sql)) {
        header("Location: home.php?view=listvehicle");
        exit();
    } else {
        echo "❌ Error: " . mysqli_error($con);
    }
}

// Handle accept/reject requests - IMPROVED VERSION
if (isset($_POST['boo_id'], $_POST['action'])) {
    $boo_id = intval($_POST['boo_id']);
    $action = mysqli_real_escape_string($con, $_POST['action']);

    // Debug logging
    error_log("Debug - Processing booking ID: " . $boo_id . ", action: " . $action);

    if ($action === 'accept') {
        $status = 'booked';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        $status = 'pending';
    }

    // Properly escaped SQL query
    $sql = "UPDATE booking_tbl 
            SET boo_status = '" . mysqli_real_escape_string($con, $status) . "' 
            WHERE boo_id = " . $boo_id;

    error_log("Debug - SQL Query: " . $sql);

    if (mysqli_query($con, $sql)) {
        // Check if any rows were actually affected
        $affected_rows = mysqli_affected_rows($con);
        if ($affected_rows > 0) {
            echo json_encode([
                'success' => true, 
                'message' => "Booking updated to: $status",
                'affected_rows' => $affected_rows
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "No booking found with ID: $boo_id"
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "Database error: " . mysqli_error($con)
        ]);
    }
    exit;
}

// Handle vehicle removal
if (isset($_GET['id']) && $_GET['status'] === 'remove') {
    $veh_id = intval($_GET['id']);
    $delete_query = "DELETE FROM vehicle_tbl WHERE veh_id = $veh_id";

    if (mysqli_query($con, $delete_query)) {
        echo "<script>alert('✅ Vehicle removed successfully'); window.location='rhome.php';</script>";
    } else {
        echo "❌ Error removing vehicle: " . mysqli_error($con);
    }
}

// Handle vehicle edit (if you want to add this functionality)
if (isset($_GET['id']) && $_GET['status'] === 'edit') {
    $veh_id = intval($_GET['id']);
    // Redirect to edit page or show edit form
    echo "<script>alert('Edit functionality - Vehicle ID: $veh_id'); window.location='rhome.php';</script>";
}
?>