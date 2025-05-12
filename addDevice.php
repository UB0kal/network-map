<?php 
    session_start();

    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";
   
    $user_id = $_SESSION['user_id'];
    
    $message = '';
    $message_type = '';
    
    include "GetDbData.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $device_type = isset($_POST['type']) ? trim($_POST['type']) : header("Location: ./main.php") . end();
        $device_name = isset($_POST['name']) ? trim($_POST['name']) : header("Location: ./main.php") . end();
        $device_ip = isset($_POST['ipAddress']) ? trim($_POST['ipAddress']) : header("Location: ./main.php") . end();
        $device_description = isset($_POST['description']) ? trim($_POST['description'])  : header("Location: ./main.php") . end();
    
        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

        $escaped_device_type = mysqli_real_escape_string($conn, $device_type);
        $escaped_device_name = mysqli_real_escape_string($conn, $device_name);
        $escaped_device_ip = mysqli_real_escape_string($conn, $device_ip);
        $escaped_device_description = mysqli_real_escape_string($conn, $device_description);

        $sql_network_id = $_SESSION['selected_network'];
    
        $sql_insert = "INSERT INTO devices (user_id, network_id, name, type, ip_address, description)
                      VALUES ($user_id, $sql_network_id, '$escaped_device_name', '$escaped_device_type', '$escaped_device_ip', '$escaped_device_description')";
        if (mysqli_query($conn, $sql_insert)) {
            if (mysqli_affected_rows($conn) > 0) {
                $message = "Device '<strong>" . htmlspecialchars($display_name) . "</strong>' created successfully!";
                $message_type = 'success';
            }
            
            mysqli_close($conn);
        } 
    }
    getDevices();
    header("Location: ./main.php");

?>