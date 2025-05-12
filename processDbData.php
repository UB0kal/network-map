<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['networkName'] != 'null'){
        $_SESSION['selected_network'] = $_POST['networkName'];
    }
    else{
        $_SESSION['selected_network'] = null; 
    }
    header("Location: ./main.php");
}

?>