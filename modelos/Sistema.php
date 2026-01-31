<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Interface.php';

class Sistema implements InterfaceModelo{
    private $id_sistema;
    public function tabela(){
        return (string) 'sistema';
    }

    public function modelo(){
        return (array) ['_id' => convert_id(''), 'versao_sistema' => (string) ''];
    }

    public function colocar_dados($dados)
    {
        throw new \Exception('Not implemented');
    }

    public function salvar_dados($dados)
    {
        throw new \Exception('Not implemented');
    }

    public function pesquisar($filtro)
    {
        return (array) model_one((string) $this->tabela(), (array) $filtro['filtro']);
    }

    public function pesquisar_todos($filtro)
    {
        return (array) model_all((string) $this->tabela(), (array) $filtro['filtro'], (array) $filtro['ordenacao'], (int) $filtro['limite']);
    }

    public function pesquisar_versao_sistema(){
        $retorno = (array) $this->pesquisar((array) ['filtro' => (array) []]);

        if(empty($retorno) == false){
            if(array_key_exists('versao_sistema', $retorno) == true){
                return (string) $retorno['versao_sistema'];
            }else{
                return (string) 'alfa 0.0';
            }
        }else{
            return (string) 'alfa 0.0';
        }
    }
}
?>