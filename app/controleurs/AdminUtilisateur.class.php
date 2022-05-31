<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class AdminUtilisateur extends Admin {

  private $methodes = [
    'l-m'         => 'listerMises',
    'l-u'         => 'listerUtilisateurs',
    'a'           => 'ajouterUtilisateur',
    'm'           => 'modifierUtilisateur',
    's'           => 'supprimerUtilisateur',
    'd'           => 'deconnecter',
    'generer_mdp' => 'genererMdp'
  ];

  /**
   * Gérer l'interface d'administration des utilisateurs 
   */  
  public function gerer($entite = "utilisateur") {

    $this->entite  = $entite;
    $this->action  = $_GET['action'] ?? 'l-u';
    $this->utilisateur_id = $_GET['utilisateur_id'] ?? null;
    $this->id_enchere = $_GET['id_enchere'] ?? null;

    if (isset($this->methodes[$this->action])) {
      $methode = $this->methodes[$this->action];
      $this->$methode();
    } else {
      throw new Exception("L'action $this->action de l'entité $this->entite n'existe pas.");
    }
  }

  /**
   * Connecter un utilisateur
   */
  public function connecter() {
    $messageErreurConnexion = ""; 
    if (count($_POST) !== 0) {
      $u = self::$oRequetesSQL->connecter($_POST);
      if ($u !== false) {
        $_SESSION['u'] = new Utilisateur($u);
        parent::gerer();
        exit;         
      } else {
        $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
      }
    }

    $utilisateur = [];
    
    (new Vue)->generer('vAdminUtilisateurConnecter',
            array(
              'titre'                  => 'Connexion',
              'utilisateur'            => $utilisateur,
              'messageErreurConnexion' => $messageErreurConnexion
            ),
            'gabarit-admin-min');
  }

  /**
   * Déconnecter un utilisateur
   */
  public function deconnecter() {
    unset ($_SESSION['u']);
    parent::gerer();
  }

  /**
   * Lister les mises
   */
  public function listerMises() {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime(); // utilisation directe de la constante MOCK_NOW
    $aujourdhui_mock  = $oAujourdhui->format('Y-m-d');
    $mises = self::$oRequetesSQL->getMisesPerso(self::$u->utilisateur_id);
    (new Vue)->generer('vAdminUtilisateurMises',
            array(
              'u'                   => self::$u,
              'titre'               => 'Vos mises',
              'encheres'            => $mises,
              'classRetour'         => $this->classRetour,  
              'messageRetourAction' => $this->messageRetourAction,
              'aujourdhui_mock'     => $aujourdhui_mock 
            ),
            'gabarit-admin');
  }

  /**
   * Lister les utilisateurs
   */

  public function listerUtilisateurs() {
    if (self::$u->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR) throw new Exception(self::ERROR_FORBIDDEN);

    $utilisateurs = self::$oRequetesSQL->getUtilisateurs();

    (new Vue)->generer('vAdminUtilisateurs',
            array(
              'u'                   => self::$u,
              'titre'               => 'Votre profil',
              'utilisateurs'        => $utilisateurs,
              'classRetour'         => $this->classRetour,  
              'messageRetourAction' => $this->messageRetourAction
            ),
            'gabarit-admin');
  }


  /**
   * Ajouter un utilisateur
   */
  public function ajouterUtilisateur() {
    if (self::$u->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR) throw new Exception(self::ERROR_FORBIDDEN);
    
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
        $oUtilisateur->genererMdp();
        $retour = self::$oRequetesSQL->ajouterUtilisateur([
          'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_mdp'      => $oUtilisateur->utilisateur_mdp,
          'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
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
          $this->listerUtilisateurs();
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
              'u'           => self::$u,
              'titre'       => 'Ajouter un utilisateur',
              'utilisateur' => $utilisateur,
              'erreurs'     => $erreurs
            ),
            'gabarit-admin');
  }

  /**
   * Modifier un utilisateur
   */
  public function modifierUtilisateur() {
    if (self::$u->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR) throw new Exception(self::ERROR_FORBIDDEN);

    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur non renseigné pour une modification");
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
        $retour = self::$oRequetesSQL->modifierUtilisateur([
          'utilisateur_id'       => $oUtilisateur->utilisateur_id, 
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
          'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
        ]);
        if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
          if ($retour === true)  {
            $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";    
          } else {  
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
          }
          $this->listerUtilisateurs();
          exit;
        } else {
          $erreurs['utilisateur_courriel'] = $retour;
        }
      }
    } else {
      $utilisateur = self::$oRequetesSQL->getUtilisateur($this->utilisateur_id);
      $erreurs = [];
    }
    
    (new Vue)->generer('vAdminUtilisateurModifier',
            array(
              'u'           => self::$u,
              'titre'       => "Modifier l'utilisateur numéro $this->utilisateur_id",
              'utilisateur' => $utilisateur,
              'erreurs'     => $erreurs
            ),
            'gabarit-admin');
  }
  
  /**
   * Supprimer un utilisateur
   */
  public function supprimerUtilisateur() {
    if (self::$u->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR) throw new Exception(self::ERROR_FORBIDDEN);

    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur incorrect pour une suppression.");
    $retour = self::$oRequetesSQL->supprimerUtilisateur($this->utilisateur_id);
    if ($retour === false) $this->classRetour = "erreur";
    $this->messageRetourAction = "Suppression de l'utilisateur numéro $this->utilisateur_id ".($retour ? "" : "non ")."effectuée.";
    $this->listerUtilisateurs();
  }

  /**
   * Générer un nouveau mot de passe
   */
  public function genererMdp() {
    if (self::$u->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR) throw new Exception(self::ERROR_FORBIDDEN);

    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur incorrect pour une modification du mot de passe.");

    $utilisateur = self::$oRequetesSQL->getUtilisateur($this->utilisateur_id);
    $oUtilisateur = new Utilisateur($utilisateur);
    $mdp = $oUtilisateur->genererMdp();
    $retour = self::$oRequetesSQL->modifierUtilisateurMdp([
        'utilisateur_id'  => $this->utilisateur_id, 
        'utilisateur_mdp' => $mdp
    ]);
    if ($retour === true)  {
      $this->messageRetourAction = "Modification du mot de passe de l'utilisateur numéro $this->utilisateur_id effectuée.";
      $oGestionCourriel = ENV === "DEV" ? new app\mocks\GestionCourriel : new GestionCourriel;
      $this->messageRetourAction .= $oGestionCourriel->envoyerMdp($oUtilisateur) ?  " Courriel envoyé à l'utilisateur." : " Erreur d'envoi d'un courriel à l'utilisateur.";
    } else {  
      $this->classRetour = "erreur";
      $this->messageRetourAction = "Modification du mot de passe de l'utilisateur numéro $this->utilisateur_id non effectuée.";
    }
    $this->listerUtilisateurs();
  }
}