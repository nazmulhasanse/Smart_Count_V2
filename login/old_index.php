<?php
session_start();
if (isset($_SESSION['cardnumber'])) {
    header("Location: status.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCount-Login Page</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f9cb9c; /* Vibrant Tomato color */
    color: #fff; /* White text color */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    padding: 20px;
    width: 100%;
    max-width: 400px;
    background-color: #fff; /* White background for the form */
    color: #333; /* Dark text for the form */
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.login-container h2 {
    text-align: center;
    color: #ff6347; /* Same vibrant color for the title */
}



.login-container h1 {
    text-align: center;
    color: #ff6347; /* Same vibrant color for the title */
}

.form-group {
    margin: 5px 0;
    width: 100%;
	
}

.form-group label {
    font-weight: bold;
}

.form-group input {
    width: 90%;
    padding: 10px;
    border: none;
    border-bottom: 1px solid #ccc;
    background: transparent;
}

.form-group button {
    background-color: #ff6347; /* Same vibrant color for the button */
    color: #fff; /* White text for the button */
    padding: 20px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

.form-group button:hover {
    background-color: #e53020; /* Darker shade when hovering */
}

@media (max-width: 768px) {
    .login-container {
        width: 90%;
        margin-top: 50px;
    }
}
    </style>
</head>

<body>
    <div class="login-container">
        <h1>SmartCount</h1>
		<h2>Login</h2>
        <?php
        // Display error message if it exists
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red; text-align: center;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']); // Clear the error message
        }
        ?>
        <form class="login-form" action="login_process.php" method="POST">
            <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" placeholder="Enter your ID" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>

</html>
