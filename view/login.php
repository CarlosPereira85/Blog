<?php
include_once 'controller/Login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login_register.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post">
            <fieldset>
                <legend>Login</legend>
                <input type="text" name="login" placeholder="Username or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </fieldset>
        </form>
    </div>
</body>
</html>