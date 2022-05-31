<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Enchere de l'application admin
 */

class AdminEnchere extends Admin {

  private $methodes = [
    'l'           => 'listerEncheres',
    'a-e'         => 'ajouterEnchere',
    'm-e'         => 'modifierEnchere',
    's-e'         => 'supprimerEnchere'
  ];

  /**
   * Gérer l'interface d'administration des utilisateurs 
   */  
  public function gerer($entite = "enchere") {

    $this->entite  = $entite;
    $this->action  = $_GET['action'] ?? 'l';
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
   * Lister les encheres
   */
  public function listerEncheres() {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime(); // utilisation directe de la constante MOCK_NOW
    $aujourdhui_mock  = $oAujourdhui->format('Y-m-d');
    $encheres = self::$oRequetesSQL->getEncheresPerso(self::$u->utilisateur_id);
    (new Vue)->generer('vAdminUtilisateurEncheres',
            array(
              'u'                   => self::$u,
              'titre'               => 'Vos enchères',
              'encheres'            => $encheres,
              'classRetour'         => $this->classRetour,  
              'messageRetourAction' => $this->messageRetourAction,
              'aujourdhui_mock'     => $aujourdhui_mock 
            ),
            'gabarit-admin');
  }

  /**
   * Ajouter une enchère
   */
  public function ajouterEnchere() {
    
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oEnchere = new Enchere($utilisateur);
      $erreursEnchere = $oEnchere->erreurs;
      if (count($erreursEnchere) === 0) {
            $retourEnchere = self::$oRequetesSQL->ajouterEnchere([
              'date_debut'            => $oEnchere->enchere_date_debut,
              'date_fin'              => $oEnchere->enchere_date_fin,
              'prix_plancher'         => $oEnchere->enchere_prix_plancher,
              'coup_de_coeur_du_lord' => $oEnchere->enchere_coup_de_coeur_du_lord,
            ]);
            if (preg_match('/^[1-9]\d*$/', $retourEnchere)) {
              $retourTimbre = self::$oRequetesSQL->ajouterTimbre([
                'nom'                   => $oEnchere->timbre_nom,
                'date_de_creation'      => $oEnchere->timbre_date_de_creation,
                'couleurs'              => $oEnchere->timbre_couleurs,
                'pays_origine'          => $oEnchere->timbre_pays_origine,
                'tirage'                => $oEnchere->timbre_tirage,
                'dimensions'            => $oEnchere->timbre_dimensions,
                'etat'                  => $oEnchere->timbre_etat,
                'certification'         => $oEnchere->timbre_certification,
                'id_timbre_enchere'     => $retourEnchere,
                'id_timbre_utilisateur' => self::$u->utilisateur_id
              ]); 
            } else {
              $this->classRetour = "erreur";         
              $this->messageRetourAction = "Ajout de l'enchère non effectué.";
              exit;
            }
            if (preg_match('/^[1-9]\d*$/', $retourTimbre)) {
              for ($i=1; $i < 4; $i++) { 
                if ($_FILES["timbre_image-$i"]["name"]) {
                  $filename = $_FILES["timbre_image-$i"]["name"];
                  $tempname = $_FILES["timbre_image-$i"]["tmp_name"];
                  $folder = "./Images/".$filename;
                  $extensions_valides = array( 'jpg' , 'jpeg' );
                  $extension_upload = strtolower(  substr(  strrchr($_FILES["timbre_image-$i"]["name"], '.')  ,1)  );
                  if (in_array($extension_upload,$extensions_valides)) {
                    if (move_uploaded_file($tempname, $folder)) {
                      $retourImage = self::$oRequetesSQL->ajouterImage([
                        'url'                   => $folder,
                        'titre'                 => $oEnchere->timbre_image_titre,
                        'image_principale'      => $i,
                        'id_timbre_image'       => $retourTimbre
                    ]);}
                  } else {
                    $this->classRetour = "erreur";         
                    $this->messageRetourAction = "Upload de l'image non effectuée";
                    exit;
                  }
                }
              }
            } else {
              self::$oRequetesSQL->supprimerEnchere($retourEnchere);
              $this->classRetour = "erreur";         
              $this->messageRetourAction = "Ajout de l'enchère non effectué.";
            }
            if ($retourImage) {
              $this->messageRetourAction = "Ajout de l'enchère numéro $retourEnchere effectué."; 
            } else {
              self::$oRequetesSQL->supprimerEnchere($retourEnchere);
              $this->classRetour = "erreur";         
              $this->messageRetourAction = "Ajout de l'enchère non effectué.";
            }
            $this->listerEncheres();
            exit;
      }
    } else {
      $utilisateur = [];
      $erreursEnchere = [];
    }
    
    (new Vue)->generer('vAdminEnchereAjouter',
            array(
              'u'                   => self::$u,
              'titre'               => 'Ajouter une enchère',
              'utilisateur'         => $utilisateur,
              'erreursEnchere'      => $erreursEnchere,
              'classRetour'         => $this->classRetour,  
              'messageRetourAction' => $this->messageRetourAction
            ),
            'gabarit-admin');
  }

  /**
   * Modifier une enchère
   */
  public function modifierEnchere() {
    if (!preg_match('/^\d+$/', $this->id_enchere))
      throw new Exception("Numéro d'enchère incorrect pour une suppression.");
      if (count($_POST) !== 0) {
        $utilisateur = $_POST;
        $erreurImage = 0;
        $oEnchere = new Enchere($utilisateur);
        $erreursEnchere = $oEnchere->erreurs;
        if (count($erreursEnchere) === 0 && $erreurImage === 0) {
              $retourEnchere = self::$oRequetesSQL->modifierEnchere([
                'date_debut'            => $oEnchere->enchere_date_debut,
                'date_fin'              => $oEnchere->enchere_date_fin,
                'prix_plancher'         => $oEnchere->enchere_prix_plancher,
                'coup_de_coeur_du_lord' => $oEnchere->enchere_coup_de_coeur_du_lord,
                'id_timbre_enchere'     => $this->id_enchere,
              ]);
              if ($retourEnchere === true) {
                $idTimbre = self::$oRequetesSQL->GetIdTimbre([
                  'id_timbre_enchere'     => $this->id_enchere,
                ]);
                $images = self::$oRequetesSQL->getImages($idTimbre["id_timbre"]);
                $retourTimbre = self::$oRequetesSQL->modifierTimbre([
                  'nom'                   => $oEnchere->timbre_nom,
                  'date_de_creation'      => $oEnchere->timbre_date_de_creation,
                  'couleurs'              => $oEnchere->timbre_couleurs,
                  'pays_origine'          => $oEnchere->timbre_pays_origine,
                  'tirage'                => $oEnchere->timbre_tirage,
                  'dimensions'            => $oEnchere->timbre_dimensions,
                  'etat'                  => $oEnchere->timbre_etat,
                  'certification'         => $oEnchere->timbre_certification,
                  'id_timbre_enchere'     => $this->id_enchere,
                ]); 
              } else {
                $this->classRetour = "erreur";         
                $this->messageRetourAction = "Modification de l'enchère non effectuée.";
                exit;
              }
              if ($retourTimbre === true) { 
                for ($i = 1; $i < 4; $i++) {
                  if (isset($images[$i-1]["image_principale"])) {
                    if ($_FILES["timbre_image-$i"]["name"]) {
                      $filename = $_FILES["timbre_image-$i"]["name"];
                      $tempname = $_FILES["timbre_image-$i"]["tmp_name"];
                      $folder = "./Images/".$filename;
                      $extensions_valides = array( 'jpg' , 'jpeg' );
                      $extension_upload = strtolower(  substr(  strrchr($_FILES["timbre_image-$i"]["name"], '.')  ,1)  );
                      if (in_array($extension_upload,$extensions_valides)) {
                        if (move_uploaded_file($tempname, $folder)) {
                          $retourImage = self::$oRequetesSQL->modifierImage([
                            'url'                   => $folder,
                            'titre'                 => $oEnchere->timbre_image_titre,
                            'image_principale'      => $i,
                            'id_timbre_image'       => $idTimbre["id_timbre"]
                        ]);}
                      } else {
                        $this->classRetour = "erreur";         
                        $this->messageRetourAction = "Upload de l'image $i non effectuée";
                        exit;
                      }
                    }
                  } else {
                    if ($_FILES["timbre_image-$i"]["name"]) {
                      $filename = $_FILES["timbre_image-$i"]["name"];
                      $tempname = $_FILES["timbre_image-$i"]["tmp_name"];
                      $folder = "./Images/".$filename;
                      $extensions_valides = array( 'jpg' , 'jpeg' );
                      $extension_upload = strtolower(  substr(  strrchr($_FILES["timbre_image-$i"]["name"], '.')  ,1)  );
                      if (in_array($extension_upload,$extensions_valides)) {
                        if (move_uploaded_file($tempname, $folder)) {
                          $retourImage = self::$oRequetesSQL->ajouterImage([
                            'url'                   => $folder,
                            'titre'                 => $oEnchere->timbre_image_titre,
                            'image_principale'      => $i,
                            'id_timbre_image'       => $idTimbre["id_timbre"]
                        ]);}
                      } else {
                        $this->classRetour = "erreur";         
                        $this->messageRetourAction = "Upload de l'image non effectuée";
                        exit;
                      }
                    }
                  }
                }
              } else {
                $this->classRetour = "erreur";         
                $this->messageRetourAction = "Modification de l'enchère non effectuée.";
              }
              if ($retourImage === true || preg_match('/^[1-9]\d*$/', $retourImage)) {
                $this->messageRetourAction = "Modification de l'enchère numéro $this->id_enchere effectuée."; 
              } else {
                $this->classRetour = "erreur";         
                $this->messageRetourAction = "Modification de l'enchère non effectuée.";
              }
              $this->listerEncheres();
              exit;
        } else {
          $enchere = self::$oRequetesSQL->getEnchereModif($this->id_enchere);
        }
      } else {
        $enchere = self::$oRequetesSQL->getEnchereModif($this->id_enchere);
        $utilisateur = [];
        $erreursEnchere = [];
      }
      
      (new Vue)->generer('vAdminEnchereModifier',
              array(
                'u'                   => self::$u,
                'titre'               => 'Modifier une enchère',
                'utilisateur'         => $utilisateur,
                'erreursEnchere'      => $erreursEnchere,
                'classRetour'         => $this->classRetour,  
                'messageRetourAction' => $this->messageRetourAction,
                'enchere'             => $enchere
              ),
              'gabarit-admin');
  }

  /**
   * Supprimer une enchère
   */
  public function supprimerEnchere() {
    if (!preg_match('/^\d+$/', $this->id_enchere))
      throw new Exception("Numéro d'enchère incorrect pour une suppression.");
    $retour = self::$oRequetesSQL->supprimerEnchere($this->id_enchere);
    if ($retour === false) $this->classRetour = "erreur";
    $this->messageRetourAction = "Suppression de l'enchère numéro $this->id_enchere ".($retour ? "" : "non ")."effectuée.";
    $this->listerEncheres();
  }

}