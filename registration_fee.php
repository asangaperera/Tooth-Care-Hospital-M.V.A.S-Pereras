<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/RegistrationFee.php');

// Check if the user is logged in
$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

// If the user is not logged in, redirect to the login form with an error message
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

// Get the appointment ID from the URL parameter
$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

// If no appointment ID is provided, redirect to the appointments page
if (!$appointmentId) {
    header("Location: appointments.php");
    exit;
}

// Create an instance of the Appointment class
$appointmentObj = new Appointment('', '', '', '', '', '', '');

// Fetch the appointment details for editing
$appointmentDetails = $appointmentObj->getAppointmentDetails($appointmentId);
// var_dump($appointmentDetails);

// If the form is submitted for updating the appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrationFee'])) {
    // Get the payment reference number from the form
    $paymentRef = $_POST['paymentRef'];

    // Create an instance of RegistrationFee
    $registrationFee = new RegistrationFee(
        $appointmentDetails['id'],
        $appointmentDetails['name'],
        $appointmentDetails['telephone'],
        $appointmentDetails['address'], 
        $appointmentDetails['date'],
        $appointmentDetails['time'],
        2,
        $paymentRef
    );

    // Book the registration fee
    $registrationFee->makeRegistrationFee();

    // Redirect back to the appointments page after updating
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Fee | #<?= $appointmentDetails['id'] ?></title>
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

        form {
            width: 40%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            padding: 10px 20px;
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

<body>
    <h1>Registration Fee | #<?= $appointmentDetails['id'] ?></h1>
    </br></br></br></br>

    <?php
    // var_dump($_SESSION['registrationFees']);
    ?>

    <form method="post">
        <label for="paymentRef">Payment Ref No:</label>
        <input type="text" id="paymentRef" value="<?= $appointmentDetails['id'] ?>" name="paymentRef" required>
        <br>

        <button type="submit" name="registrationFee">Submit</button>
    </form>
</body>

</html>
