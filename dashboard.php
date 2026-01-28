<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Modelos/Dashboard.php';

//@note index
router_add('index', function () {
	verificar_conexao_internet();

	require_once 'includes/head.php';
	$objeto_dashboard = new Dashboard();

	// $cidade = (string) '';
	// $temperatura = (string) '';
	// $descricao = (string) '';
	// $retorno_previsao = (array) $objeto_clima->pesquisar_previsao();

	// $objeto_documento->excluir_relatorio_antigo();

	// $relatorio_quantidade_documento_por_tipo_arquivo = (array) $objeto_dashboard->relatorio_quantidade_documentos_por_tipo_arquivo();
	// $relatorio_tamanho_total_arquivos_cadastrados = (array) $objeto_dashboard->relatorio_tamanho_arquivos();

	// if (array_key_exists('results', $retorno_previsao) == true) {
	// 	if (array_key_exists('city', $retorno_previsao['results']) == true) {
	// 		$cidade = (string) $retorno_previsao['results']['city'];
	// 	}

	// 	if (array_key_exists('temp', $retorno_previsao['results']) == true) {
	// 		$temperatura = (string) $retorno_previsao['results']['temp'];
	// 	}

	// 	if (array_key_exists('description', $retorno_previsao['results']) == true) {
	// 		$descricao = (string) $retorno_previsao['results']['description'];
	// 	}
	// }

	// $objeto_preferencia = new Preferencia();

    // $preferencia_usuario_quantidade_documentos_por_extensao = (string) 'CHECKED';
    // $preferencia_usuario_tamanho_total_arquivos = (string) 'CHECKED';

    //MONTANDO FILTRO DE PESQUISA PARA SABER SE O USUÁRIO LOGADO NO SISTEMA PREFERE VER O RELATÓRIO DE QUANTIDADE DE DOCUMENTOS POR TIPO DE ARQUIVO
    // $filtro_pesquisa = (array) ['and' => (array) [['id_sistema', '===', (int) intval(CODIGO_SISTEMA, 10)], ['id_usuario', '===', (int) intval(CODIGO_USUARIO, 10)], ['nome_preferencia', '===', (string) 'USUARIO_PREFERENCIA_RELATORIO_QUANTIDADE_DOCUMENTOS_POR_EXTENSAO']]];
    // $retorno_pesquisa_preferencia = (array) $objeto_preferencia->pesquisar((array) ['filtro' => (array) $filtro_pesquisa]);

    // if(empty($retorno_pesquisa_preferencia) == true){
    //     $preferencia_usuario_quantidade_documentos_por_extensao = (string) '';
    // }

    // //MONTANDO FILTRO DE PESQUISA PARA SABER SE O USUÁRIO LOGADO NO SISTEMA PREFERE VER O RELATÓRIO DE TAMANHO TOTAL DE ARQUIVOS CADASTRADOS NO SISTEMA

    // $filtro_pesquisa = (array) ['and' => (array) [['id_sistema', '===', (int) CODIGO_SISTEMA], ['id_usuario', '===', (int) CODIGO_USUARIO], ['nome_preferencia', '===', (string) 'USUARIO_PREFRENCIA_RELATORIO_TAMANHO_TOTAL_ARQUIVO']]];
    // $retorno_pesquisa_preferencia = (array) $objeto_preferencia->pesquisar((array) ['filtro' => (array) $filtro_pesquisa]);

    // if(empty($retorno_pesquisa_preferencia) == true){
    //     $preferencia_usuario_tamanho_total_arquivos = (string) '';
    // }
	?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		google.charts.load('current', { 'packages': ['corechart'] });

		/** 
		 * Função responsável por montar de forma visual o relatório de quantidade de documentos cadastrados no sistema por tipo de arquivo
		*/
		function relatorio_quantidade_documentos_por_tipo_arquivo() {
			let dados = <?php echo json_encode($relatorio_quantidade_documento_por_tipo_arquivo, JSON_UNESCAPED_UNICODE); ?>;
			var tabela = new google.visualization.DataTable();

			tabela.addColumn('string', 'EXTENSÃO');
			tabela.addColumn('number', 'QUANTIDADE DOCUMENTO');

			var opcoes = {
				'height': 200,
				'width': 450
			};

			sistema.each(dados, function (contador, informacao) {
				tabela.addRow([informacao.tipo_arquivo, informacao.quantidade_documentos]);
			});

			var grafico = new google.visualization.PieChart(document.getElementById('relatorio_quantidade_documento_por_tipo_arquivo'));
			grafico.draw(tabela, opcoes);
		}

		/** 
		 * Função responsável por calcular e mostrar a quantidade total de documentos cadastrados no banco de dados
		*/
		function relatorio_tamanho_total_documentos_cadastrados(){
			let dados = <?php echo json_encode($relatorio_tamanho_total_arquivos_cadastrados, JSON_UNESCAPED_UNICODE); ?>;
			var tabela_relatorio = new google.visualization.DataTable();

			tabela_relatorio.addColumn('string', 'EXTENSÃO');
			tabela_relatorio.addColumn('number', 'TAMANHO EM MEGAS');

			var opcoes = {'height': 200, 'width': 450};
			sistema.each(dados, function(contador, informacao){
				if(informacao.id_tipo_arquivo != 0){
					tabela_relatorio.addRow([informacao.tipo_arquivo, informacao.tamanho_arquivo]);
				}
			});

			var grafico_tamanho_arquivo = new google.visualization.PieChart(document.getElementById('relatorio_tamanho_total_arquivos_cadastrados'));
			grafico_tamanho_arquivo.draw(tabela_relatorio, opcoes);
		}

		google.charts.setOnLoadCallback(relatorio_quantidade_documentos_por_tipo_arquivo);
		google.charts.setOnLoadCallback(relatorio_tamanho_total_documentos_cadastrados);
	</script>
	<div class="container-fluid">
		
	</div>
	<script>
		window.onload = function () {

		}
	</script>
	<?php
	require_once 'includes/footer.php';
	exit;
});
?>