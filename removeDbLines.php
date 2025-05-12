<?php 
    session_start();

    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";

    include "GetDbData.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['removeNetwork']) and isset($_SESSION['selected_network'])){
        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $network_id = $_SESSION['selected_network'];
        $sql = "DELETE FROM networks WHERE id = " . $network_id;
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        $_SESSION["selected_network"] = null;

        getDevices();
        getConnections();
        header("Location: ./main.php");
    }
    elseif ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['removeNode']) and isset($_SESSION['selected_network'])){
        $data = $_COOKIE['selectedNode'];
        $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $sql = "DELETE FROM devices WHERE id = " . $data;
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        $_SESSION["selectedNode"] = null;

        getDevices();
        getConnections();
        header("Location: ./main.php");
    }
    header("Location: ./main.php");
?>