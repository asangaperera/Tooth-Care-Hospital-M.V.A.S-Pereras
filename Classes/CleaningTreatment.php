<?php
// class CleaningTreatment implements Treatment {
//     public function calculateTotalFee(): float {
//         $fixedFee = 400.0;
//         return $fixedFee;
//     }
// }

class CleaningTreatmentFactory implements TreatmentFactory {
    public function createTreatment(): Treatment {
        return new CleaningTreatment();
    }
}
?>