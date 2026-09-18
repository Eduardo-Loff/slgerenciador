<?php

class Lote{
    private $loteId;
    private $lotePropriedadeId;
    private $loteCodigo;
    private $loteObservacoes;
    private $loteEstado;
    private $loteCriacao;

    // Getters

    public function getId(){
        return $this->loteId;
    }

    public function getPropriedadeId(){
        return $this->lotePropriedadeId;
    }

    public function getCodigo(){
        return $this->loteCodigo;
    }

    public function getObservacoes(){
        return $this->loteObservacoes;
    }

    public function getEstado(){
        return $this->loteEstado;
    }

    public function getCriacao(){
        return $this->loteCriacao;
    }

    // Setters

    public function setId($id){
        $this->loteId = intval($id);
    }

   public function setPropriedadeId($propriedadeId){
        $this->lotePropriedadeId = intval($propriedadeId);
   } 

   public function setCodigo($codigo){
        $this->loteCodigo = $codigo;
   }

   public function setObservacoes($observacoes){
        $this->loteObservacoes = $observacoes;
   }

   public function setEstado($estado){
        $this->loteEstado = $estado;
   }

   public function setCriacao($criacao){
        $this->loteCriacao = $criacao;
   }

}