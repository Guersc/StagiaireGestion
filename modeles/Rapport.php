<?php
class Rapport {
    private $id;
    private $etudiantId;
    private $contenu;
    private $dateSoumission;

    public function __construct($etudiantId, $contenu, $dateSoumission) {
        $this->etudiantId = $etudiantId;
        $this->contenu = $contenu;
        $this->dateSoumission = $dateSoumission;
    }

    // Méthodes d'accès
    public function getId() { return $this->id; }
    public function getEtudiantId() { return $this->etudiantId; }
    public function getContenu() { return $this->contenu; }
    public function getDateSoumission() { return $this->dateSoumission; }
    
    // Méthodes supplémentaires
    public function soumettre() {
        // Logique pour soumettre le rapport 
    }

    public function consulter() {
        // Logique pour consulter le rapport
    }
}
?>