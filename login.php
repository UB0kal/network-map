<?php
    session_start();

    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";




    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_destroy();
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password']; 
        
        $conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

        $escaped_username = mysqli_real_escape_string($conn, $username);
        $sql = "SELECT id, username, email, password_hash FROM users WHERE username = '$escaped_username'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $user_data = mysqli_fetch_assoc($result);
                if (password_verify($password, $user_data['password_hash'])) {
                    session_regenerate_id(true);
    
                    $_SESSION["logged_in"] = true;
                    $_SESSION["user_id"] = $user_data['id'];
                    $_SESSION["username"] = $user_data['username'];
    
                    header("Location: main.php");
                    exit;
                } else {
                    $_SESSION['login_error'] = "Invalid username or password.";
                    header("Location: login.php");
                    exit;
                }
            } else {
                $_SESSION['login_error'] = "Invalid username or password.";
                header("Location: login.php");
                exit;
            }
            mysqli_free_result($result);
        } else {
            $_SESSION['login_error'] = "An error occurred during login. Please try again.";
            header("Location: login.php");
            exit;
        }
        header("Location: main.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl w-1/3 shadow-md scale-120">
        <h1 class="text-xl mx-auto justify-center items-center flex w-full">Login</h1>
        <form action="" method='POST'>
            <h1 class="flex text-red-500 mx-auto justify-center"><?php $test = isset($_SESSION['login_error']) ? trim($_SESSION['login_error']) : ''; echo("$test") ?>
            </h1>
            <h1 class="flex text-green-500 mx-auto justify-center text-center"><?php $test = isset($_SESSION['registration_success']) ? trim($_SESSION['registration_success']) : ''; echo("$test") ?></h1>
            <br>
            <input class="border-solid  w-full border-2 rounded-lg p-1 my-2" id="username" type="text" name="username" placeholder="username">
            <br>
            <label class="text-lg" for="password">password</label>
            <br>
            <input class="border-solid w-full border-2 rounded-lg p-1 mt-2" id="password" type="password" name="password" placeholder="password">
            <br>
            <a class="text-xs text-blue-400 justify-end flex w-full" href="/">forgot password?</a>
            <input class="border-2 mt-3 p-2 px-4 border-blue-500 bg-blue-500 hover:scale-105 rounded-xl" type="submit" value="Login">
            <a class="text-xs text-green-400 pl-1 justify-start flex w-full" href="./register.php">register here</a>
        </form>
    </div>
</body>
</html>