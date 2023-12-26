<?php 
class WhiteningTreatment {
    public function calculateTotalFee(): float {
        $fixedFee = 1400.0;
        return $fixedFee;
    }
}

class WhiteningTreatmentFactory implements TreatmentFactory {
    public function createTreatment(): Treatment {
        return new WhiteningTreatment();
    }
}

?>