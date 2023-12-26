<?php
session_start();

require_once('classes/User.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User('admin', password_hash('password', PASSWORD_DEFAULT));

    if ($user->getUsername() === $username && $user->verifyPassword($password)) {
        // Authentication successful
        $_SESSION['user'] = $username;
        $_SESSION['loggedIn'] = true; // Set the loggedIn session variable
        header("Location: index.php");
        exit;
    } else {
        // Authentication failed
        $_SESSION['loggedIn'] = false; // Set the loggedIn session variable
        echo "Invalid username or password. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
    background-color: #f0f0f0;
    font-family: 'Arial', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    position: relative;
}

#background {
    position: fixed;
    width: 100%; /* 100% * number of images */
    height: 100%;
  

}


h1 {
    color: #001f3f; /* Dark blue color */
    text-align: center;
    position: absolute;
    top: 30px; /* Adjust the top position as needed */
    left: 50%;
    transform: translateX(-50%);

}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    z-index: 1;
    position: relative;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

button {
    width: 100%;
    background-color: #009688;
    color: #fff;
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #00796b;
}

    </style>
</head>
<body><br></br>
    <div id="background">
        <img src="image1.jpg" alt="Background Image 1">
   
    </div><br></br>
    <h1>Tooth Care Hospital</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>

</html>

