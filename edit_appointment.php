<?php
session_start();

require_once('classes/Appointment.php');

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateAppointment'])) {
    $newPatientName = $_POST['patientName'];
    $newAddress = $_POST['address'];
    $newTelephone = $_POST['telephone'];
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];
    $newStatus = $appointmentDetails['status'];

    // Update the appointment using the non-static method
    $appointmentObj->updateAppointment($appointmentId, $newPatientName, $newAddress, $newTelephone, $newDate, $newTime, $newStatus);

    // Redirect back to the appointments page after updating
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Toothcare Hospital</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #009688;
        }

        form {
            width: 400px;
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #009688;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #00796b;
        }
    </style>
</head>
<body>
    <h1>Update Appointment | # <?=$appointmentDetails['id']?></h1>
    <form method="post" id="appointmentForm">
        <label for="patientName">Patient Name:</label>
        <input type="text" id="patientName" name="patientName" value="<?=$appointmentDetails['name']?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?=$appointmentDetails['address']?>" required>

        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" value="<?=$appointmentDetails['telephone']?>" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?=$appointmentDetails['date']?>" required>

        <label for="time">Preferred Time Slot:</label>
        <select id="time" name="time" required>
            <!-- Options will be dynamically populated based on the selected day -->
        </select>

        <button type="submit" name="updateAppointment">Update Appointment</button>
    </form>
</body>
</html>

    <script>
        // Function to initialize and populate time slots
        function initializeTimeSlots() {
        var datepicker = document.getElementById('date');
        var timeSelect = document.getElementById('time');
        var selectedDate = new Date(datepicker.value);
        var day = selectedDate.getDay(); // 0 is Sunday, 1 is Monday, etc.

        // Retrieve existing appointments for the selected date
        var existingAppointments = <?= json_encode($_SESSION['appointments'] ?? []) ?>;

        // Extract booked time slots for the selected date
        var bookedTimeSlots = existingAppointments
            .filter(function (appointment) {
                return new Date(appointment.date).toDateString() === selectedDate.toDateString();
            })
            .map(function (appointment) {
                return appointment.time;
            });

        // Define time slots based on the selected day
        var timeSlots = [];
        if (day === 1 || day === 3) {
            // Monday or Wednesday
            timeSlots = ['06:00 pm - 07:00 pm', '07:00 pm - 08:00 pm', '08:00 pm - 09:00 pm'];
        } else if (day === 6 || day === 0) {
            // Saturday or Sunday
            timeSlots = ['03:00 pm - 04:00 pm', '04:00 pm - 05:00 pm', '05:00 pm - 06:00 pm', '06:00 pm - 07:00 pm', '07:00 pm - 08:00 pm', '08:00 pm - 09:00 pm', '09:00 pm - 10:00 pm'];
        }

        // Exclude booked time slots from the available options
        var availableTimeSlots = timeSlots.filter(function (slot) {
            return !bookedTimeSlots.includes(slot);
        });

        // Clear existing options
        timeSelect.innerHTML = '';

        // Add a placeholder option
        var placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.text = 'Select a time slot';
        timeSelect.add(placeholderOption);

        // Include the preselected time slot even if it's booked
        var preselectedOption = document.createElement('option');
        preselectedOption.value = '<?= $appointmentDetails['time'] ?>';
        preselectedOption.text = '<?= $appointmentDetails['time'] ?>';
        preselectedOption.selected = true;
        timeSelect.add(preselectedOption);

        // Populate other available time slots in the dropdown
        availableTimeSlots.forEach(function (slot) {
            var option = document.createElement('option');
            option.value = slot;
            option.text = slot;

            // Exclude the preselected time slot from the options
            if (slot !== '<?= $appointmentDetails['time'] ?>') {
                timeSelect.add(option);
            }
        });
    }

    // Function to validate the selected date
    function validateDate() {
        var selectedDate = document.getElementById('date').value;
        var selectedDay = new Date(selectedDate).getDay();

        // Array of allowed days (Monday is 1, Sunday is 0)
        var allowedDays = [1, 3, 6, 0];

        if (!allowedDays.includes(selectedDay)) {
            alert('Please select a valid date (Monday, Wednesday, Saturday, or Sunday).');
            return false;
        }

        return true;
    }

    // Attach the validation function to the form submission
    document.getElementById('appointmentForm').addEventListener('submit', function (event) {
        if (!validateDate()) {
            event.preventDefault();
        }
    });

        // Attach the initialize function to the page load event
        window.addEventListener('load', function () {
            initializeTimeSlots();
        });

        document.getElementById('date').addEventListener('input', function () {
            initializeTimeSlots(); // Re-initialize time slots on date change
        });
    </script>




</body>
</html>