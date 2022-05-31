<?php

/**
 * Classe de l'entité Enchère
 *
 */
class Enchere extends Entite
{
  protected  $enchere_id;
  protected  $enchere_date_fin;
  protected  $enchere_date_debut;
  protected  $enchere_prix_plancher;
  protected  $enchere_coup_de_coeur_du_lord;
  protected  $erreurs = array();

  // Getters explicites nécessaires au moteur de templates TWIG
  public function getEnchere_id()  { return $this->enchere_id; }
  public function getEnchere_date_fin() { return $this->enchere_date_fin; }
  public function getEnchere_date_debut() { return $this->enchere_date_debut; }
  public function getEnchere_prix_plancher() { return $this->enchere_prix_plancher; }
  public function getEnchere_coup_de_coeur_du_lord() { return $this->enchere_coup_de_coeur_du_lord; }
  public function getErreurs()   { return $this->erreurs; }

  /**
   * Mutateur de la propriété enchere_id 
   * @param int $enchere_id
   * @return $this
   */    
  public function setEnchere_id($enchere_id) {
    unset($this->erreurs['enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $enchere_id)) {
      $this->erreurs['enchere_id'] = "Numéro d'enchère incorrect.";
    }
    $this->enchere_id = $enchere_id;
    return $this;
  }    

  /**
   * Mutateur de la propriété enchere_date_debut
   * @param string $enchere_date_debut
   * @return $this
   */    
  public function setEnchere_date_debut($enchere_date_debut) {
    unset($this->erreurs['enchere_date_debut']);
    $enchere_date_debut = trim($enchere_date_debut);
    $this->enchere_date_debut = $enchere_date_debut;
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $enchere_date_debut)) {
      $t = explode('-', $enchere_date_debut);
      if (count($t) === 3 && checkdate($t[1], $t[2], $t[0])) {
        return $this;
      }
    }
    $this->erreurs['enchere_date_debut'] = 'Date invalide.';
    return $this;
  }

  /**
   * Mutateur de la propriété enchere_date_fin
   * @param string $enchere_date_fin
   * @return $this
   */    
  public function setEnchere_date_fin($enchere_date_fin) {
    unset($this->erreurs['enchere_date_fin']);
    $enchere_date_fin = trim($enchere_date_fin);
    $this->enchere_date_fin = $enchere_date_fin;
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $enchere_date_fin)) {
      $t = explode('-', $enchere_date_fin);
      if (count($t) === 3 && checkdate($t[1], $t[2], $t[0])) {
        return $this;
      }
    }
    $this->erreurs['enchere_date_fin'] = 'Date invalide.';
    return $this;
  }

  /**
   * Mutateur de la propriété enchere_prix_plancher 
   * @param int $enchere_prix_plancher
   * @return $this
   */        
  public function setEnchere_prix_plancher($enchere_prix_plancher) {
    unset($this->erreurs['enchere_prix_plancher']);
    if (!preg_match('/^[1-9]\d*$/', $enchere_prix_plancher)) {
      $this->erreurs['enchere_prix_plancher'] = "Entrez un prix valide";
    }
    $this->enchere_prix_plancher = $enchere_prix_plancher;
    return $this;
  }

  /**
   * Mutateur de la propriété enchere_coup_de_coeur_du_lord
   * @param string $$enchere_coup_de_coeur_du_lord
   * @return $this
   */    
  public function setEnchere_coup_de_coeur_du_lord($enchere_coup_de_coeur_du_lord) {
    unset($this->erreurs['enchere_coup_de_coeur_du_lord']);
    $enchere_coup_de_coeur_du_lord = trim($enchere_coup_de_coeur_du_lord);
    $this->enchere_coup_de_coeur_du_lord = $enchere_coup_de_coeur_du_lord;
    return $this;
  }  

  protected  $timbre_id;
  protected  $timbre_nom;
  protected  $timbre_date_de_creation;
  protected  $timbre_couleurs;
  protected  $timbre_pays_origine;
  protected  $timbre_tirage;
  protected  $timbre_dimensions;
  protected  $timbre_etat;
  protected  $timbre_certification;
  protected  $timbre_image;
  protected  $timbre_image_titre;

  // Getters explicites nécessaires au moteur de templates TWIG
  public function getTimbre_id()  { return $this->timbre_id; }
  public function getTimbre_nom() { return $this->timbre_nom; }
  public function getTimbre_date_de_creation() { return $this->timbre_date_de_creation; }
  public function getTimbre_couleurs() { return $this->timbre_couleurs; }
  public function getTimbre_pays_origine() { return $this->timbre_pays_origine; }
  public function getTimbre_tirage() { return $this->timbre_tirage; }
  public function getTimbre_dimensions() { return $this->timbre_dimensions; }
  public function getTimbre_condition() { return $this->timbre_etat; }
  public function getTimbre_certifié() { return $this->timbre_certification; }
  public function getTimbre_image() { return $this->timbre_image; }
  public function getTimbre_image_titre() { return $this->timbre_image_titre; }

  /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */    
  public function setTimbre_id($timbre_id) {
    unset($this->erreurs['timbre_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_id)) {
      $this->erreurs['timbre_id'] = "Numéro de timbre incorrect.";
    }
    $this->timbre_id = $timbre_id;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_nom
   * @param string $timbre_nom
   * @return $this
   */    
  public function setTimbre_nom($timbre_nom) {
      unset($this->erreurs['timbre_nom']);
      $timbre_nom = trim($timbre_nom);
      $regExp = '/^\S+(\s+\S+){0,}$/';
      if (!preg_match($regExp, $timbre_nom)) {
      $this->erreurs['timbre_nom'] = 'Au moins deux caractères';
      }
      $this->timbre_nom = $timbre_nom;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_date_de_creation
   * @param int $timbre_date_de_creation
   * @return $this
   */   
  public function setTimbre_date_de_creation($timbre_date_de_creation) {
      unset($this->erreurs['timbre_date_de_creation']);
      if (!preg_match('/^\d+$/', $timbre_date_de_creation) || $timbre_date_de_creation > date("Y")) {
      $this->erreurs['timbre_date_de_creation'] = "Entrez une date de création valide";
      }
      $this->timbre_date_de_creation = $timbre_date_de_creation;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_couleurs
   * @param string $timbre_couleurs
   * @return $this
   */
  public function setTimbre_couleurs($timbre_couleurs) {
      unset($this->erreurs['timbre_couleurs']);
      $timbre_couleurs = trim($timbre_couleurs);
      $regExp = '/^\S+(\s+\S+){0,}$/';
      if (!preg_match($regExp, $timbre_couleurs)) {
      $this->erreurs['timbre_couleurs'] = 'Au moins une couleur';
      }
      $this->timbre_couleurs = $timbre_couleurs;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_pays_origine
   * @param string $timbre_pays_origine
   * @return $this
   */    
  public function setTimbre_pays_origine($timbre_pays_origine) {
      unset($this->erreurs['timbre_pays_origine']);
      $timbre_pays_origine = trim($timbre_pays_origine);
      $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
      if (!preg_match($regExp, $timbre_pays_origine)) {
      $this->erreurs['timbre_pays_origine'] = 'Au moins deux caractères';
      }
      $this->timbre_pays_origine = $timbre_pays_origine;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_tirage
   * @param int $timbre_tirage
   * @return $this
   */    
  public function setTimbre_tirage($timbre_tirage) {
      unset($this->erreurs['timbre_tirage']);
      $regExp = '/^\S+(\s+\S+){0,}$/';
      if (!preg_match($regExp, $timbre_tirage)) {
      $this->erreurs['timbre_tirage'] = "Entrez un nombre de tirages valide";
      }
      $this->timbre_tirage = $timbre_tirage;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_dimensions
   * @param string $timbre_dimensions
   * @return $this
   */
  public function setTimbre_dimensions($timbre_dimensions) {
      unset($this->erreurs['timbre_dimensions']);
      $timbre_dimensions = trim($timbre_dimensions);
      $regExp = '/^\S+(\s+\S+){0,}$/';
      if (!preg_match($regExp, $timbre_dimensions)) {
      $this->erreurs['timbre_dimensions'] = 'Entrez une dimension valide';
      }
      $this->timbre_dimensions = $timbre_dimensions;
      return $this;
  }

  /**
   * Mutateur de la propriété timbre_condition
   * @param string $timbre_condition
   * @return $this
   */    
  public function setTimbre_etat($timbre_etat) {
      $timbre_etat = trim($timbre_etat);
      $this->timbre_etat = $timbre_etat;
      return $this;
  } 
  
  /**
   * Mutateur de la propriété timbre_certifié
   * @param string $timbre_certifié
   * @return $this
   */    
  public function setTimbre_certification($timbre_certification) {
      $timbre_certification = trim($timbre_certification);
      $this->timbre_certification = $timbre_certification;
      return $this;
  } 

  /**
   * Mutateur de la propriété timbre_image_titre
   * @param string $timbre_image_titre
   * @return $this
   */    
  public function setTimbre_image_titre($timbre_image_titre) {
      unset($this->erreurs['timbre_image_titre']);
      $timbre_image_titre = trim($timbre_image_titre);
      $regExp = '/^\S+(\s+\S+){0,}$/';
      if (!preg_match($regExp, $timbre_image_titre)) {
      $this->erreurs['timbre_image_titre'] = 'Au moins deux caractères';
      }
      $this->timbre_image_titre = $timbre_image_titre;
      return $this;
  }
  
}