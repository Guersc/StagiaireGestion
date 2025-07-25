<?php
class Evaluation {
    private $id;
    private $tuteurId;
    private $etudiantId;
    private $score;
    private $commentaires;

    public function __construct($tuteurId, $etudiantId, $score, $commentaires) {
        $this->tuteurId = $tuteurId;
        $this->etudiantId = $etudiantId;
        $this->score = $score;
        $this->commentaires = $commentaires;
    }

    // Méthodes d'accès
    public function getId() { return $this->id; }
    public function getTuteurId() { return $this->tuteurId; }
    public function getEtudiantId() { return $this->etudiantId; }
    public function getScore() { return $this->score; }
    public function getCommentaires() { return $this->commentaires; }
}
?>