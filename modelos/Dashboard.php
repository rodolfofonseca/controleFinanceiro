<?php
require_once 'Classes/bancoDeDados.php';
require_once 'contas.php';

class Dashboard
{
    /**
     * Retorna toda as contas cadastradas no banco de dados;
     */
    public function relatorio_saldo_conta(){
        $objeto_conta = new Contas();
        return (array) $objeto_conta->pesquisar_todos((array) ['filtro' => (array) [], 'ordenacao' => (array) ['nome_conta' => (bool) true], 'limite' => (int) 0]);
    }
}
?>