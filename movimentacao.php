<?php
require_once 'Classes/bancoDeDados.php';
require_once 'modelos/Movimetacao.php';
require_once 'modelos/Contas.php';

router_add('index', function () {
    $data = new DateTime();
    $objeto_conta = new Contas();

    $retorno_conta = (array) $objeto_conta->pesquisar_todos((array) ['filtro' => (array) [], 'ordenacao' => (array) ['nome_conta' => (bool) true], 'limite' => (int) 100]);
    include_once 'includes/head.php';

    $primeiro_dia = $data->format('Y-m-01');
    $ultimo_dia   = $data->format('Y-m-t');
    ?>
    <script>
        function pesquisar_movimentacao(){
            let codigo_conta = document.querySelector('#conta').value;
            let tipo_lancamento = document.querySelector('#tipo_lancamento').value;
            let descricao = document.querySelector('#descricao').value;
            let saldo_conta = document.querySelector('#saldo_conta').value;
            let data_inicial = document.querySelector('#data_inicial').value;
            let data_final = document.querySelector('#data_final').value;

            sistema.request.post('/movimentacao.php', {'rota': 'pesquisar_movimentacao', 'codigo_conta':codigo_conta, 'descricao':descricao, 'saldo_conta':saldo_conta, 'tipo_lancamento':tipo_lancamento, 'data_inicial':data_inicial, 'data_final':data_final}, function(retorno){
                let movimentacoes = retorno.dados;
                let tamanho_retorno = movimentacoes.length;
                let tabela = document.querySelector('#tabela_movimentacao tbody');
                tabela = sistema.remover_linha_tabela(tabela);

                if(tamanho_retorno == 0){
                    let linha = document.createElement('tr');
                    linha.appendChild(sistema.gerar_td(['text-center'], 'NENHUMA MOVIMENTAÇÃO ENCONTRADA COM O FILTRO PASSADO!', 'inner', true, 5));
                    tabela.appendChild(linha);
                }else{
                    sistema.each(movimentacoes, function(index, movimentacao){
                        let linha = document.createElement('tr');

                        linha.appendChild(sistema.gerar_td(['text-center'], movimentacao.nome_conta, 'inner'));
                        linha.appendChild(sistema.gerar_td(['text-center'], movimentacao.descricao, 'inner'));
                        linha.appendChild(sistema.gerar_td(['text-center'], movimentacao.valor_lancamento, 'inner'));

                        if(movimentacao.tipo_lancamento == 'DEBITO'){
                            linha.appendChild(sistema.gerar_td(['text-center'], sistema.gerar_botao('botao_selecionar_' + movimentacao._id.$oid, 'DÉBITO', ['btn', 'btn-danger'], function visualizar() {}), 'append'));
                        }else{
                            linha.appendChild(sistema.gerar_td(['text-center'], sistema.gerar_botao('botao_selecionar_' + movimentacao._id.$oid, 'CRÉDITO', ['btn', 'btn-success'], function visualizar() {}), 'append'));
                        }

                        linha.appendChild(sistema.gerar_td(['text-center'], retornar_data(movimentacao.data_lancamento), 'inner'));

                        tabela.appendChild(linha);
                    });
                }
            });
        }

        function cadastrar_movimentacao(id_conta){
            window.location.href = sistema.url('/movimentacao.php', {'rota': 'salvar_dados','conta': id_conta});
        }
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Movimentação</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <button class="btn btn-secondary custom-radius botao_grande btn-lg" onclick="cadastrar_movimentacao('');">CADASTRAR MOVIMENTAÇÃO</button>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-2 text-center">
                                <select class="form-control custom-radius" id="conta" onchange="pesquisar-_movimentacao();">
                                    <?php
                                    if(empty($retorno_conta) == true){
                                        echo "<option value = ''>NENHUMA CONTA ENCONTRADA</option>";
                                    }else{
                                        echo "<option value='TODAS'>TODAS</option>";
                                        foreach($retorno_conta as $contas){
                                            echo "<option value = ".$contas['_id'].">".$contas['nome_conta']."/ ".$contas['saldo_conta']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-2 text-center">
                                <input type="text" class="form-control custom-radius" id="descricao" placeholder="descrição" onkeyup="pesquisar_movimentacao();">
                            </div>
                            <div class="col-2 text-center">
                                <input type="text" class="form-control custom-radius" id="saldo_conta" placeholder="00,00" onkeyup="pesquisar_movimentacao();"/>
                            </div>
                            <div class="col-2 text-center">
                                <select id="tipo_lancamento" class="form-control custom-radius" onchange="pesquisar_movimentacao">
                                    <option value="TODOS">TODOS</option>
                                    <option value="CREDITO">CREDITO</option>
                                    <option value="DEBITO">DEBITO</option>
                                </select>
                            </div>
                            <div class="col-2 text-center">
                                <input type="date" id="data_inicial" class="form-control custom-radius" value="<?php echo $primeiro_dia; ?>">
                            </div>
                            <div class="col-2 text-center">
                                <input type="date" id="data_final" class="form-control custom-radius" value="<?php echo $ultimo_dia; ?>">
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-3 push-9">
                                <button type="button" class="btn btn-info custom-radius botao_grande btn-lg" onclick="pesquisar_movimentacao();">PESQUISAR</button>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="tabela_movimentacao">
                                        <thead class="bg-info text-white">
                                            <tr class="text-center">
                                                <th scope="col">NOME CONTA</th>
                                                <th scope="col">DESCRIÇÃO</th>
                                                <th scope="col">VALOR</th>
                                                <th scope="col">TIPO</th>
                                                <th scope="col">DATA MOVIMENTACAO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center">UTILIZE OS FILTROS PARA FACILITAR SUA PESQUISA</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function(){
            pesquisar_movimentacao();
        }
    </script>
    <?php
    include_once 'includes/footer.php';
    exit;
});

router_add('salvar_dados', function(){
    $objeto_conta = new Contas();
    $data = new DateTime();

    $retorno_conta = (array) $objeto_conta->pesquisar_todos((array) ['filtro' => (array) [], 'ordenacao' => (array) ['nome_conta' => (bool) true], 'limite' => (int) 100]);

    $data_inicial = $data->format('Y-m-01');
    include_once 'includes/head.php';
    ?>
    <script>
        function salvar_dados(){
            let conta = document.querySelector('#conta').value;
            let lancamento = document.querySelector('#tipo_lancamento').value;
            let data_lancamento = document.querySelector('#data_lancamento').value;
            let valor_lancamento = sistema.float(document.querySelector('#valor_lancamento').value);
            let descricao = document.querySelector('#descricao').value;

            sistema.request.post('/movimentacao.php', {'rota':'salvar_dados_movimentacao', 'conta':conta, 'tipo_lancamento':lancamento, 'valor_lancamento':valor_lancamento, 'data_lancamento':data_lancamento, 'descricao':descricao}, function(retorno){
                validar_retorno(retorno, '/movimentacao.php');
            });
        }

        function limpar_campos(){
            document.querySelector('#valor_lancamento').value = '';
            document.querySelector('#descricao').value = '';
        }

        function voltar(){
            window.location.href = sistema.url('/movimentacao.php', {'rota':'index'});
        }
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Movimentação</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <label>CONTA</label>
                                <select class="form-control custom-radius" id="conta">
                                    <?php
                                    if(empty($retorno_conta) == true){
                                        echo "<option value = ''>NENHUMA CONTA ENCONTRADA</option>";
                                    }else{
                                        foreach($retorno_conta as $contas){
                                            echo "<option value = ".$contas['_id'].">".$contas['nome_conta']."/ ".$contas['saldo_conta']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-3 text-center">
                                <label>LANÇAMENTO</label>
                                <select class="form-control custom-radius" id="tipo_lancamento">
                                    <option value="DEBITO">DEBITO</option>
                                    <option value="CREDITO">CRÉDITO</option>
                                </select>
                            </div>
                            <div class="col-3 text-center">
                                <label>DATA</label>
                                <input type="date" class="form-control custom-radius" id="data_lancamento" value="<?php echo $data_inicial; ?>">
                            </div>
                            <div class="col-3 text-center">
                                <label>VALOR</label>
                                <input type="text" class="form-control custom-radius" id="valor_lancamento" sistema-mask="moeda">
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-12 text-center">
                                <textarea class="form-control custom-radius" id="descricao"></textarea>
                            </div>
                        </div>
                        <br/>
                        <?php include_once 'includes/botao_cadastro.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
    <?php
    include_once 'includes/footer.php';
});

router_add('salvar_dados_movimentacao', function(){
    $objeto_movimentacao = new Movimetacao();

    echo json_encode((array) ['status' => (bool) $objeto_movimentacao->salvar_dados($_REQUEST)], JSON_UNESCAPED_UNICODE);
    exit;
});

router_add('pesquisar_movimentacao', function(){
    $objeto_movimentacao  = new Movimetacao();
    $objeto_conta = new Contas();

    $data = new DateTime();
    $data_inicial = $data->format('Y-m-01');
    $data_final   = $data->format('Y-m-t');

    $codigo_conta = (string) (isset($_REQUEST['codigo_conta']) ? (string) $_REQUEST['codigo_conta']:'TODAS');
    $descricao = (string) (isset($_REQUEST['descricao'])? (string) $_REQUEST['descricao']:'');
    $saldo_conta_string = (string) (isset($_REQUEST['saldo_conta']) ? (string) $_REQUEST['saldo_conta']:'');
    $tipo_lancamento = (string) (isset($_REQUEST['tipo_lancamento']) ? (string) $_REQUEST['tipo_lancamento']:'TODOS');
    $data_inicial = (string) (isset($_REQUEST['data_inicial']) ? (string) $_REQUEST['data_inicial']: $data_inicial);
    $data_final = (string) (isset($_REQUEST['data_final']) ? (string) $_REQUEST['data_final']: $data_final);

    $filtro = (array) [];
    $filtro_contas = (array) [];
    $retorno_lancameto_index = (array) [];

    if($codigo_conta != 'TODAS'){
        array_push($filtro, (array) ['conta', '===', convert_id($codigo_conta)]);
    }

    if($descricao != ''){
        array_push($filtro, (array) ['descricao', '=', (string) $descricao]);
    }

    if($tipo_lancamento != 'TODOS'){
        array_push($filtro, (array) ['tipo_lancamento', '===', (string) $tipo_lancamento]);
    }

    if($data_inicial != ''){
        array_push($filtro, (array) ['data_lancamento', '>', model_date($data_inicial, '00:00:00')]);
    }

    if($data_final != ''){
        array_push($filtro, (array) ['data_lancamento', '<', model_date($data_final, '23:59:59')]);
    }

    if($saldo_conta_string != ''){
        $saldo_conta = (double) doubleval((string) str_replace(',', '.', $saldo_conta_string));
        array_push($filtro, (array) ['valor_lancamento', '===', (double) $saldo_conta]);
    }

    $filtro_pesquisa = (array) ['and' => (array) $filtro];

    $retorno_pesquisar_movimentacao = (array) $objeto_movimentacao->pesquisar_todos((array) ['filtro' => (array) $filtro_pesquisa, 'ordenacao' => (array) ['data_lancamento' => (bool) false], 'limite' => (int) 100]);

    if(empty($retorno_pesquisar_movimentacao) == false){
        foreach($retorno_pesquisar_movimentacao as $retorno){
            $conta_retorno = (array) [];

            $filtro_contas = (array) ['_id', '===', $retorno['conta']];

            if(empty($filtro_contas) == false){
                $conta_retorno = (array) $objeto_conta->pesquisar((array) ['filtro' => (array) $filtro_contas]);
            }

            if(array_key_exists('nome_conta', $conta_retorno) == true){
                $retorno['nome_conta'] = $conta_retorno['nome_conta'];
            }

            array_push($retorno_lancameto_index, $retorno);
        }
    }

    echo json_encode((array) ['dados' => (array) $retorno_lancameto_index],JSON_UNESCAPED_UNICODE);
    exit;
});
?>