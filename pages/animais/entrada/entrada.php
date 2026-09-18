<?php 

class Entrada{
    private $entradaId;
    private $entradaLoteId; 
    private $entradaOrigem;
    private $entradaQuantidadeMachos;
    private $entradaQuantidadeFemeas;
    private $entradaPm;
    private $entradaPt;
    private $entradaNf;
    private $entradaData;

    // Getters 

    public function getId(){
        return $this->entradaId;
    }

    public function getLoteId(){
        return $this->entradaLoteId;
    }

    public function getOrigem(){
        return $this->entradaOrigem;
    }
    
    public function getMachos(){
        return $this->entradaQuantidadeMachos;
    }

    public function getFemeas(){
        return $this->entradaQuantidadeFemeas;
    }

    public function getPm(){
        return $this->entradaPm;
    }

    public function getPt(){
        return $this->entradaPt;
    }

    public function getNf(){
        return $this->entradaNf;
    }

    public function getData(){
        return $this->entradaData;
    }

    // Setters

    public function setId($id){
        $this->entradaId = intval($id);
    }
    
    public function setLoteId($loteId){
        $this->entradaLoteId = intval($loteId);
    }

    public function setOrigem($origem){
        $this->entradaOrigem = $origem;
    }
    
    public function setMachos($machos){
        $this->entradaQuantidadeMachos = intval($machos);
    }

    public function setFemeas($femeas){
        $this->entradaQuantidadeFemeas = intval($femeas);
    }
    
    public function setPm($pm){
        $this->entradaPm = floatval($pm);
    }
    
    public function setPt($pt){
        $this->entradaPt = floatval($pt);
    }

    public function setNf($nf){
        $this->entradaNf = $nf;
    }

    public function setData($data){
        $this->entradaData = $data;
    }
}