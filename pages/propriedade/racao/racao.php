<?php 

class Racao{
    private $racaoId;
    private $racaoLoteId; 
    private $racaoQuantidade;
    private $racaoData;
    private $racaoNf;
    private $racaoTipo;

    // Getters 

    public function getId(){
        return $this->racaoId;
    }

    public function getLoteId(){
        return $this->racaoLoteId;
    }

    public function getQuantidade(){
        return $this->racaoQuantidade;
    }
    
    public function getNf(){
        return $this->racaoNf;
    }

    public function getData(){
        return $this->racaoData;
    }

    public function getTipo(){
        return $this->racaoTipo;
    }

    // Setters

    public function setId($id){
        $this->racaoId = intval($id);
    }
    
    public function setLoteId($loteId){
        $this->racaoLoteId = intval($loteId);
    }

    public function setQuantidade($quantidade){
        $this->racaoQuantidade = intval($quantidade);
    }
    
    public function setNf($nf){
        $this->racaoNf = floatval($nf);
    }

    public function setData($data){
        $this->racaoData = $data;
    }
    
    public function setTipo($tipo){
        $this->racaoTipo = $tipo;
   }
}