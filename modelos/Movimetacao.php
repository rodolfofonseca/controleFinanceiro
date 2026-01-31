<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Interface.php';

class Movimetacao implements InterfaceModelo{
    private $id_movimentacao;
    private $conta;
    private $tipo_lancamento;
    private $valor_lancamento;
    private $data_lancamento;
    private $descricao;

    public function tabela(){
        return (string) 'movimentacao';
    }

    public function modelo(){
        return (array) ['_id' => convert_id(''), 'conta' => convert_id(''), 'tipo_lancamento' => (string) 'DEBITO', 'valor_lancamento' => (double) 0.0, 'data_lancamento' => model_date(), 'descricao' => (string) ''];
    }

    public function colocar_dados($dados){
        if(array_key_exists('codigo_movimentacao', $dados)){
            if($dados['codigo_movimentacao'] != ''){
                $this->id_movimentacao = convert_id($dados['codigo_movimentacao']);
            }
        }

        if(array_key_exists('conta', $dados)){
            if($dados['conta'] != ''){
                $this->conta = convert_id($dados['conta']);
            }
        }

        if(array_key_exists('tipo_lancamento', $dados)){
            if($dados['tipo_lancamento'] != ''){
                $this->tipo_lancamento = (string) $dados['tipo_lancamento'];
            }
        }

        if(array_key_exists('valor_lancamento', $dados)){
            if($dados['valor_lancamento'] != 0){
                $this->valor_lancamento = (double) doubleval($dados['valor_lancamento']);
            }else{
                $this->valor_lancamento = (double) 0.0;
            }
        }

        if(array_key_exists('data_lancamento', $dados)){
            if($dados['data_lancamento'] != ''){
                $this->data_lancamento = model_date($dados['data_lancamento']);
            }else{
                $this->data_lancamento = model_date();
            }
        }

        if(array_key_exists('descricao', $dados) == true){
            $this->descricao = (string) $dados['descricao'];
        }
    }

    public function salvar_dados($dados)
    {
        $this->colocar_dados($dados);
        $retorno_insercao = false;
        $retorno_atualizacao = false;

        $retorno_insercao = (bool) model_insert((string) $this->tabela(), (array) ['conta' => $this->conta, 'tipo_lancamento' => (string) $this->tipo_lancamento, 'valor_lancamento' => (double) $this->valor_lancamento, 'data_lancamento' => $this->data_lancamento, 'descricao' => (string) $this->descricao]);

        $objeto_conta = new Contas();
        $retorno_conta = (array) $objeto_conta->pesquisar((array)['filtro' => (array) ['_id', '===', $this->conta]]);

        if(empty($retorno_conta) == false){
            $saldo_atual = (double) 0.0;
            $novo_saldo = (double) 0.0;
            
            if(array_key_exists('saldo_conta', $retorno_conta) == true){
                $saldo_atual = (double) doubleval($retorno_conta['saldo_conta']);
            }

            if($this->tipo_lancamento == 'CREDITO'){
                $novo_saldo = (double) ($saldo_atual + $this->valor_lancamento);
            }else{
                $novo_saldo = (double) ($saldo_atual - $this->valor_lancamento);
            }

            $retorno_atualizacao = (bool) $objeto_conta->atualizar_valor((array) ['codigo_conta' => $this->conta, 'saldo_conta' => (double)$novo_saldo]);
        }

        if($retorno_atualizacao == true && $retorno_insercao == true){
            return (bool) true;
        }else{
            return (bool) false;
        }
    }

    public function pesquisar($filtro)
    {
        return (array) model_one((string) $this->tabela(), (array) $filtro['filtro']);
    }

    public function pesquisar_todos($filtro)
    {
        return (array) model_all((string) $this->tabela(), (array) $filtro['filtro'], (array) $filtro['ordenacao'], (int) intval($filtro['limite'], 10));
    }
}
?>