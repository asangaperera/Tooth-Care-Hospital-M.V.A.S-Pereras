<?php

class Patient {
    private $patientName;
    private $address;
    private $telephone;

    public function __construct($patientName, $address, $telephone) {
        $this->patientName = $patientName;
        $this->address = $address;
        $this->telephone = $telephone;
    }

    public function getPatientName() {
        return $this->patientName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getTelephone() {
        return $this->telephone;
    }
}

class Appointment extends Patient {
    private $appointmentId;
    private $date;
    private $time;
    private $status;

    public function __construct($appointmentId, $patientName, $address, $telephone, $date, $time, $status) {

        parent::__construct(
            $patientName,
            $address,
            $telephone
        );
// <!--ecplulation-->
        $this->appointmentId = $appointmentId;
        $this->date = $date;
        $this->time = $time;
        $this->status = $status;
    }

    public function getAppointmentId() {
        return $this->appointmentId;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }
// abtraction
    public function getStatus() {
        return $this->status;
    }

    public function bookAppointment($patientName, $address, $telephone, $date, $time, $status) {
        $appointmentId = uniqid('app_');

        // Create a new Appointment instance
        $appointment = new self($appointmentId, $patientName, $address, $telephone, $date, $time, $status);

        // Get the existing appointments from the session
        $appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

        // Append the new appointment to the existing list
        $appointments[] = [
            'id' => $appointment->getAppointmentId(),
            'name' => $appointment->getPatientName(),
            'address' => $appointment->getAddress(),
            'telephone' => $appointment->getTelephone(),
            'date' => $appointment->getDate(),
            'time' => $appointment->getTime(),
            'status' => $status,
        ];

        // Save the updated list back to the session
        $_SESSION['appointments'] = $appointments;

        // return $appointment;
    }

    public function getAppointmentsFromSession() {
        // Get the existing appointments from the session
        return isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];
    }

    public function getAppointmentDetails($appointmentId) {
        // Get the existing appointments from the session
        $appointments = $this->getAppointmentsFromSession();
    
        // Find the appointment with the given ID
        foreach ($appointments as $appointment) {
            if ($appointment['id'] === $appointmentId) {
                return $appointment;
            }
        }
    
        // Return null if appointment not found
        return null;
    }

    public function updateAppointment($appointmentId, $newPatientName, $newAddress, $newTelephone, $newDate, $newTime, $newStatus) {
        // Get the existing appointments from the session
        $appointments = $this->getAppointmentsFromSession();
    
        // Find the appointment with the given ID
        foreach ($appointments as &$appointment) {
            if ($appointment['id'] === $appointmentId) {
                // Update the appointment details
                $appointment['name'] = $newPatientName;
                $appointment['address'] = $newAddress;
                $appointment['telephone'] = $newTelephone;
                $appointment['date'] = $newDate;
                $appointment['time'] = $newTime;
                $appointment['status'] = $newStatus;
                break;
            }
        }
    
        // Save the updated list back to the session
        $_SESSION['appointments'] = $appointments;
    }

    public function updateStatus($newStatus)
    {
        $this->status = $newStatus;

        $appointments = $this->getAppointmentsFromSession();
        foreach ($appointments as &$appointment) {
            if ($appointment['id'] === $this->getAppointmentId()) {
                $appointment['status'] = $newStatus;
                break;
            }
        }
        $_SESSION['appointments'] = $appointments;
    }
    
}

?>
