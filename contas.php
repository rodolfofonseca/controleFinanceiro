<?php
require_once 'Classes/bancoDeDados.php';
require_once 'modelos/Contas.php';

router_add('index', function(){
    include_once 'includes/head.php';
    ?>
    <script>
        function pesquisar_conta(){
            let nome_conta = document.querySelector('#nome_conta').value;
            let saldo_conta = document.querySelector('#saldo_conta').value;

            sistema.request.post('/contas.php', {'rota':'pesquisar_contas', 'nome_conta':nome_conta, 'saldo_conta':saldo_conta}, function(retorno){
                let contas = retorno.dados;
                let tamanho_retorno = contas.length;
                let tabela = document.querySelector('#tabela_contas tbody');
                tabela = sistema.remover_linha_tabela(tabela);

                if(tamanho_retorno == 0){
                    let linha = document.createElement('tr');
                    linha.appendChild(sistema.gerar_td(['text-center'], 'NENHUMA CONTA ENCONTRADA COM O FILTRO PASSADO!', 'inner', true, 3));
                    tabela.appendChild(linha);
                }else{
                    sistema.each(contas, function(index, conta){
                        let linha = document.createElement('tr');
                        linha.appendChild(sistema.gerar_td(['text-center'], conta.nome_conta, 'inner'));
                        linha.appendChild(sistema.gerar_td(['text-center'], conta.saldo_conta, 'inner'));

                        linha.appendChild(sistema.gerar_td(['text-center'], sistema.gerar_botao('botao_selecionar_' + conta._id.$oid, 'VISUALZAR', ['btn', 'btn-secondary'], function alterar() {cadastrar_conta(conta._id.$oid);}), 'append'));

                        tabela.appendChild(linha);
                    });
                }
            }, false);
        }

        function cadastrar_conta(id_conta){
            window.location.href = sistema.url('/contas.php', {'rota': 'salvar_dados_contas','codigo_conta': id_conta});
        }
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Contas</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <button type="button" class="btn btn-secondary custom-radius botao_grande btn-lg" onclick="cadastrar_conta('');">CADASTRAR CONTA</button>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-3 text-center">
                                <input type="text" class="form-control custom-radius" placeholder="Nome Conta" id="nome_conta" onkeyup="pesquisar_conta();">
                            </div>
                            <div class="col-3 text-center">
                                <input type="text" class="form-control custom-radius" placeholder="Valor Conta" id="saldo_conta" onkeyup="pesquisar_conta();">
                            </div>
                            <div class="col-3 push-3">
                                <button type="button" class="btn btn-info custom-radius botao_grande btn-lg" onclick="pesquisar_conta();">PESQUISAR</button>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="tabela_contas">
                                        <thead class="bg-info text-white">
                                            <tr class="text-center">
                                                <th scope="col">NOME</th>
                                                <th scope="col">VALOR</th>
                                                <th scope="col">AÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="text-center">UTILIZE OS FILTROS PARA FACILITAR SUA PESQUISA</td>
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
        pesquisar_conta();
    </script>
    <?php
    include_once 'includes/footer.php';
exit;
});

router_add('salvar_dados_contas', function(){
    $id_conta = (string) (isset($_REQUEST['codigo_conta']))? $_REQUEST['codigo_conta'] : '';

    $nome_conta = '';
    $saldo_conta = 0.0;

    if($id_conta != ''){
        $objeto_conta = new Contas();
        $dados_conta = (array) $objeto_conta->pesquisar((array) ['filtro' => (array) ['_id', '===', convert_id($id_conta)]]);
        if(empty($dados_conta) == false){
            if(array_key_exists('nome_conta', $dados_conta) == true){
                $nome_conta = (string) $dados_conta['nome_conta'];
            }

            if(array_key_exists('saldo_conta', $dados_conta) == true){
                $saldo_conta = (float) floatval($dados_conta['saldo_conta']);
            }
        }
    }

    include_once 'includes/head.php';
    ?>
    <script>
        function salvar_dados(){
            let codigo_conta = document.querySelector('#codigo_conta').value;
            let nome_conta = document.querySelector('#nome_conta').value;
            let saldo_conta = sistema.float(document.querySelector('#saldo_conta').value);

            sistema.request.post('/contas.php', {'rota':'salvar_dados_conta', 'codigo_conta':codigo_conta, 'nome_conta':nome_conta, 'saldo_conta':saldo_conta}, function(retorno){
                validar_retorno(retorno, '/contas.php');
            });
        }

        function limpar_campos(){
            document.querySelector('#nome_conta').value = '';
            document.querySelector('#saldo_conta').value = '';
            document.querySelector('#codigo_conta').value = '';
        }

        function voltar(){
            window.location.href = sistema.url('/contas.php', {'rota':'index'});
        }
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Salvar Dados Conta</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-center">
                                <label for="codigo_conta">Código Conta</label>
                                <input type="text" class="form-control custom-radius" id="codigo_conta" value="<?php echo $id_conta; ?>" disabled>
                            </div>
                            <div class="col-4 text-center">
                                <label for="nome_conta">Nome Conta</label>
                                <input type="text" class="form-control custom-radius text-uppercase" id="nome_conta" value="<?php echo $nome_conta;?>">
                            </div>
                            <div class="col-4 text-center">
                                <label for="saldo_conta">Saldo Conta</label>
                                <input type="text" class="form-control custom-radius" id="saldo_conta" value="<?php echo $saldo_conta;?>" sistema-mask="moeda">
                            </div>
                        </div>
                        <br/>
                        <?php include_once 'includes/botao_cadastro.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once 'includes/footer.php';
    exit;
});

router_add('salvar_dados_conta', function(){
    $objeto_conta = new Contas();
    echo json_encode((array) ['status' => (bool) $objeto_conta->salvar_dados($_REQUEST)], JSON_UNESCAPED_UNICODE);
    exit;
});

router_add('pesquisar_contas', function(){
    $nome_conta = (string) (isset($_REQUEST['nome_conta']))? $_REQUEST['nome_conta'] : '';
    $saldo_conta_string = (string) (isset($_REQUEST['saldo_conta'])? (string) $_REQUEST['saldo_conta']:'');

    $saldo_conta_string = (string) str_replace(',', '.', $saldo_conta_string);
    $saldo_conta = (double) doubleval($saldo_conta_string);

    $filtro = (array) [];

    if($nome_conta == '' && $saldo_conta != 0){
        $filtro = (array) ['saldo_conta', '===', (doubleval($saldo_conta))];
    }else if($nome_conta != '' && $saldo_conta == 0){
        $filtro = (array) ['nome_conta', '=', (string) strtoupper($nome_conta)];
    }else if($nome_conta != '' && $saldo_conta != 0){
        $filtro = (array) ['and' => (array) [(array) ['nome_conta', '=', (string) strtoupper($nome_conta)], (array) ['saldo_conta', '===', (doubleval($saldo_conta))]]];
    }

    $objeto_conta = new Contas();

    echo json_encode((array) ['dados' => (array) $objeto_conta->pesquisar_todos((array) ['filtro' => (array) $filtro, 'ordenacao' => (array) ['nome_conta' => (bool) false], 'limite' => (int) 10])], JSON_UNESCAPED_UNICODE);
    exit;
});
?>