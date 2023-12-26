<?php

interface TreatmentStrategyInterface {
    public function getTreatmentFee();
}

class CleaningTreatment implements TreatmentStrategyInterface {
    public function getTreatmentFee() {
        return 1250;
    }
}

class WhiteningTreatment implements TreatmentStrategyInterface {
    public function getTreatmentFee() {
        return 1350;
    }
}

class FillingTreatment implements TreatmentStrategyInterface {
    public function getTreatmentFee() {
        return 1450;
    }
}

class NerveFillingTreatment implements TreatmentStrategyInterface {
    public function getTreatmentFee() {
        return 2200;
    }
}

class RootCanalTherapyTreatment implements TreatmentStrategyInterface {
    public function getTreatmentFee() {
        return 1320;
    }
}

?>