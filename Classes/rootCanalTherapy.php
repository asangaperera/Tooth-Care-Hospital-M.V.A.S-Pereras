<?php 
// class rootCanalTherapy {
//     public function calculateTotalFee(): float {
//         $fixedFee = 1560.0;
//         return $fixedFee;
//     }
// }

class rootCanalTherapyFactory implements TreatmentFactory {
    public function createTreatment(): Treatment {
        return new rootCanalTherapy();
    }
}

?>
