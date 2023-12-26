<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/Treatment.php');

$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Process the form submission
    $paymentId = $_POST['paymentId'];

    // Retrieve the treatment from the session
    $treatments = $_SESSION['treatment'];

    foreach ($treatments as &$treatment) {
        if ($treatment['appointmentId'] === $appointmentId) {
            // Update the appointment details
            $treatment['paymentId'] = $paymentId;
            break;
        }
    }

    $_SESSION['treatment'] = $treatments;

    $selectedTreatment = null;
    foreach ($treatments as $treatment) {
        if ($treatment['appointmentId'] === $appointmentId) {
            $selectedTreatment = $treatment;
            break;
        }
    }

    if ($selectedTreatment) {
        // Update the payment ID in the treatment
        $selectedTreatment['paymentId'] = $paymentId;

        // Redirect to the invoice page
        header("Location: invoice.php?id=" . $appointmentId);
        exit;
    } else {
        // Handle error: Treatment not found for the specified appointment ID
    }

} else {
    // Retrieve the treatment from the session
    $treatments = $_SESSION['treatment'];

    // Find the treatment for the specified appointment ID
    $selectedTreatment = null;
    foreach ($treatments as $treatment) {
        if ($treatment['appointmentId'] === $appointmentId) {
            $selectedTreatment = $treatment;
            break;
        }
    }

    // Retrieve appointment details using the Appointment class
    $appointmentObj = new Appointment('', '', '', '', '', '', '');
    $appointmentDetails = $appointmentObj->getAppointmentDetails($appointmentId);

    // Add appointment details to the selected treatment
    $selectedTreatment['patientName'] = $appointmentDetails['name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Fee</title>
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

        table {
            width: 70%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #009688;
            color: #fff;
        }

        form {
            width: 50%;
            margin-top: 20px;
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
    font-size: 18px;
    border: none;
    border-radius: 20px; /* Corrected property name */
    cursor: pointer;
        }

        button:hover {
            background-color: #00796b;
        }
    </style>
</head>

<body>
    <h1>Dental Treatment Payment</h1>
    <?php if ($selectedTreatment) : ?>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Appointment ID</th>
                <th>Treatment Type</th>
                <th>Treatment Fee</th>
            </tr>
            <tr>
                <td><?= $selectedTreatment['patientName'] ?></td>
                <td><?= $selectedTreatment['appointmentId'] ?></td>
                <td><?= $selectedTreatment['treatmentType'] ?></td>
                <td><?= $selectedTreatment['fee'] ?></td>
            </tr>
        </table>

        <form method="post">
            <label for="paymentId">Enter Payment ID:</label>
            <input type="text" id="paymentId" name="paymentId" required><br>

            <button type="submit" name="submit">Submit Payment</button>
        </form>
    <?php else : ?>
        <p>No treatment found for the specified appointment ID.</p>
    <?php endif; ?>
</body>

</html>
