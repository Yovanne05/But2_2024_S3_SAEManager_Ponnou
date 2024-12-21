<?php
include_once "modules/mod_professeur/modele_professeur.php";
include_once  "modules/mod_professeur/vue_professeur.php";

Class ContProfesseur {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleProfesseur();
        $this->vue = new VueProfesseur();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";

        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
            case "creerSAE":
                $this->creerSAE();
                break;
            default:
                $this->accueil();
                break;
        }
    }

    public function accueil() {
        $saeGerer = $this->modele->saeGerer($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeGerer);
    }

    public function creerSAE() {
        $this->vue->creerUneSAE();
        if (
            isset($_POST['titre']) && !empty(trim($_POST['titre'])) &&
            isset($_POST['annee']) && !empty(trim($_POST['annee'])) &&
            isset($_POST['semestre']) && !empty(trim($_POST['semestre'])) &&
            isset($_POST['description']) && !empty(trim($_POST['description']))
        ) {
            $titre = trim($_POST['titre']);
            $annee = trim($_POST['annee']);
            $semestre = trim($_POST['semestre']);
            $description = trim($_POST['description']);
            $this->modele->ajouterProjet($titre, $annee, $semestre, $description);
        }
    }
}