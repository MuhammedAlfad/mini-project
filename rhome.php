<?php
session_start();

$dbhost = "localhost";
$dbusr = "root";
$dbpas = "";

$con = mysqli_connect($dbhost, $dbusr, $dbpas);
if (!$con) {
    echo "error connecting <br>";
}

$usedb = " use transport";
mysqli_query($con, $usedb);

$sql = "SELECT * FROM category_tbl";
$result = mysqli_query($con, $sql);

$category = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $category[] = $row;
    }
} else {
    echo "Error fetching categories: " . mysqli_error($con);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($con, $_POST['veh_name']);
    $model = mysqli_real_escape_string($con, $_POST['veh_mod']);
    $registerno = mysqli_real_escape_string($con, $_POST['veh_reginfo']);
    $contact = mysqli_real_escape_string($con, $_POST['contact_no']);
    $location = mysqli_real_escape_string($con, $_POST['veh_loc']);
    $description = mysqli_real_escape_string($con, $_POST['veh_des']);
    $category = mysqli_real_escape_string($con, $_POST['cat_name']);
    $price = mysqli_real_escape_string($con, $_POST['veh_price']);

    $targetDir = "uploads/";
    $imageName = basename($_FILES["veh_img"]["name"]);
    $targetFile = $targetDir . uniqid() . "_" . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["veh_img"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if (move_uploaded_file($_FILES["veh_img"]["tmp_name"], $targetFile)) {
        $upload = "INSERT INTO vehicle_tbl (veh_name, veh_model, veh_reg, veh_contact, veh_loc, veh_des, cat_name, veh_img, veh_price, ren_id) 
                 VALUES ('$name', '$model', '$registerno', '$contact', '$location', '$description', '$category', '$targetFile', '$price', '{$_SESSION['id']}')";
        
        mysqli_query($con, $upload);
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            margin: 0;
            background-image: url("img/3.jpg");
            height: 100vh;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .options {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 500px;
            height: 500px;
            flex-direction: column;
            background-color: rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(6px);
            border: 3px solid rgba(255, 255, 255, 0.1);
            gap: 50px;
            border-radius: 50px;
            box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);
        }

        .options button {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            position: relative;
            background: transparent;
            color: aliceblue;
            border: none;
            border-radius: 30px;
            box-shadow: 0px 0px 10px 2px rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(6px);
            font-size: 30px;
            display: flex;
            flex-direction: column;
            width: 350px;
            height: 50px;
            padding: 40px;
            justify-content: center;
        }

        .options button:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.2);
        }

        nav {
            color: aliceblue;
            font-family: sans-serif;
            position: absolute;
            top: 0px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(30px);
            width: 100%;
            height: 5%;
            margin: 0px;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .upload-vehicle-form {
            display: none;
            position: relative;
            align-items: flex-start;
            justify-content: flex-start;
            width: 1200px;
            height: 700px;
            flex-direction: row;
            background-color: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);
        }

        #formgroup1 {
            font-size: 20px;
            font-weight: bold;
            display: flex;
            color: white;
            gap: 60px 90px;
            padding: 90px;
            font-family: sans-serif;
            flex-wrap: wrap;
        }

        #formgroup1 input {
            color: aliceblue;
            width: 300px;
            height: 25px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
        }

        #regnum { width: 270px !important; }
        #contact { width: 277px !important; }
        #vehloc { width: 280px !important; }
        #vehcat { width: 278px !important; }

        #formgroup2 {
            color: white;
            font-size: 20px;
            font-weight: bold;
            position: absolute;
            left: 0px;
            top: 250px;
            display: flex;
            gap: 60px 90px;
            padding: 90px;
            font-family: sans-serif;
            flex-wrap: wrap;
            flex-direction: row;
        }

        textarea {
            position: absolute;
            color: white;
            resize: none;
            top: 40px;
            left: -5px;
            width: 440px;
            height: 130px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
        }

        .vehdes {
            position: absolute;
            top: 80px;
            left: 620px;
            padding: none;
        }

        select {
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            color: white;
        }

        #formgroup3 {
            position: relative;
            top: 25px;
            left: 87px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            font: sans-serif;
        }

        #submit {
            position: relative;
            top: 200px;
            left: 510px;
            color: white;
            width: 150px;
            height: 40px;
            border: none;
            box-shadow: 0px 0px 5px rgba(255, 255, 255, 0.5);
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 50px;
            font-size: 20px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: bold;
        }

        .my-vehicles {
            display: none;
            position: fixed;
            align-items: flex-start;
            justify-content: flex-start;
            width: 90%;
            height: 80%;
            flex-direction: row;
            background-color: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);
        }

        .request {
            display: none;
            position: fixed;
            align-items: flex-start;
            justify-content: flex-start;
            padding: 60px;
            top: 70px;
            width: 90%;
            height: 70%;
            flex-direction: row;
            background-color: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);
            flex-wrap: wrap;
            gap: 40px;
            overflow-y: auto;
        }

        .req {
            color: white;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            position: relative;
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            flex-direction: column;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            width: 300px;
            height: auto;
            gap: 10px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .request button {
            border-radius: 5px;
            border: none;
            padding: 8px 15px;
            font-weight: bold;
            cursor: pointer;
            margin: 2px;
        }

        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }

        .reject-btn {
            background-color: #f44336;
            color: white;
        }

        .view-btn {
            background-color: #2196F3;
            color: white;
            padding: 5px 10px;
        }

        .request button:hover {
            opacity: 0.8;
        }

        .request button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="options">
        <button id="uv">Upload Vehicle</button>
        <button id="req">Requests</button>
        <button id="mv">My vehicles</button>
    </div>

    <div class="upload-vehicle-form">
        <form action="" method="POST" enctype="multipart/form-data">
            <div id="formgroup1">
                <i id="back1" class="fa fa-arrow-left" aria-hidden="true" style="position:absolute; top: 30px; left:20px; font-size:25px;"></i>
                
                <div class="element">Vehicle Name <input type="text" name="veh_name" id="veh-name"></div>
                <div class="element">Vehicle Model <input type="text" name="veh_mod" id=""></div>
                <div class="element">Registration Info <input type="text" name="veh_reginfo" id="regnum"></div>
                <div class="element">Contact Number <input type="text" name="contact_no" id="contact"></div>
                <div class="element">Vehicle Location <input type="text" name="veh_loc" id="vehloc"></div>
                <div class="element">Vehicle Price <input type="text" name="veh_price" id=""></div>
            </div>

            <div id="formgroup2">
                <div class="element">
                    Vehicle Category
                    <select name="cat_name" value="">
                        <option>Select Type</option>
                        <?php foreach ($category as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['cat_name']); ?>">
                                <?= htmlspecialchars($cat['cat_name']); ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="vehdes">Description <textarea name="veh_des" rows="5" column="56"></textarea></div>
            </div>

            <div id="formgroup3">
                Upload Image <input type="file" name="veh_img">
            </div>

            <div id="formgroup4">
                <input type="submit" id="submit" Name="submit">
            </div>
        </form>
    </div>

    <!-- my vehicles -->
    <div class="my-vehicles">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 style="text-align: center; color: white; font-family: sans-serif; font-weight: bold;">
                MY VEHICLES
            </h1>

            <div id="formgroup1">
                <i class="back3 fa fa-arrow-left" aria-hidden="true" style="position:absolute; top: 30px; left:20px; font-size:25px;"></i>

                <?php
                $listveh = "SELECT * FROM vehicle_tbl WHERE ren_id = " . $_SESSION['id'];
                $veh_result = mysqli_query($con, $listveh);

                if (!$veh_result) {
                    echo "Query failed: " . mysqli_error($con);
                } else {
                    while ($veh = mysqli_fetch_assoc($veh_result)) {
                        echo "<div style='color: white'>";
                        echo "<strong>Name:</strong> " . htmlspecialchars($veh['veh_name']) . "<br>";
                        echo "<strong>Model:</strong> " . htmlspecialchars($veh['veh_model']) . "<br>";
                        echo "<strong>Price:</strong> ₹" . htmlspecialchars($veh['veh_price']) . "<br>";
                        echo "<img src='" . htmlspecialchars($veh['veh_img']) . "' width='150' style='border-radius:10px'><br>";
                        echo "<hr>";

                        // Edit Button
                        echo "<a href='book_vehicle.php?id=" . $veh['veh_id'] . "&status=edit' 
                                style='padding:8px 12px; background:green; color:white; text-decoration:none; border-radius:5px; margin-right:10px;'>
                                Edit</a>";

                        // Remove Button
                        echo "<a href='book_vehicle.php?id=" . $veh['veh_id'] . "&status=remove' 
                                style='padding:8px 12px; background:red; color:white; text-decoration:none; border-radius:5px;'>
                                Remove</a>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </form>
    </div>

    <!-- request view -->
    <div class="request">
        <i class="back3 fa fa-arrow-left" aria-hidden="true" style="color:white; z-index:1000; position:absolute; top: 30px; left:20px; font-size:25px;"></i>

        <?php
        $req_list = "
        SELECT vehicle_tbl.veh_name, vehicle_tbl.veh_model, vehicle_tbl.veh_img,
               booking_tbl.boo_id, booking_tbl.boo_status, booking_tbl.cus_id, booking_tbl.cus_doc,
               customer_tbl.cus_name, booking_tbl.date, booking_tbl.boo_id
        FROM vehicle_tbl
        JOIN booking_tbl 
            ON vehicle_tbl.veh_id = booking_tbl.veh_id
        JOIN customer_tbl 
            ON booking_tbl.cus_id = customer_tbl.cus_id
        WHERE vehicle_tbl.ren_id = '{$_SESSION['id']}'
          AND booking_tbl.boo_status = 'pending'
        ";

        $li = mysqli_query($con, $req_list);

        if (!$li) {
            echo "Query failed: " . mysqli_error($con);
        } else {
            $counter = 0;
            while ($list = mysqli_fetch_assoc($li)) {
                $viewId = "viewdoc" . $list['boo_id'];
                $formId = "form_" . $list['boo_id'];

                echo "<div class='req' data-booking-id='{$list['boo_id']}'>";

                echo "<strong>Vehicle:</strong> " . htmlspecialchars($list['veh_name']) . "<br>";
                echo "<strong>Model:</strong> " . htmlspecialchars($list['veh_model']) . "<br>";
                echo "<strong>Customer:</strong> " . htmlspecialchars($list['cus_name']) . "<br>";
                echo "<strong>Date:</strong> " . htmlspecialchars($list['date']) . "<br>";

                echo "<img src='" . htmlspecialchars($list['veh_img']) . "' width='150' style='border-radius:10px; margin: 10px 0;'><br>";

                // View Document Button
                echo "<label>Verify Document: ";
                echo "<button type='button' class='view-btn' onclick=\"toggleDoc('$viewId')\">VIEW</button>";
                echo "</label><br>";

                // Hidden Image Div
                echo "<div id='$viewId' style='display:none; margin:10px 0;'>
                        <img src='" . htmlspecialchars($list['cus_doc']) . "' width='200' style='border-radius:10px; border: 2px solid white;'>
                      </div>";

                // Action buttons form
                echo "<form id='$formId' onsubmit=\"handleAction(event, {$list['boo_id']})\" style='margin-top:15px;'>";
                echo "<button type='submit' name='action' value='accept' class='accept-btn'>Accept</button>";
                echo "<button type='submit' name='action' value='reject' class='reject-btn'>Reject</button>";
                echo "</form>";

                echo "</div>";
                $counter++;
            }
            
            if ($counter === 0) {
                echo "<div style='color: white; text-align: center; width: 100%;'>";
                echo "<h2>No pending requests found</h2>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <nav>
        <i class="fa-regular fa-user"></i>
        <?php echo $_SESSION['username']; ?>
    </nav>

    <script>
        const upload = document.getElementById('uv');
        const options = document.querySelector('.options');
        const vehicleform = document.querySelector('.upload-vehicle-form');
        const submit = document.getElementById('submit');
        const myvehicle = document.getElementById('mv');
        const myvehiclelist = document.querySelector('.my-vehicles');
        const back3 = document.querySelectorAll('.back3');
        const back1 = document.getElementById('back1');
        const viewrequest = document.querySelector('.request');
        const reqbtn = document.getElementById('req');

        upload.addEventListener('click', function() {
            options.style.display = 'none';
            vehicleform.style.display = 'flex';
        });

        submit.addEventListener('click', function() {
            options.style.display = 'flex';
            vehicleform.style.display = 'none';
        });

        myvehicle.addEventListener('click', function() {
            options.style.display = 'none';
            myvehiclelist.style.display = 'flex';
        });

        back1.addEventListener('click', function() {
            options.style.display = 'flex';
            vehicleform.style.display = 'none';
        });

        reqbtn.addEventListener('click', function() {
            viewrequest.style.display = 'flex';
            options.style.display = 'none';
        });

        back3.forEach(function(button) {
            button.addEventListener('click', function() {
                options.style.display = 'flex';
                myvehiclelist.style.display = 'none';
                viewrequest.style.display = 'none';
            });
        });

        // Toggle document visibility
        function toggleDoc(id) {
            const target = document.getElementById(id);
            const isVisible = target && target.style.display === "block";

            // Hide all open documents first
            document.querySelectorAll("[id^='viewdoc']").forEach(el => el.style.display = "none");

            // If it wasn't visible before, show it
            if (!isVisible && target) {
                target.style.display = "block";
            }
        }

        // Enhanced handleAction function
        function handleAction(e, boo_id) {
            e.preventDefault();

            const action = e.submitter.value;
            const form = e.target;
            const buttons = form.querySelectorAll('button');
            const requestCard = e.target.closest(".req");

            console.log("Processing action:", action, "for booking ID:", boo_id);

            // Disable buttons during processing
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = '0.5';
            });

            // Show processing message
            const originalText = e.submitter.textContent;
            e.submitter.textContent = 'Processing...';

            fetch("book_vehicle.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "boo_id=" + encodeURIComponent(boo_id) + "&action=" + encodeURIComponent(action)
            })
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.text();
            })
            .then(data => {
                console.log("Server response:", data);

                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        // Success - remove the request card with animation
                        requestCard.style.transition = 'all 0.5s ease-out';
                        requestCard.style.transform = 'scale(0.8)';
                        requestCard.style.opacity = '0';
                        
                        setTimeout(() => {
                            requestCard.remove();
                        }, 500);

                        // Show success message
                        showMessage("Request " + action + "ed successfully!", "success");
                    } else {
                        throw new Error(jsonData.message || "Unknown error occurred");
                    }
                } catch (parseError) {
                    console.error("JSON Parse Error:", parseError);
                    
                    // Check for success indicators in plain text response
                    if (data.includes('"success":true') || data.includes('Booking updated')) {
                        requestCard.style.transition = 'all 0.5s ease-out';
                        requestCard.style.transform = 'scale(0.8)';
                        requestCard.style.opacity = '0';
                        
                        setTimeout(() => {
                            requestCard.remove();
                        }, 500);
                        
                        showMessage("Request " + action + "ed successfully!", "success");
                    } else {
                        throw new Error("Server returned unexpected response: " + data);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage("Error processing request: " + error.message, "error");
            })
            .finally(() => {
                // Re-enable buttons
                buttons.forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });
                e.submitter.textContent = originalText;
            });
        }

        // Helper function to show messages
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.textContent = message;
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: bold;
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                ${type === 'success' ? 'background-color: #4CAF50;' : 'background-color: #f44336;'}
            `;

            document.body.appendChild(messageDiv);

            // Remove message after 3 seconds
            setTimeout(() => {
                messageDiv.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    document.body.removeChild(messageDiv);
                }, 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

</body>
</html>