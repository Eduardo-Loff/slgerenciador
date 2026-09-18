<?php 

class Medicacao{

    private $medicamentoId;
    private $medicamentoNome;
    private $medicamentoTipo;

    // Getters

    public function getId(){
        return $this->medicamentoId;
    }

    public function getNome(){
        return $this->medicamentoNome;
    }
    
    public function getTipo(){
        return $this->medicamentoTipo;
    }

    // Setters

    public function setId($id){
        $this->medicamentoId = intval($id);
    }
    
    public function setNome($nome){
        $this->medicamentoNome = ($nome);
    }
    
    public function setTipo($tipo){
        $this->medicamentoTipo = ($tipo);
    }
}