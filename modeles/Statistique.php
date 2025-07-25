<?php
class Statistique {
    private $etudiantId;
    private $evaluations = [];

    public function __construct($etudiantId) {
        $this->etudiantId = $etudiantId;
    }

    public function ajouterEvaluation($evaluation) {
        $this->evaluations[] = $evaluation;
    }

    public function calculerMoyenne() {
        $total = 0;
        foreach ($this->evaluations as $evaluation) {
            $total += $evaluation->getScore();
        }
        return count($this->evaluations) > 0 ? $total / count($this->evaluations) : 0;
    }
}
?>