<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once('classes/Appointment.php');
require_once('classes/User.php');
require_once('classes/Treatment.php');
require_once('classes/TreatmentFactory.php');
require_once('classes/CleaningTreatment.php');
require_once('classes/AppointmentsRepository.php');

// Check if the appointments array is stored in the session, if not, create a new one
$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

// Check if the user is logged in
$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

// If the user is not logged in, redirect to the login form with an error message
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

// Create an instance of the Appointment class
$appointmentObj = new Appointment('', '', '', '', '', '', '');

// If the user is logged in and the form is submitted for booking an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookAppointment'])) {
    $patientName = $_POST['patientName'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = 1;

    // Book the appointment using the static method
    // $appointment = Appointment::bookAppointment($patientName, $date);

    // Book the appointment using the non-static method
    $appointment = $appointmentObj->bookAppointment($patientName, $address, $telephone, $date, $time, $status);
    // unset($appointmentObj);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filterByDate'])) {
    // Get the date filter
    $filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : date('Y-m-d');

    // Store the date filter in the session
    $_SESSION['appointment_filter_date'] = $filterDate;

    // Retrieve appointments from the session (replace this with your actual session structure)
    $appointmentList = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

    // Filter appointments based on the date
    $appointments = array_filter($appointmentList, function ($appointment) use ($filterDate) {
        return isset($appointment['date']) && date('Y-m-d', strtotime($appointment['date'])) === $filterDate;
    });
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchById'])) {
    // Get the appointment ID from the form
    $searchId = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';

    // Filter appointments based on the appointment ID
    $appointments = array_filter($appointments, function ($appointment) use ($searchId) {
        return isset($appointment['id']) && $appointment['id'] == $searchId;
    });
}else{
    // Get the list of appointments
    $appointments = $appointmentObj->getAppointmentsFromSession();
}

unset($appointmentObj);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toothcare Hospital</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            font-family: 'Arial', sans-serif;
            overflow: hidden; /* Hide overflow to prevent scrollbars */
            color: #333;
        }

        h1 {
            color: #009688;
        }

        #background {
            position: fixed;
            width: 300%; /* 100% * number of images */
            height: 100%;
            z-index: -1; /* Place the background behind other elements */
            animation: slide 30s linear infinite; /* Adjust the duration as needed */
        }

        @keyframes slide {
            0%, 100% {
                transform: translateX(0%);
            }
            33.33% {
                transform: translateX(-100%);
            }
            66.66% {
                transform: translateX(-200%);
            }
        }

        form {
            width: 40%;
            background-color: rgba(255, 255, 255, 0.8); /* Add transparency to form background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Create a stacking context for the form */
            z-index: 1; /* Place the form above the background */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, select {
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
    <div id="background">
        <img src="image1.jpg" alt="Background Image 1">
        <img src="image2.jpg" alt="Background Image 2">
        <img src="image3.jpg" alt="Background Image 3">
    </div>
    <h1>Toothcare Hospital - Book an Appointment</h1>
    <form method="post" id="appointmentForm">
    <form method="post" id="appointmentForm">
        <label for="patientName">Patient Name:</label>
        <input type="text" id="patientName" name="patientName" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Preferred Time Slot:</label>
        <select id="time" name="time" placeholder="Select a time slot" required>
            <!-- Options will be dynamically populated based on the selected day -->
        </select>

        <button type="submit" name="bookAppointment">Book Appointment</button>
    </form>
    </form>
</body>
</html>
    <script>
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

        document.getElementById('date').addEventListener('input', function () {
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

            // Populate time slots in the dropdown
            availableTimeSlots.forEach(function (slot) {
                var option = document.createElement('option');
                option.value = slot;
                option.text = slot;
                timeSelect.add(option);
            });
        });
    </script>

    <h2>Appointments</h2>

    <!-- Form to search by appointment ID -->
    <form method="POST" action="">
        <label for="appointment_id">Search by Appointment ID:</label>
        <input type="text" id="appointment_id" name="appointment_id" required>
        <button type="submit" name="searchById">Search</button>
    </form>

    <!-- Form to input date for filtering -->
    <form method="POST" action="">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" id="filter_date" name="filter_date" value="" required>
        <button type="submit" name="filterByDate">Filter</button>
    </form>

    <?php if (!empty($appointments)) : ?>

        <!-- Display appointments in a table -->
        <table border='1'>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Telephone</th>
                <th>Status</th>
                <th>Appointment Date</th>
                <th></th>
            </tr>
            <?php foreach ($appointments as $appointment) : ?>
                <tr>
                    <?php
                        // var_dump($appointment);
                    ?>
                    <td><?= $appointment['id'] ?></td>
                    <td><?= $appointment['name'] ?></td>
                    <td><?= $appointment['telephone'] ?></td>
                    <td><?= ($appointment['status'] == 1) ? 'Unpaid' : 'Paid' ?></td>
                    <td><?= $appointment['date'].', '.$appointment['time'] ?></td>
                    <td>
                        <a href="edit_appointment.php?id=<?= $appointment['id'] ?>">Edit</a>
                        <a href="registration_fee.php?id=<?= $appointment['id'] ?>">Make Payment</a>
                        <a href="treatment.php?id=<?= $appointment['id'] ?>">Treatment</a>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No appointments available.</p>
    <?php endif; ?>

    <!-- Comment out the logout link while testing -->
    <a href="logout.php">Logout</a>
</body>
</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once('classes/Appointment.php');
require_once('classes/User.php');
require_once('classes/Treatment.php');
require_once('classes/TreatmentFactory.php');
require_once('classes/CleaningTreatment.php');
require_once('classes/AppointmentsRepository.php');

// Check if the appointments array is stored in the session, if not, create a new one
$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

// Check if the user is logged in
$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

// If the user is not logged in, redirect to the login form with an error message
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

// Create an instance of the Appointment class
$appointmentObj = new Appointment('', '', '', '', '', '', '');

// If the user is logged in and the form is submitted for booking an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookAppointment'])) {
    $patientName = $_POST['patientName'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = 1;

    // Book the appointment using the static method
    // $appointment = Appointment::bookAppointment($patientName, $date);

    // Book the appointment using the non-static method
    $appointment = $appointmentObj->bookAppointment($patientName, $address, $telephone, $date, $time, $status);
    // unset($appointmentObj);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filterByDate'])) {
    // Get the date filter
    $filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : date('Y-m-d');

    // Store the date filter in the session
    $_SESSION['appointment_filter_date'] = $filterDate;

    // Retrieve appointments from the session (replace this with your actual session structure)
    $appointmentList = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

    // Filter appointments based on the date
    $appointments = array_filter($appointmentList, function ($appointment) use ($filterDate) {
        return isset($appointment['date']) && date('Y-m-d', strtotime($appointment['date'])) === $filterDate;
    });
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchById'])) {
    // Get the appointment ID from the form
    $searchId = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';

    // Filter appointments based on the appointment ID
    $appointments = array_filter($appointments, function ($appointment) use ($searchId) {
        return isset($appointment['id']) && $appointment['id'] == $searchId;
    });
}else{
    // Get the list of appointments
    $appointments = $appointmentObj->getAppointmentsFromSession();
}

unset($appointmentObj);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toothcare Hospital</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            color: #333;
        }

        h1, h2 {
            color: #009688;
        }

        #background {
            position: fixed;
            width: 300%;
            height: 100%;
            z-index: -1;
            animation: slide 30s linear infinite;
        }

        @keyframes slide {
            0%, 100% {
                transform: translateX(0%);
            }
            33.33% {
                transform: translateX(-100%);
            }
            66.66% {
                transform: translateX(-200%);
            }
        }

        form {
            width: 40%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
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

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 20px;
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

        td a {
            margin-right: 10px;
            text-decoration: none;
            color: #009688;
        }

        p {
            margin-top: 20px;
            font-size: 18px;
        }

        a {
            text-decoration: none;
            color: #009688;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="background">
        <img src="image1.jpg" alt="Background Image 1">
        <img src="image2.jpg" alt="Background Image 2">
        <img src="image3.jpg" alt="Background Image 3">
    </div>
    <h1>Toothcare Hospital - Book an Appointment</h1>
    <form method="post" id="appointmentForm">
        <!-- Your form elements go here -->
    </form>

    <h2>Appointments</h2>

    <!-- Your search forms go here -->

    <?php if (!empty($appointments)) : ?>
        <!-- Display appointments in a table -->
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Telephone</th>
                <th>Status</th>
                <th>Appointment Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($appointments as $appointment) : ?>
                <tr>
                    <td><?= $appointment['id'] ?></td>
                    <td><?= $appointment['name'] ?></td>
                    <td><?= $appointment['telephone'] ?></td>
                    <td><?= ($appointment['status'] == 1) ? 'Unpaid' : 'Paid' ?></td>
                    <td><?= $appointment['date'] . ', ' . $appointment['time'] ?></td>
                    <td>
                        <a href="edit_appointment.php?id=<?= $appointment['id'] ?>">Edit</a>
                        <a href="registration_fee.php?id=<?= $appointment['id'] ?>">Make Payment</a>
                        <a href="treatment.php?id=<?= $appointment['id'] ?>">Treatment</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No appointments available. <a href="logout.php">Logout</a></p>
    <?php endif; ?>
</body>
</html>
