<?php

/**
 * Classe de l'entité Mise
 *
 */
class Mise extends Entite
{

    protected $mise_direct;
    protected $mise_auto;
  
    protected $erreurs = array();
  
    // Getters explicites nécessaires au moteur de templates TWIG
    public function getMise_direct() { return $this->mise_direct; }
    public function getMise_auto()   { return $this->mise_auto; }

   /**
    * Mutateur de la propriété mise_direct
    * @param int $mise_direct
    * @return $this
    */   
    public function setMise_direct($mise_direct) {
        unset($this->erreurs['mise_direct']);
        if (!preg_match('/^\d+$/', $mise_direct)) {
        $this->erreurs['mise_direct'] = "Entrez une valeur de mise valide";
        }
        $this->mise_direct = $mise_direct;
        return $this;
    }

   /**
    * Mutateur de la propriété mise_auto
    * @param int $mise_direct
    * @return $this
    */   
    public function setMise_auto($mise_auto) {
        unset($this->erreurs['mise_auto']);
        if (!preg_match('/^\d+$/', $mise_auto)) {
        $this->erreurs['mise_auto'] = "Entrez une valeur de mise valide";
        }
        $this->mise_auto = $mise_auto;
        return $this;
    }
}