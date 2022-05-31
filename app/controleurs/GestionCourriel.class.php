<?php

/**
 * Classe GestionCourriel
 *
 */
class GestionCourriel {

  /**
   * Envoyer un courriel à l'utilisateur pour lui communiquer
   * son identifiant de connexion et son mot de passe
   * @param object $oUtilisateur utilisateur destinataire
   *
   */
  public function envoyerMdp(Utilisateur $oUtilisateur) {
    
    $destinataire  = $oUtilisateur->utilisateur_courriel; 
    $message       = (new Vue)->generer('cMdp',
                                        array(
                                          'titre'        => 'Information',
                                          'oUtilisateur' => $oUtilisateur
                                        ));
    $headers  = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-Type: text/html; charset=utf-8' . "\n";
    $headers .= 'From: Lord Stampee <support@stpampee.com>' . "\n";
    return mail($destinataire, "Informations", $message, $headers);
  }
}