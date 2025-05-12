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

    if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_SESSION['selected_network'])) {

        $device_from = isset($_POST['from']) ? trim($_POST['from']) : header("Location: ./main.php") . end();
        $device_to = isset($_POST['to']) ? trim($_POST['to']) : header("Location: ./main.php") . end();
    
        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

        $sql_network_id = $_SESSION['selected_network'];
        $escaped_device_from = mysqli_real_escape_string($conn, $device_from);
        $escaped_device_to = mysqli_real_escape_string($conn, $device_to);
    
        $sql_insert = "INSERT INTO connections (user_id, network_id, source, target)
                    VALUES ($user_id, $sql_network_id, $escaped_device_from, $escaped_device_to)";
    
        if (mysqli_query($conn, $sql_insert)) {
            if (mysqli_affected_rows($conn) > 0) {
                $message = "Connection '<strong>" . htmlspecialchars($display_name) . "</strong>' created successfully!";
                $message_type = 'success';
            }
            
            mysqli_close($conn);
        } 
    }
    getConnections();
    header("Location: ./main.php");

?>