<?php 

class Aplicacao{

    private $medicacaoLoteId;
    private $medicacaoId;
    private $loteId;
    private $medicacaoLoteDataInicio;
    private $medicacaoLoteDataFim;
    private $medicacaoLoteCausa;
    

    // Getters

    public function getMedicacaoId(){
        return $this->medicacaoId;
    }

    public function getLoteId(){
        return $this->loteId;
    }

    public function getMedicacaoLoteId(){
        return $this->medicacaoLoteId;
    }
    
    public function getDataInicio(){
        return $this->medicacaoLoteDataInicio;
    }

    public function getDataFim(){
        return $this->medicacaoLoteDataFim;
    }

    public function getCausa(){
        return $this->medicacaoLoteCausa;
    }

    // Setters

    public function setMedicacaoId($medicacaoId){
        $this->medicacaoId = intval($medicacaoId);
    }
    
    public function setLoteId($loteId){
        $this->loteId = intval($loteId);
    }

    public function setMedicacaoLoteId($medicacaoLoteId){
        $this->medicacaoLoteId = intval($medicacaoLoteId);
    }
    
    public function setMedicacaoDataInicio($dataInicio){
        $this->medicacaoLoteDataInicio = ($dataInicio);
    }

    public function setMedicacaoDataFim($dataFim){
        $this->medicacaoLoteDataFim = ($dataFim);
    }

    public function setMedicacaoCausa($causa){
        $this->medicacaoLoteCausa = ($causa);
    }
}