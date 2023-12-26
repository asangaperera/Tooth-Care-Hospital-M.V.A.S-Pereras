<?php

require_once 'Appointment.php'; 

class RegistrationFee extends Appointment
{
    public $paymentRefNo;

    public function __construct($id, $name, $telephone, $address, $date, $time, $status, $paymentRefNo)
    {
        parent::__construct($id, $name, $telephone, $address, $date, $time, $status);
        $this->paymentRefNo = $paymentRefNo;
    }

    public function makeRegistrationFee()
    {
        // Get the existing registration fees from the session
        $registrationFees = isset($_SESSION['registrationFees']) ? $_SESSION['registrationFees'] : [];

        // Append the new registration fee to the existing list
        $registrationFees[] = [
            'id' => $this->getAppointmentId(),
            'paymentRefNo' => $this->paymentRefNo,
        ];

        // Save the updated list back to the session
        $_SESSION['registrationFees'] = $registrationFees;
        
        $this->updateStatus(2);

        return $this;
    }

}

?>
