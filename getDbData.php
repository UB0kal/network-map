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

    function getDevices(){
        setcookie('devices', "");
        $conn = mysqli_connect($GLOBALS['DB_HOST'],$GLOBALS['DB_USER'],
                                $GLOBALS['DB_PASS'],$GLOBALS['DB_NAME']);
        $userId_for_string = (int)$_SESSION['user_id'];
        $networkId_for_string = (int)$_SESSION['selected_network'];

        $sql = "SELECT * FROM devices WHERE user_id = " 
                . $userId_for_string . " AND network_id =" . $networkId_for_string ;

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $devices = [];

            while ($row = mysqli_fetch_assoc($result)) {
                 $devices[] = $row;
             }
        }
        $devices = json_encode($devices);
        setcookie('devices', $devices);
    }

    function getConnections(){
        setcookie('connections', "");
        $conn = mysqli_connect($GLOBALS['DB_HOST'],$GLOBALS['DB_USER'],
                                $GLOBALS['DB_PASS'],$GLOBALS['DB_NAME']);
        $userId_for_string = (int)$_SESSION['user_id'];
        $networkId_for_string = (int)$_SESSION['selected_network'];

        $sql = "SELECT * FROM connections WHERE user_id = " 
                . $userId_for_string . " AND network_id =" . $networkId_for_string ;

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $connections = [];

            while ($row = mysqli_fetch_assoc($result)) {
                 $connections[] = $row;
             }
        }
        $connections = json_encode($connections);
        setcookie('connections', $connections);
    }
?>