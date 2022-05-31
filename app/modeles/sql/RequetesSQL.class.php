<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO {

  /**
   * Récupération des encheres
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */ 
  public function getEncheres() {
    $this->sql = "
    SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
    `pays_origine` , `tirage`, `dimensions`, `etat`,
    `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
    `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,`mise`,`id_image`, `url`, `titre`,
    `image_principale`,`id_timbre_image`
    FROM TIMBRE
    INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
    LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
    INNER JOIN IMAGES ON id_timbre = id_timbre_image 
    WHERE image_principale = 1
    ORDER BY id_enchere ASC";      
    return $this->getLignes();
  }

  /**
   * Récupération d'une enchère
   * @param int $timbre_id, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEnchere($timbre_id) {
    $this->sql = "
      SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
      `pays_origine` , `tirage`, `dimensions`, `etat`,
      `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
      `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,`mise`,`id_image`, `url`, `titre`,
      `image_principale`,`id_timbre_image`
      FROM TIMBRE
      INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
      LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
      INNER JOIN IMAGES ON id_timbre = id_timbre_image
      WHERE id_timbre = :timbre_id AND image_principale = 1";

    return $this->getLignes(['timbre_id' => $timbre_id],RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Récupération des images d'une enchères
   * @param int $timbre_id, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getImages($timbre_id) {
    $this->sql = "
      SELECT `id_image`, `url`, `titre`,
      `image_principale`,`id_timbre_image`
      FROM IMAGES
      WHERE id_timbre_image = :timbre_id";
    return $this->getLignes(['timbre_id' => $timbre_id]);
  }

  /**
   * Récupération des enchères après un filtration
   * @param int $champs tableau des champs de filtres'
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEncheresFiltres($champs) {
    if (!isset($champs['pays_origine'])) {
      $this->sql = "
      SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
      `pays_origine` , `tirage`, `dimensions`, `etat`,
      `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
      `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,
      `mise`,`id_image`, `url`, `titre`,`image_principale`,`id_timbre_image`
      FROM TIMBRE
      INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
      LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
      INNER JOIN IMAGES ON id_timbre = id_timbre_image
      WHERE `etat` = :etat 
      AND `date_de_creation` >= :date_de_creation
      AND `mise` >= :prix_min
      AND mise <= :prix_max AND image_principale = 1";
    } else {
      $this->sql = "
      SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
      `pays_origine` , `tirage`, `dimensions`, `etat`,
      `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
      `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,
      `mise`,`id_image`, `url`, `titre`,`image_principale`,`id_timbre_image`
      FROM TIMBRE
      INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
      LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
      INNER JOIN IMAGES ON id_timbre = id_timbre_image
      WHERE `etat` = :etat AND `pays_origine` = :pays_origine 
      AND `date_de_creation` >= :date_de_creation
      AND `mise` >= :prix_min
      AND mise <= :prix_max AND image_principale = 1";
    }
    return $this->getLignes($champs);
  }

  /**
   * Récupération d'une mise
   * @param int $id_enchere, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 

  public function getMise($id_enchere){
    $this->sql = "
      SELECT `mise`
      FROM PARTICIPE
      WHERE id_enchere_mise = :id_enchere_mise";

    return $this->getLignes(['id_enchere_mise' => $id_enchere],RequetesPDO::UNE_SEULE_LIGNE);
  }

  /* GESTION DES UTILISATEURS 
     ======================== */

   /**
   * Récupération des enchère personnelles
   * @param int $utilisateur_id, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEncheresPerso($utilisateur_id) {
    $this->sql = "
      SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
      `pays_origine` , `tirage`, `dimensions`, `etat`,
      `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
      `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,
      `mise`,`id_image`, `url`, `titre`,
      `image_principale`,`id_timbre_image`
      FROM TIMBRE
      INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
      LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
      INNER JOIN IMAGES ON id_timbre = id_timbre_image
      WHERE id_timbre_utilisateur = :utilisateur_id AND image_principale = 1
      ORDER BY id_timbre DESC";

    return $this->getLignes(['utilisateur_id' => $utilisateur_id]);
  }

  /**
   * Récupération des mises personnelles
   * @param int $utilisateur_id, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 

  public function getMisesPerso($utilisateur_id) {

    $this->sql = "
    SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
    `pays_origine` , `tirage`, `dimensions`, `etat`,
    `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
    `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,
    `mise`,`id_image`, `url`, `titre`, `utilisateur_id_mise`,
    `image_principale`,`id_timbre_image`
    FROM TIMBRE
    INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
    LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
    INNER JOIN IMAGES ON id_timbre = id_timbre_image
    WHERE utilisateur_id_mise = :utilisateur_id AND image_principale = 1";

    return $this->getLignes(['utilisateur_id' => $utilisateur_id]);
  }

  /**
   * Récupération d'une enchère
   * @param int $timbre_id, clé de l'enchère
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEnchereModif($enchere_id) {
    $this->sql = "
      SELECT `id_timbre`, `nom`, `date_de_creation`, `couleurs`, 
      `pays_origine` , `tirage`, `dimensions`, `etat`,
      `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`,
      `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`,
      `mise`,`id_image`, `url`, `titre`,
      `image_principale`,`id_timbre_image`
      FROM TIMBRE
      INNER JOIN ENCHERE ON id_enchere = id_timbre_enchere
      LEFT OUTER JOIN PARTICIPE ON id_enchere_mise = id_enchere
      INNER JOIN IMAGES ON id_timbre = id_timbre_image
      WHERE id_timbre_enchere = :id_enchere AND image_principale = 1";

    return $this->getLignes(['id_enchere' => $enchere_id],RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Récupération d'un utilisateur
   * @param int $enchere_id, clé du utilisateur  
   * @return object Utilisateur
   */ 
  public function getUtilisateurEnchere($enchere_id) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM PARTICIPE
      JOIN UTILISATEUR ON utilisateur_id_mise = utilisateur_id
      WHERE id_enchere_mise = :id_enchere";
    return $this->getLignes(['id_enchere' => $enchere_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter une mise
   * @param array $champs tableau des champs de la mise
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterMise($champs) {
    $this->sql = '
      UPDATE PARTICIPE SET
      mise                     = :mise,
      utilisateur_id_mise      = :utilisateur_id_mise
      WHERE id_enchere_mise    = :id_enchere_mise;';
    return $this->CUDLigne($champs);
  }

  /**
   * Première mise
   * @param array $champs tableau des champs de la mise
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function premiereMise($champs) {
    $this->sql = '
      INSERT INTO PARTICIPE SET
      mise                     = :mise,
      utilisateur_id_mise      = :utilisateur_id_mise,
      id_enchere_mise          = :id_enchere_mise;';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer une enchère
   * @param int $enchere_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerEnchere($enchere_id) {
    $this->sql = '
    DELETE FROM ENCHERE WHERE id_enchere = :id_enchere';
    return $this->CUDLigne(['id_enchere' => $enchere_id]);
  }

  /**
   * Récupération des utilisateurs
   * @return array tableau d'objets Utilisateur
   */ 
  public function getUtilisateurs() {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM UTILISATEUR";
     return $this->getLignes();
  }

  /**
   * Récupération d'un utilisateur
   * @param int $utilisateur_id, clé du utilisateur  
   * @return object Utilisateur
   */ 
  public function getUtilisateur($utilisateur_id) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM UTILISATEUR
      WHERE utilisateur_id = :utilisateur_id";
    return $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Contrôler si adresse courriel non déjà utilisée par un autre utilisateur que utilisateur_id
   * @param array $champs tableau utilisateur_courriel et utilisateur_id (0 si dans toute la table)
   * @return string|false utilisateur avec ce courriel, false si courriel disponible
   */ 
  public function controlerCourriel($champs) {
    $this->sql = 'SELECT utilisateur_id FROM UTILISATEUR
                  WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_id != :utilisateur_id';
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Connecter un utilisateur
   * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
   * @return object Utilisateur
   */ 
  public function connecter($champs) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM UTILISATEUR
      WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_mdp = SHA2(:utilisateur_mdp, 512)";
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterUtilisateur($champs) {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => 0]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      INSERT INTO UTILISATEUR SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_mdp      = SHA2(:utilisateur_mdp, 512),
      utilisateur_profil   = :utilisateur_profil';
    return $this->CUDLigne($champs);
  }

  /**
   * Ajouter un timbre
   * @param array $champs tableau des champs du timbre
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterTimbre($champs) {
    $this->sql = '
      INSERT INTO TIMBRE SET
      nom                = :nom,
      date_de_creation   = :date_de_creation,
      couleurs           = :couleurs,
      pays_origine       = :pays_origine,
      tirage             = :tirage,
      dimensions         = :dimensions,
      etat               = :etat,
      certification      = :certification,
      id_timbre_enchere  = :id_timbre_enchere,
      id_timbre_utilisateur = :id_timbre_utilisateur';
    return $this->CUDLigne($champs);
  }

  /**
   * Ajouter une Enchère
   * @param array $champs tableau des champs de l'enchère
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterEnchere($champs) {
    $this->sql = '
      INSERT INTO ENCHERE SET
      date_debut            = :date_debut,
      date_fin              = :date_fin,
      prix_plancher         = :prix_plancher,
      coup_de_coeur_du_lord = :coup_de_coeur_du_lord';
    return $this->CUDLigne($champs);
  }

  /**
   * Ajouter une Image
   * @param array $champs tableau des champs de l'image
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterImage($champs) {
    $this->sql = '
      INSERT INTO IMAGES SET
      url             = :url,
      titre           = :titre,
      image_principale= :image_principale,
      id_timbre_image = :id_timbre_image';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un timbre
   * @param array $champs tableau des champs du timbre
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierTimbre($champs) {
    $this->sql = '
      UPDATE TIMBRE SET
      nom                      = :nom,
      date_de_creation         = :date_de_creation,
      couleurs                 = :couleurs,
      pays_origine             = :pays_origine,
      tirage                   = :tirage,
      dimensions               = :dimensions,
      etat                     = :etat,
      certification            = :certification
      WHERE id_timbre_enchere  = :id_timbre_enchere;';
    return $this->CUDLigne($champs);
  }

  /**
   * Cherche l'id d'un timbre
   * @param array $champs tableau des champs du timbre
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 

  public function GetIdTimbre($champs) {
    $this->sql = '
      SELECT id_timbre FROM TIMBRE
      WHERE id_timbre_enchere  = :id_timbre_enchere';
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Modifier une Enchère
   * @param array $champs tableau des champs de l'enchère
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierEnchere($champs) {
    $this->sql = '
      UPDATE ENCHERE SET
      date_debut               = :date_debut,
      date_fin                 = :date_fin,
      prix_plancher            = :prix_plancher,
      coup_de_coeur_du_lord    = :coup_de_coeur_du_lord
      WHERE id_enchere         = :id_timbre_enchere';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier une Image
   * @param array $champs tableau des champs de l'image
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierImage($champs) {
    $this->sql = '
      UPDATE IMAGES SET
      url                   = :url,
      titre                 = :titre
      WHERE id_timbre_image = :id_timbre_image
      AND image_principale  = :image_principale';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierUtilisateur($champs) {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => $champs['utilisateur_id']]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      UPDATE UTILISATEUR SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_profil   = :utilisateur_profil
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

 /**
   * Modifier le mot de passe d'un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean true si modifié, false sinon
   */ 
  public function modifierUtilisateurMdp($champs) {
    $this->sql = '
      UPDATE UTILISATEUR SET utilisateur_mdp  = SHA2(:utilisateur_mdp, 512)
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerUtilisateur($utilisateur_id) {
    $this->sql = '
      DELETE FROM UTILISATEUR WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }
}