<?php
require_once 'Utilisateur.php';

class Tuteur extends Utilisateur {
    private $entreprise;

    public function __construct($nom, $prenom, $email, $mot_de_passe, $entreprise) {
        parent::__construct($nom, $prenom, $email, $mot_de_passe, 'tuteur');
        $this->entreprise = $entreprise;
    }

    // Méthodes d'accès
    public function getEntreprise() { return $this->entreprise; }
}
?>