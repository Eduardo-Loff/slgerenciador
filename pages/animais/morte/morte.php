<?php 

class Morte{
    private $morteId;
    private $morteLoteId; 
    private $morteQuantidade;
    private $mortePm;
    private $morteData;
    private $morteCausa;

    // Getters 

    public function getId(){
        return $this->morteId;
    }

    public function getLoteId(){
        return $this->morteLoteId;
    }

    public function getQuantidade(){
        return $this->morteQuantidade;
    }
    
    public function getPm(){
        return $this->mortePm;
    }

    public function getData(){
        return $this->morteData;
    }

    public function getCausa(){
        return $this->morteCausa;
    }

    // Setters

    public function setId($id){
        $this->morteId = intval($id);
    }
    
    public function setLoteId($loteId){
        $this->morteLoteId = intval($loteId);
    }

    public function setQuantidade($quantidade){
        $this->morteQuantidade = intval($quantidade);
    }
    
    public function setPm($pm){
        $this->mortePm = floatval($pm);
    }

    public function setData($data){
        $this->morteData = $data;
    }
    
    public function setCausa($causa){
        $this->morteCausa = $causa;
   }
}