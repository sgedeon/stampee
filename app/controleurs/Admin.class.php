<?php

/**
 * Classe Contrôleur des requêtes de l'application admin
 */

class Admin extends Routeur {

  protected $entite;
  protected $action;

  protected $utilisateur_id;

  protected static $u;            // objet Utilisateur connecté
  protected static $oRequetesSQL;

  protected $classRetour = "fait";
  protected $messageRetourAction = "";

  /**
   * Gérer l'interface d'administration 
   */  
  public function gerer() {
    self::$oRequetesSQL = new RequetesSQL;
    if (isset($_SESSION['u'])) {
      self::$u = $_SESSION['u'];
      $entite = $_GET['entite']  ?? 'Utilisateur';
      $entite = ucwords($entite);
      $classe = "Admin$entite";
      if (class_exists($classe)) {
        (new $classe())->gerer();
      } else {
        throw new Exception("L'entité $entite n'existe pas.");
      }
    } else {
      (new AdminUtilisateur)->connecter();
    }    
  }
}