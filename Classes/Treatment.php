<?php

class Treatment {
    private $appointmentId;
    private $treatmentType;
    private $fee;
    private $paymentId;

    public function __construct($appointmentId, $treatmentType, $fee, $paymentId) {
        $this->appointmentId = $appointmentId;
        $this->treatmentType = $treatmentType;
        $this->fee = $fee;
        $this->paymentId = $paymentId;
    }

    public function getAppointmentId() {
        return $this->appointmentId;
    }

    public function getTreatmentType() {
        return $this->treatmentType;
    }

    public function getFee() {
        return $this->fee;
    }

    public function getPaymentId() {
        return $this->paymentId;
    }

    public static function getTreatmentFee($treatmentType) {
        if ($treatmentType == 'cleaning') {
            return 1450; // Adjust the fee for cleaning as needed
        } elseif ($treatmentType == 'whitening') {
            return 2555; // Adjust the fee for whitening as needed
        } elseif ($treatmentType == 'filling') {
            return 1860; // Adjust the fee for filling as needed
        }
        elseif ($treatmentType == 'Nerve Filling') {
            return 2565; // Adjust the fee for filling as needed
        }
        elseif ($treatmentType == 'Root Canal Therapy') {
            return 2700; // Adjust the fee for filling as needed
        } else {
            throw new Exception("Unknown treatment type: $treatmentType");
        }
    }
}

?>