<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transport");

if (!isset($_SESSION['id'])) {
    die("You must log in to book");
}

if (isset($_POST['veh_id'])) {
    $veh_id = intval($_POST['veh_id']);
    $user_id = $_SESSION['id'];
    $renter_id= intval($_POST['ren_id']);;

    $sql = "INSERT INTO booking_tbl (veh_id, cus_id, boo_status,ren_id) VALUES ($veh_id, $user_id, 'pending',$renter_id)";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Booking request sent!";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}



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



?>
