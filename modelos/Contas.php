<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Interface.php';

class Contas implements InterfaceModelo{
    private $id_conta;
    private $nome_conta;
    private $saldo_conta;

    public function tabela(){
        return (string) 'contas';
    }

    public function modelo(){
        return (array) ['_id' => convert_id(''), 'nome_conta' => (string) '', 'saldo_conta' => (float) 0];
    }

    public function colocar_dados($dados){
        if(array_key_exists('codigo_conta', $dados) == true){
            if($dados['codigo_conta'] != ''){
                $this->id_conta = convert_id($dados['codigo_conta']);
            }else{
                $this->id_conta = null;
            }
        }

        if(array_key_exists('nome_conta', $dados) == true){
            if($dados['nome_conta'] != ''){
                $this->nome_conta = (string) strtoupper($dados['nome_conta']);
            }
        }

        if(array_key_exists('saldo_conta', $dados) == true){
            $this->saldo_conta = (double) doubleval($dados['saldo_conta']);
        }
    }

    public function salvar_dados($dados)
    {
        $this->colocar_dados($dados);

        if($this->id_conta != null){
            return model_update((string) $this->tabela(), (array) ['_id', '===', $this->id_conta], (array) ['nome_conta' => (string) $this->nome_conta, 'saldo_conta' => (double)doubleval($this->saldo_conta)]);
        }else{
            return model_insert((string) $this->tabela(), (array) ['nome_conta' => (string) $this->nome_conta, 'saldo_conta' => (double) doubleval($this->saldo_conta)]);
        }
    }

    public function pesquisar($filtro)
    {
        return model_one((string) $this->tabela(), (array) $filtro['filtro']);
    }

    public function pesquisar_todos($filtro)
    {
        return model_all((string) $this->tabela(), (array) $filtro['filtro'], $filtro['ordenacao'], $filtro['limite']);
    }

    public function atualizar_valor($dados){
        $this->colocar_dados($dados);

        return (bool) model_update((string) $this->tabela(), ['_id', '===', $this->id_conta], (array) ['saldo_conta' => (double) doubleval($this->saldo_conta)]);
    }
}
?>