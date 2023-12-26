<?php

class AppointmentsRepository {
    private $appointments = [];

    public function addAppointment(Appointment $appointment) {
        $this->appointments[] = $appointment;
    }

    public function getAppointments() {
        return $this->appointments;
    }

    public function setAppointments(array $appointments) {
        $this->appointments = $appointments;
    }
}