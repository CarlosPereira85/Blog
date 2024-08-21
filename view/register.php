<?php
include_once 'controller/Register.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/login_register.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="post">
            <fieldset>
                <legend>Register</legend>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <label>
                    <input type="checkbox" name="is_admin"> Admin
                </label>
                <button type="submit">Register</button>
            </fieldset>
        </form>
    </div>
</body>
</html>