<?php
class Stage {
    private $id;
    private $entreprise;
    private $dateDebut;
    private $dateFin;
    private $objectifs;
    private $etudiantId;

    public function __construct($entreprise, $dateDebut, $dateFin, $objectifs, $etudiantId) {
        $this->entreprise = $entreprise;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->objectifs = $objectifs;
        $this->etudiantId = $etudiantId;
    }

    // Méthodes d'accès
    public function getId() { return $this->id; }
    public function getEntreprise() { return $this->entreprise; }
    public function getDateDebut() { return $this->dateDebut; }
    public function getDateFin() { return $this->dateFin; }
    public function getObjectifs() { return $this->objectifs; }
    public function getEtudiantId() { return $this->etudiantId; }
}
?>