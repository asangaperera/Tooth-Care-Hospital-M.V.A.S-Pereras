<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/Treatment.php');

// Check if the user is logged in
$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

// If the user is not logged in, redirect to the login form with an error message
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

// Get the appointment ID from the URL parameter
$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;
// Create an instance of the Appointment class
$appointmentObj = new Appointment('', '', '', '', '', '', '');

// Fetch the appointment details from the session
$appointmentDetails = $appointmentObj->getAppointmentDetails($appointmentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Process the form submission
    $appointmentId = $_POST['appointmentId'];
    $treatmentType = $_POST['treatmentType'];

    $treatmentStrategy = null;

    switch ($treatmentType) {
        case 'cleaning':
            $treatmentStrategy = new CleaningTreatment();
            break;
        case 'whitening':
            $treatmentStrategy = new WhiteningTreatment();
            break;
        case 'filling':
            $treatmentStrategy = new FillingTreatment();
            break;
        case 'Nerve Filling':
            $treatmentStrategy = new NerveFillingTreatment();
            break;
        case 'Root Canal Therapy':
            $treatmentStrategy = new RootCanalTherapyTreatment();
            break;
        default:
            throw new Exception("Unknown treatment type: $treatmentType");
    }

    $treatment = new Treatment($treatmentStrategy);

    // Create a Treatment instance and store it in the session
    // $treatment = new Treatment($appointmentId, $treatmentType, Treatment::getTreatmentFee($treatmentType), null);
    
    $treatments = isset($_SESSION['treatment']) ? $_SESSION['treatment'] : [];
    // $treatments[] = $treatment;
    $treatments[] = [
        'appointmentId' => $appointmentId,
        'treatmentType' => $treatmentType,
        'fee' => $treatment->getTreatmentFee(),
        // 'fee' => Treatment::getTreatmentFee($treatmentType),
        'paymentId' => null,
    ];
    $_SESSION['treatment'] = $treatments;

    // Redirect to the treatment fee page
    header("Location: treatment_fee.php?id=" . $appointmentId);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Treatment Selection</title>
    <!-- Add your styles and scripts here -->
</head>
<body>
    <h1>Treatment Selection</h1>
    <?php var_dump($_SESSION['treatment']); ?>
    <form method="post">
        <label>Patient Name: <?= $appointmentDetails['name'] ?></label><br>
        <label>Appointment ID: <?= $appointmentDetails['id'] ?></label><br>

        <label for="treatmentType">Select Treatment Type:</label>
        <select id="treatmentType" name="treatmentType" required>
            <option value="cleaning">Cleaning</option>
            <option value="whitening">Whitening</option>
            <option value="filling">filling</option>
            <option value="Nerve Filling">Nerve Filling</option>
            <option value="Root Canal Therapy">Root Canal Therapy</option>
            <!-- Add more options as needed -->
        </select><br>

        <!-- <label for="fee">Treatment Fee:</label>
        <input type="text" id="fee" name="fee" value="" readonly><br> -->

        <input type="hidden" name="appointmentId" value="<?= $appointmentDetails['id'] ?>">

        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
