<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "transport");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boo_id = $_POST['booking-id'];
    $ren    = $_POST['ren'];
    $cus    = $_POST['cus'];
    $method = $_POST['method']; // comes from hidden input
    $amount = $_POST['amount'];

    // 1️⃣ Fetch booking status
    $check = mysqli_query($con, "SELECT status FROM payment_tbl where boo_id ='$boo_id'");
    $row   = mysqli_fetch_assoc($check);

    if ($row && $row['status'] === 'completed') {
      echo "<script>alert('⚠️ Payment already exists or booking not pending'); window.location.href='home.php';</script>";
    }
    else {
       
        $sql1 = "INSERT INTO payment_tbl (boo_id, cus_id, ren_id, method, amount, status) 
                 VALUES ('$boo_id', '$cus', '$ren', '$method', '$amount', 'completed')";
        if (mysqli_query($con, $sql1)) {
            // 3️⃣ Update booking status to 'paid'
            mysqli_query($con, "UPDATE booking_tbl SET boo_status='' WHERE boo_id='$boo_id'");
            echo "<script>alert('✅ Payment successful!'); window.location.href='home.php';</script>";
        } else {
            echo "❌ Error inserting payment: " . mysqli_error($con);
        }
    } }
       
   
?>
