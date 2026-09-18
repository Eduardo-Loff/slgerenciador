<?php 

class Saida{
    private $saidaId;
    private $saidaLoteId; 
    private $saidaDestino;
    private $saidaQuantidade;
    private $saidaData;
    private $saidaNf;
    private $saidaGta;
    private $saidaPm;

    // Getters 

    public function getId(){
        return $this->saidaId;
    }

    public function getLoteId(){
        return $this->saidaLoteId;
    }

    public function getDestino(){
        return $this->saidaDestino;
    }
    
    public function getQuantidade(){
        return $this->saidaQuantidade;
    }

    public function getData(){
        return $this->saidaData;
    }

    public function getNf(){
        return $this->saidaNf;
    }

    public function getGta(){
        return $this->saidaGta;
    }

    public function getPm(){
        return $this->saidaPm;
    }

    // Setters

    public function setId($id){
        $this->saidaId = intval($id);
    }
    
    public function setLoteId($loteId){
        $this->saidaLoteId = intval($loteId);
    }

    public function setDestino($destino){
        $this->saidaDestino = $destino;
    }
    
    public function setQuantidade($quantidade){
        $this->saidaQuantidade = intval($quantidade);
    }

    public function setData($data){
        $this->saidaData = $data;
    }
    
    public function setNf($nf){
        $this->saidaNf = $nf;
    }
    
    public function setGta($gta){
        $this->saidaGta = $gta;
    }

    public function setPm($pm){
        $this->saidaPm = $pm;
    }
}