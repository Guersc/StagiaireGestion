<?php
class Utilisateur {
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $mot_de_passe;
    protected $role; // (étudiant, tuteur, administrateur)

    public function __construct($nom, $prenom, $email, $mot_de_passe, $role) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    // Méthodes d'accès et de modification
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    // Méthodes pour l'authentification
    public function verifierMotDePasse($mot_de_passe) {
        return password_verify($mot_de_passe, $this->mot_de_passe);
    }
}
?>