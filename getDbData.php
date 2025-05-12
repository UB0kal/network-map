<?php 

    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";

    function getNetworks(){
        $conn = mysqli_connect($GLOBALS['DB_HOST'],$GLOBALS['DB_USER'],
                                $GLOBALS['DB_PASS'],$GLOBALS['DB_NAME']);
        $userId_for_string = (int)$_SESSION['user_id'];
        $sql = "SELECT * FROM networks where user_id = ". $userId_for_string;

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $user_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    return $user_data;
    }
?>