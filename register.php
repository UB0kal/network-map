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
                    <input class="border-solid  w-full border-2 rounded-lg p-1 my-2" id="email" type="text" name="email" placeholder="email">
                </div>
                <div>
                    <label class="text-lg" for="username">username</label>
                    <br>
                    <input class="border-solid  w-full border-2 rounded-lg p-1 my-2" id="username" type="text" name="username" placeholder="username">
                </div>
            </div>
            <label class="text-lg" for="password">password</label>
            <br>
            <input class="border-solid w-full border-2 rounded-lg p-1 mt-2 mb-3" id="password" type="password" name="password" placeholder="password">
            <br>
            <label class="text-lg" for="rpassword">repeat password</label>
            <br>
            <input class="border-solid w-full border-2 rounded-lg p-1 mt-2" id="rpassword" type="rpassword" name="rpassword" placeholder="repeat your password">
            <br>
            <a class="text-xs text-blue-400 justify-end flex w-full" href="/">forgot password?</a>
            <input class="border-2 mt-3 p-2 px-4 border-blue-500 bg-blue-500 hover:scale-105 rounded-xl" type="submit" value="Register">
            <a class="text-xs text-green-400 pl-1 justify-start flex w-full" href="./login.php">login here</a>
        </form>
    </div>
</body>
</html>