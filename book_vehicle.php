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
    $date= $_POST['date'];

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
    
    $sql = "INSERT INTO booking_tbl (veh_id, cus_id, boo_status, ren_id, cus_doc,date) 
            VALUES ($veh_id, $user_id, 'pending', $renter_id, '$doc','$date')";

    if (mysqli_query($con, $sql)) {
       header("Location: home.php?view=listvehicle");
        exit();
    } else {
        echo "❌ Error: " . mysqli_error($con);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $boo_id = $_POST['boo_id'];

    if (isset($_POST['accept'])) {
        $status = 'booked';
    } elseif (isset($_POST['reject'])) {
        $status = 'available';
    } else  {
        $status = 'pending';
    }

    $sql = "UPDATE booking_tbl 
            SET boo_status = '$status' 
            WHERE 
             boo_id = '$boo_id'";

    mysqli_query($con, $sql);
       

}


/*
//request accepting rejecting
// Handle Accept
if (isset($_GET['accept_id'])) {
    $boo_id = intval($_GET['accept_id']);
    mysqli_query($con, "UPDATE booking_tbl SET boo_status='accepted' WHERE boo_id=$boo_id");
}

// Handle Reject
if (isset($_GET['reject_id'])) {
    $boo_id = intval($_GET['reject_id']);
    mysqli_query($con, "UPDATE booking_tbl SET boo_status='rejected' WHERE boo_id=$boo_id");
}


if (isset($_GET['remove_id'])) {
    $veh_id = intval($_GET['remove_id']); // Prevent SQL injection
    $delete_query = "DELETE FROM vehicle_tbl WHERE veh_id = $veh_id";
    
    if (mysqli_query($con, $delete_query)) {
        echo "Vehicle removed successfully.";
    } else {
        echo "Error removing vehicle: " . mysqli_error($con);
    }
}

*/



?>
