<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Interface.php';

class Notificacoes implements InterfaceModelo{
    private $id_notificacao;
    private $id_usuario;
    private $data_feather;
    private $titulo_notificacao;
    private $mensagem_curta;
    private $mensagem_longa;
    private $data_notificacao;
    private $data_leitura;
    private $status_leitura;

    public function tabela(){
        return (string) 'notificacoes';
    }

    public function modelo(){
        return (array) ['id_notificacao' => (int) 0, 'id_usuario' => (int) 0, 'data_feather' => (string) '', 'titulo_notificacao' => (string) '', 'mensagem_curta' => (string) '', 'mensagem_longa' => (string) '', 'data_notificacao' => 'date', 'data_leitura' => 'date', 'status_leitura' => (string) 'NAO_LIDO'];
    }

    public function colocar_dados($dados){
    }

    public function salvar_dados($dados){
    }

    public function pesquisar($dados){
    }

    public function pesquisar_todos($dados){
    }

    public function deletar($dados){
    }

    /**
     * Responsável por contar e retornar a quantidade de registros de notificacoes que o usuário do sistema possuir
     * @param array dados
     * @return int
     */
    public function contar_notificacoes($dados){
    }
}
?>