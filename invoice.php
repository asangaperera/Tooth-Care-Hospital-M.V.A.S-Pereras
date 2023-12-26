<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/Treatment.php');

$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

$treatments = $_SESSION['treatment'];

    // Find the treatment for the specified appointment ID
    $treatment = null;
    foreach ($treatments as $x) {
        if ($x['appointmentId'] === $appointmentId) {
            $treatment = $x;
            break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        h1 {
            color: #009688;
        }

        .invoice-container {
            width: 40%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .invoice-container p {
            margin-bottom: 15px;
        }
        .back-to-home-button {
            display: inline-block;
            padding: 10px 30px;
            background-color: #009688;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .back-to-home-button:hover {
            background-color: #00796b;
        }
    </style>
</head>

<body>
    <h1>Invoice</h1>
    <div class="invoice-container">
        <p><strong>Appointment ID:</strong> <?= $treatment['appointmentId'] ?></p>
        <p><strong>Treatment Type:</strong> <?= $treatment['treatmentType'] ?></p>
        <p><strong>Treatment Fee:</strong> Rs.<?= $treatment['fee'] ?></p>
        <p><strong>Payment ID:</strong> <?= $treatment['paymentId'] ?></p>
    </div>
    <p><a href="index.php" class="back-to-home-button">Back to Home</a></p>
</body>

</html>
