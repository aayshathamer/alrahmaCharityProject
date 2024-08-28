<?php
// Start the session and check if the user is logged in
session_start();
if(!isset($_SESSION['user_id'])){
    header('location:user_login.php');
    exit;
}

// Get donation details from the session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$total_amounts = isset($_SESSION['total_amounts']) ? $_SESSION['total_amounts'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Donation</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            padding: 30px;
        }
        h1 {
            color: #1e90ff;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .icon {
            font-size: 50px;
            color: #1e90ff;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 25px;
            font-size: 18px;
            color: #ffffff;
            background-color: #1e90ff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #187bcd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🎉</div>
        <h1>Thank You for Your Donation!</h1>
        <p>Your generosity is greatly appreciated. You have donated <strong>$<?php echo number_format($total_amounts, 2); ?></strong>.</p>
        <p>Your support helps us continue our mission and make a difference. Your donation is making an impact on the world and improving someone's life significantly.</p>
        <a href="home.php" class="button">Go to Home Page</a>
    </div>
</body>
</html>
