<?php 
    session_start();


    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";
   
    $user_id = $_SESSION['user_id'];
    
    $message = '';
    $message_type = '';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['NetworkName'])) {
        $network_name = trim($_POST['NetworkName']);
    
        if (empty($network_name)) {
            $message = "Network name cannot be empty.";
            $message_type = 'error';
        } elseif (strlen($network_name) > 100) {
            $message = "Network name is too long (maximum 100 characters).";
            $message_type = 'error';
        } else {
           
            $conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

            $escaped_network_name = mysqli_real_escape_string($conn, $network_name);

            $sql_check = "SELECT id FROM networks WHERE user_id = $user_id AND name = '$escaped_network_name'";
            $result_check = mysqli_query($conn, $sql_check);

            if ($result_check) {
                if (mysqli_num_rows($result_check) > 0) {
                    $message = "You already have a network with this name. Please choose a different name.";
                    $message_type = 'error';
                } else {

                    $sql_insert = "INSERT INTO networks (user_id, name) VALUES ($user_id, '$escaped_network_name')";

                    if (mysqli_query($conn, $sql_insert)) {
                        if (mysqli_affected_rows($conn) > 0) {
                            $message = "Network '<strong>" . htmlspecialchars($network_name) . "</strong>' created successfully!";
                            $message_type = 'success';
                        } 
                    }
            }
            mysqli_close($conn);
            }
        }
        $_SESSION['create_network_message'] = $message;
        header("Location: ./main.php");
    }
    else {
        header("Location: ./main.php");
    }
?>