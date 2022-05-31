<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur {

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->timbre_id = $_GET['timbre_id'] ?? null; 
    $this->id_enchere = $_GET['id_enchere'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * page d'accueil
   * 
   */  
  public function accueil() {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime(); // utilisation directe de la constante MOCK_NOW
    $aujourdhui_mock  = $oAujourdhui->format('Y-m-d');
    $encheres = $this->oRequetesSQL->getEncheres();
    if (isset($_SESSION['u'])) {
      $u = $_SESSION['u'];
    } else $u = "";
    (new Vue)->generer("vAccueil",
            array(
              'titre' => "Accueil",
              'encheres' => $encheres,
              'aujourdhui_mock'=> $aujourdhui_mock,
              'u' => $u
            ),
            "gabarit-frontend");
  }

  /**
   * page catalogue 
   *  
   */  
  public function catalogue() {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime(); // utilisation directe de la constante MOCK_NOW
    $aujourdhui_mock  = $oAujourdhui->format('Y-m-d');
    $this->messageRetourAction = "";
    $encheres = $this->oRequetesSQL->getEncheres();
    if (isset($_SESSION['u'])) {
      $u = $_SESSION['u'];
    } else $u = "";
    if (count($_POST)) {
      $filtres = $_POST;
      if ($filtres['pays'] == "") {
        $encheres = $this->oRequetesSQL->getEncheresFiltres([
          'prix_min'             => $filtres['min'],
          'prix_max'             => $filtres['max'],
          'date_de_creation'     => $filtres['année'],
          'etat'                 => $filtres['condition']
        ]);
        if (!$encheres)$this->messageRetourAction = "Aucune enchère ne correspond à votre recherche.";
      } else {
        $encheres = $this->oRequetesSQL->getEncheresFiltres([
          'prix_min'             => $filtres['min'],
          'prix_max'             => $filtres['max'],
          'date_de_creation'     => $filtres['année'],
          'pays_origine'         => $filtres['pays'],
          'etat'                 => $filtres['condition']
        ]);
        if (!$encheres)$this->messageRetourAction = "Aucune enchère ne correspond à votre recherche.";
      }
    } else $encheres = $this->oRequetesSQL->getEncheres();

    (new Vue)->generer("vCatalogue",
            array(
              'titre' => "Catalogue",
              'encheres' => $encheres,
              'aujourdhui_mock'=> $aujourdhui_mock,
              'messageRetourAction' => $this->messageRetourAction,
              'u' => $u
            ),
            "gabarit-frontend-catalogue");
  }

  /**
   * Fiche d'enchère 
   * 
   */  
  public function fiche() {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime(); // utilisation directe de la constante MOCK_NOW
    $aujourdhui_mock  = $oAujourdhui->format('Y-m-d');
    $this->classRetour = "";         
    $this->messageRetourAction = "Entrez le montant de votre mise";
    $enchere = $this->oRequetesSQL->getEnchereModif($this->id_enchere);
    $utilisateur = $this->oRequetesSQL->getUtilisateurEnchere($this->id_enchere);
    $images = $this->oRequetesSQL->getImages($enchere['id_timbre']);
    $miseActuelle = $this->oRequetesSQL->getMise($this->id_enchere);
    if ($miseActuelle) {
      $miseActuelle = ($miseActuelle['mise']);
    } else $miseActuelle = 0;
    $this->nouvelleMise = $miseActuelle;;
    $encheres = $this->oRequetesSQL->getEncheres();
    if (isset($_SESSION['u'])) {
      $u = $_SESSION['u'];
    } else $u = "";
    if (count($_POST) !== 0 && isset($_SESSION['u'])) {
      $mise = $_POST;
      $oMise = new Mise ($mise);
      $erreurs = $oMise->erreurs;
      $miseNew = $oMise->mise_direct;
      if (count($erreurs) === 0) {
        if($miseActuelle + 9 < $miseNew && $miseNew >= $enchere['prix_plancher']){
          $retour = $this->oRequetesSQL->ajouterMise([
            'id_enchere_mise'      => $this->id_enchere,
            'utilisateur_id_mise'  => $u->utilisateur_id,
            'mise'                 => $oMise->mise_direct
        ]);} elseif ($miseActuelle == 0 && $miseNew >= $enchere['prix_plancher']) {
          $retour = $this->oRequetesSQL->premiereMise([
            'id_enchere_mise'      => $this->id_enchere,
            'utilisateur_id_mise'  => $u->utilisateur_id,
            'mise'                 => $oMise->mise_direct
        ]);} else {
          $retour = false; 
        }
        if ($retour === true || preg_match('/^[1-9]\d*$/', $retour)) {
          $utilisateur = $this->oRequetesSQL->getUtilisateurEnchere($this->id_enchere);
          $this->classRetour = "fait"; 
          $this->nouvelleMise = $oMise->mise_direct;
          $this->messageRetourAction = "Votre mise de $oMise->mise_direct$ a été effectuée."; 
        } else {
          $this->classRetour = "erreur";         
          $this->messageRetourAction = "Votre mise doit être supérieure d'au moins 10$ à la mise actuelle et au moins égale au prix plancher.";
        }
      } 
    } elseif(count($_POST) !== 0 && !isset($_SESSION['u'])) {
      $this->classRetour = "erreur";         
      $this->messageRetourAction = "Veuillez vous connecter ou créer un compte pour pouvoir miser.";
    }

    (new Vue)->generer("vFiche",
            array(
              'titre'               => "Fiche",
              'enchere'             => $enchere,
              'encheres'            => $encheres,
              'utilisateur'         => $utilisateur,
              'images'              => $images,
              'aujourdhui_mock'     => $aujourdhui_mock,
              'classRetour'         => $this->classRetour,  
              'messageRetourAction' => $this->messageRetourAction,
              'nouvelleMise'        => $this->nouvelleMise,
              'u'                   => $u
            ),
            "gabarit-frontend-fiche");
  }


  /**
   * Ajouter un utilisateur
   */
  public function ajouterUtilisateur() {
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
        $oUtilisateur->genererMdp();
        $retour =  $this->oRequetesSQL->ajouterUtilisateur([
          'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_mdp'      => $oUtilisateur->utilisateur_mdp,
          'utilisateur_profil'   => "utilisateur"
        ]);
        if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
          if (preg_match('/^[1-9]\d*$/', $retour)) {
            $this->messageRetourAction = "Ajout de l'utilisateur numéro $retour effectué."; 
            $oGestionCourriel = ENV === "DEV" ? new app\mocks\GestionCourriel : new GestionCourriel;
            $this->messageRetourAction .= $oGestionCourriel->envoyerMdp($oUtilisateur) ?  " Courriel envoyé à l'utilisateur." : " Erreur d'envoi d'un courriel à l'utilisateur.";
          } else {
            $this->classRetour = "erreur";         
            $this->messageRetourAction = "Ajout de l'utilisateur non effectué.";
          }
          $this->confirmationInscription();
          exit;
        } else {
          $erreurs['utilisateur_courriel'] = $retour;
        }
      }
    } else {
      $utilisateur = [];
      $erreurs     = [];
    }
    
    (new Vue)->generer('vAdminUtilisateurAjouter',
            array(
              'titre'       => 'Inscrivez-vous',
              'utilisateur' => $utilisateur,
              'erreurs'     => $erreurs
            ),
            'gabarit-admin-min');
  }

   /**
   * Confirmaton inscription
   * 
   */  
    public function confirmationInscription() {
      (new Vue)->generer("vAdminUtilisateurConfirmer",
              array(
                'titre' => "Confirmation inscription",
              ),
              "gabarit-frontend-fiche");
    }

}