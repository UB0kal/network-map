<?php 
    session_start();

    $DB_HOST = "localhost";
    $DB_NAME = "networkmap";
    $DB_USER = "root";
    $DB_PASS = "";

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $input_email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $input_password = isset($_POST['password']) ? $_POST['password'] : '';
        $input_confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (empty($input_username)) {
            $errors['username'] = "Username is required.";
        } elseif (strlen($input_username) < 3) {
            $errors['username'] = "Username must be at least 3 characters long.";
        } elseif (strlen($input_username) > 100) {
            $errors['username'] = "Username cannot be more than 100 characters long.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $input_username)) {
            $errors['username'] = "Username can only contain letters, numbers, and underscores.";
        }

        if (empty($input_email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        } elseif (strlen($input_email) > 255) {
            $errors['email'] = "Email cannot be more than 255 characters long.";
        }

        if (empty($input_password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($input_password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }
        if (empty($input_confirm_password)) {
            $errors['confirm_password'] = "Please confirm your password.";
        } elseif ($input_password !== $input_confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        if (empty($errors)) {
            $conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

            $escaped_username_check = mysqli_real_escape_string($conn, $input_username);
            $sql_check_username = "SELECT id FROM users WHERE username = '$escaped_username_check'";
            $result_check_username = mysqli_query($conn, $sql_check_username);

            if ($result_check_username) {
                if (mysqli_num_rows($result_check_username) > 0) {
                    $errors['username'] = "This username is already taken.";
                }
                mysqli_free_result($result_check_username);
            } else {
                $errors['db_query'] = "Error checking username: " . mysqli_error($conn);
            }

            if (!isset($errors['username']) && !isset($errors['db_query'])) {
                $escaped_email_check = mysqli_real_escape_string($conn, $input_email);
                $sql_check_email = "SELECT id FROM users WHERE email = '$escaped_email_check'";
                $result_check_email = mysqli_query($conn, $sql_check_email);

                if ($result_check_email) {
                    if (mysqli_num_rows($result_check_email) > 0) {
                        $errors['email'] = "This email address is already registered.";
                    }
                    mysqli_free_result($result_check_email);
                } else {
                    $errors['db_query'] = "Error checking email: " . mysqli_error($conn);
                }
            }
        }

        if (empty($errors)) {
            session_destroy();
            session_start();
            $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

            $escaped_username_insert = mysqli_real_escape_string($conn, $input_username);
            $escaped_email_insert = mysqli_real_escape_string($conn, $input_email);

            $sql_insert_user = "INSERT INTO users (username, email, password_hash) VALUES ('$escaped_username_insert', '$escaped_email_insert', '$hashed_password')";

            if (mysqli_query($conn, $sql_insert_user)) {
                $_SESSION['registration_success'] = "Registration successful! You can now log in.";
                mysqli_close($conn);
                header("Location: login.php"); // Redirect to login page
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl w-3/4 shadow-md scale-120">
        <h1 class="text-xl mx-auto justify-center items-center flex w-full">Register</h1>
        <form action="" method='POST'>
            <div class="flex mt-3">
                <div class="mr-4">
                    <label class="text-lg" for="username">email</label>
                    <br>
                    <input class="border-solid  w-full border-2 rounded-lg p-1 mt-2" id="email" type="text" name="email" placeholder="email">
                    <h3 class="text-xs text-red-400  pl-1 justify-start flex w-full"><?php $test = isset($errors['email']) ? trim($errors['email']) : ''; echo("$test") ?></h3>
                </div>
                <div>
                    <label class="text-lg" for="username">username</label>
                    <br>
                    <input class="border-solid  w-full border-2 rounded-lg p-1 mt-2" id="username" type="text" name="username" placeholder="username">
                    <h3 class="text-xs text-red-400  pl-1 justify-start flex w-full"><?php $test = isset($errors['username']) ? trim($errors['username']) : ''; echo("$test") ?></h3>

                </div>
            </div>
            <label class="text-lg" for="password">password</label>
            <br>
            <input class="border-solid w-full border-2 rounded-lg p-1 mt-2" id="password" type="password" name="password" placeholder="password">
            <h3 class="text-xs text-red-400  pl-1 pb-1 justify-start flex w-full"><?php $test = isset($errors['password']) ? trim($errors['password']) : ''; echo("$test") ?></h3>
            <label class="text-lg" for="confirm_password">repeat password</label>
            <br>
            <input class="border-solid w-full border-2 rounded-lg p-1 mt-2" id="confirm_password" type="password" name="confirm_password" placeholder="confirm password">
            <h3 class="text-xs text-red-400  pl-1 pb-1 justify-start flex w-full"><?php $test = isset($errors['confirm_password']) ? trim($errors['confirm_password']) : ''; echo("$test") ?></h3>
            <br>
            <input class="border-2 mt-3 p-2 px-4 border-blue-500 bg-blue-500 hover:scale-105 rounded-xl" type="submit" value="Register">
            <a class="text-xs text-green-400 pl-1 justify-start flex w-full" href="./login.php">login here</a>
        </form>
    </div>
</body>
</html>