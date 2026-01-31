<?php
require_once 'Classes/bancoDeDados.php';
require_once 'Modelos/Dashboard.php';

//@note index
router_add('index', function () {
	verificar_conexao_internet();

	require_once 'includes/head.php';
	$objeto_dashboard = new Dashboard();

	$relatorio_saldo_contas = (array) $objeto_dashboard->relatorio_saldo_conta();

	?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		google.charts.load('current', { 'packages': ['corechart'] });

		function relatorio_saldo_contas(){
			let dados = <?php echo json_encode($relatorio_saldo_contas, JSON_UNESCAPED_UNICODE); ?>;
			var tabela_relatorio = new google.visualization.DataTable();
			tabela_relatorio.addColumn('string', 'NOME');
			tabela_relatorio.addColumn('number', 'SALDO');

			sistema.each(dados, function(contador, informacao){
				if(informacao.nome_conta != ''){
					tabela_relatorio.addRow([informacao.nome_conta, Number(informacao.saldo_conta)]);
				}
			});

			// var opcoes = { width: 1500, height: 100, title: 'Saldo das contas', legend: { position: 'none' }, hAxis: { title: 'Extensão' }, vAxis: { title: 'SALDO CONTAS', minValue: 0 }};
			var opcoes = { width: 1500, height: 400, legend: { position: 'none' }};

			var grafico_tamanho_arquivo = new google.visualization.ColumnChart(document.getElementById('relatorio_saldo_das_contas'));

    		grafico_tamanho_arquivo.draw(tabela_relatorio, opcoes);
		}
	</script>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div id="relatorio_saldo_das_contas"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		window.onload = function () {
			relatorio_saldo_contas();
		}
	</script>
	<?php
	require_once 'includes/footer.php';
	exit;
});
?>