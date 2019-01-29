<!DOCTYPE html>
<html>
<?php
	if(isset($_REQUEST['buscar_informacao_alta'])){
		$informacao_busca_alta = 1;
	}else{
		$informacao_busca_alta = 0;
	}

?>
<head>
	<title>Tickets cadastrados</title>
	<meta http-equiv=”Content-Type” content=”text/html; charset=iso-8859-1″>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.1.1.js"></script>

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
 	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
 

	
	<style>
		.loader {
			border: 16px solid #f3f3f3; /* Light grey */
			border-top: 16px solid #3498db; /* Blue */
			border-radius: 50%;
			width: 120px;
			height: 120px;
		animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
</head>

<body>

	<?php
		include ('menu_informacao.php');
	?>
	
	<input type="hidden" name="buscar_prioridade_alta" id="buscar_prioridade_alta" class="form-control" value="<?= $informacao_busca_alta ?>">
	
	<div class="container">
		<div class="row" align ="center">
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="card " style="background-color: #ff6666;" >
					<div class="card-body">
						<br/>
						<h5 class="card-title"><strong>Ticket em cor vermelha</strong></h5>
						<p class="card-text">Quando o ticket está em cor vermelha, ele apresenta que está em prioridade alta, precisa de maior atenção para ser resolvido</p>
						<br/>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="card " style="background-color: #ffff4d;">
					<div class="card-body">
						<br/>
						<h5 class="card-title"><strong>Ticket em cor amarela</strong></h5>
						<p class="card-text">Quando o ticket está em cor amarela, ele apresenta que está em prioridade normal, precisa de atenção, mas pode esperar</p>
						<br/>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12 text-center" align="center">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Informação tickets</h3>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<h3>Tickets cadastrados</h3>
						</div>
						<br/>
                        <div class="row">
                            <div class="col-sm-12 col-xs-4 col-lg-4 col-md-4">
                                <label for="">Data inicial</label>
								
								<input type="date" name="data_inicial" id="data_inicial" class="form-control" value="<?= date('Y-m-01') ?>"  title="">
								
                            </div>
                            <div class="col-sm-12 col-xs-4 col-lg-4 col-md-4">
                                <label for="">Data final</label>
								<input type="date" name="data_final" id="data_final" class="form-control" value="<?= date('Y-m-t') ?>"  title="">
                            </div>
                            <div class="col-sm-12 col-xs-4 col-lg-4 col-md-4">
                                <label for="">Selecione a prioridade</label>
								
								<select name="buscar_prioriedade" id="buscar_prioridade" class="form-control" >
                                <label for="">Selecione a prioridade</label>
									<option value="todas" selected>Todas as prioridades</option>
									<option value="alta" id="alta_select">Alta</option>
									<option value="baixa">Baixa</option>
								</select>
								
                            </div>
                        </div>
						<br/>
						<div class="row" align="left">
							
							<div class="col-sm-12 col-xs-4 col-lg-4">
								<button type="button" class="btn btn-lg btn-success" onclick="buscandoInformacao()" id="buscar_informacao">Buscar informação</button>
							</div>
							
							<div class="col-sm-12 col-xs-4 col-lg-4 " style="display:none" id="gerar_json">
								<button type="button" class="btn btn-lg btn-info" onclick="gerarArquivoJson()" id="btn_arquivo_json">Gerar arquivo json</button>
							</div>

							<div class="col-sm-12 col-xs-4 col-lg-4 " style="display:none" id="download_json">
								<a class="btn  btn-lg btn-success" href="" role="button" download="Meu_arquivo_neo">Download do arquivo</a>								
							</div>
						</div>
                        <br/>
						<div id="loader_informacao" align="center" style="display:none">
							<div class="loader"></div>
							<br/>
							<label for="">Carregando as informações aguarde...</span>
						</div>
						
						<div id="informacao_total" style="display:none">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-interacoes">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Interações feitas</h4>
				</div>
				<div class="modal-body">
					<div id="loader_informacao_interacoes" align="center" >
						<div class="loader"></div>
						<br/>
						<label for="">Carregando as informações aguarde...</span>
					</div>
					<div id="tabela_interacoes">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-primary">Salvar informação</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-prioridade-cad">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Cadastrar prioridade</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-xs-12 col-lg-12">
							<label for="">Selecione uma prioridade</label>
							<select name="informacao_prioriedade_ticket" id="informacao_prioriedade_ticket" Onchange="selectTicket(this.value)" class="form-control" >
								<option value="selecione" id="selecione">Selecione...</option>
								<option value="1" id="alta">Alta</option>
								<option value="2" id="normal">Normal</option>
							</select>		
						</div>
						<br/>
						<div class="col-sm-12 col-xs-12 col-lg-12" style="display:none" id="prioridadeAlta">
							<label for="">Selecione a prioridade alta</label>
							<select name="assunto_prioridade_alta" id="assunto_prioridade_alta" class="form-control" >
								<option value="selecione" id="selecione_alta">Selecione...</option>
								<option value="1_alta">Consumidor insatisfeito com produto ou serviço</option>
								<option value="2_alta">Prazo de resolução do ticket alta</option>
								<option value="3_alta">Consumidor sugere abrir reclamação como exemplo Procon ou ReclameAqui</option>
							</select>		
						</div>
						<div class="col-sm-12 col-xs-12 col-lg-12" style="display:none" id="prioridadeNormal">
							<label for="">Selecione a Prioridade normal</label>
							<select name="assunto_prioridade_normal" id="assunto_prioridade_normal"  class="form-control" >
								<option value="selecione" id="selecione_baixa">Selecione...</option>
								<option value="1_normal">Consumidor insatisfeito com produto ou serviço</option>
								<option value="2_normal">Consumidor não demonstra irritação</option>
							</select>		
						</div>
						
						<input type="hidden" name="cod_ticket" id="cod_ticket" class="form-control" >
						
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-primary" onclick="salvarInformacao()" id="btnSalvarInfo">Salvar informação</button>
				</div>
			</div>
		</div>
	</div>

	
	<div class="modal fade" id="modal-historico">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					<div id="loader_informacao_historico" align="center" >
						<div class="loader"></div>
						<br/>
						<label for="">Carregando as informações aguarde...</span>
					</div>
					<div id="tabela_historico">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	

	<script>
		$( document ).ready(function() {

			//Verifico primeiro se nao veio pela tela_principal onde você clica no botão de tickets em alta
			var buscar_informacao_prioridade_alta = $('#buscar_prioridade_alta').val();
			
			//Se a prioridade estiver 1 eu sei que vem de la
			if(buscar_informacao_prioridade_alta == 1 ){
				
				//Mostro um alert, mostrando que a consulta será feita pelos tickets em alta
				$.alert("Percebi que voce clicou no botão na tela principal, e quer filtrar pelos tickets que estão em alta. \n Estamos carregando a informação pra você");
				
				//Coloco o select do em alta como selected
				document.getElementById("alta_select").selected = "true";

				//Troco o html do botão para buscando as informações
				$('#buscar_informacao').html("Buscando as informações...");
				
				//Pego os parâmetros e ja seto a prioridade para alta
				var data_inicial = $('#data_inicial').val(); 
				var data_final =  $('#data_final').val();
				var prioridade = "alta";

				//Escondo o informação total
				$('#informacao_total').hide();

				//Mostro o loader 
				$('#loader_informacao').show();

				$.post(
					"funcoes/buscar_informacao_tickets_parametros.php"
					, {
						data_inicial: data_inicial.toString(),
						data_final: data_final.toString(),
						prioridade: prioridade.toString()
					}
					, function (data) {

						//Seto o botão denovo para buscar informações
						$('#buscar_informacao').html("Buscar informação");
						
						//Coloco o retorno em informacao_total
						$('#informacao_total').html(data);

						//Escondo o loader
						$('#loader_informacao').hide();

						//Mostro o botão para gerar o json
						$('#gerar_json').show();

						//Insiro um datatables onde ele faz a paginação
						var table = $('#tabela_ticket').dataTable();

						//Faço a ordenação por data inicio, data de update e prioridade 3,4,5
						table.fnSort([[3,'asc'], [4,'asc'],[5,'desc']]);

						//Mostro a tabela com as informações
						$('#informacao_total').show();
					}
				);
			}else{
				return false;
			}
		});

		function buscandoInformacao(){

			//Troco o html do botão para buscando as informações
			$('#buscar_informacao').html("Buscando as informações...");

			//Pego os parâmetros 
			var data_inicial = $('#data_inicial').val(); 
			var data_final =  $('#data_final').val();
			var prioridade = $('#buscar_prioridade').val();

			//Escondo o informação total
			$('#informacao_total').hide();

			//Escondo o botão gerar json
			$('#gerar_json').hide();

			//Escondo o botão de download do json
			$('#download_json').hide();

			//Mostro o loader 
			$('#loader_informacao').show();

			$.post(
				"funcoes/buscar_informacao_tickets_parametros.php"
				, {
					data_inicial: data_inicial.toString(),
					data_final: data_final.toString(),
					prioridade: prioridade.toString()
				}
				, function (data) {

					//Volto o botão para buscar informação
					$('#buscar_informacao').html("Buscar informação");

					//Insiro as informações do data no informacao_total
					$('#informacao_total').html(data);
					
					//Mostro o botão para gerar o json
					$('#gerar_json').show();

					//Sumo com o loader 
					$('#loader_informacao').hide();

					//Insiro o datatables, que ja faz vários processos
					var table = $('#tabela_ticket').dataTable();

					//Ordeno por data criação, data update e prioridade 3,4,5
					table.fnSort([[3,'asc'], [4,'asc'],[5,'desc']]);

					//Mostro a informação
					$('#informacao_total').show();
				}
			);
		}

		//Aqui recebo três parametros o codigo do ticket, data inicial e o data final
		function informacaoInteracaoPorTicket(cod_ticket, data_ini, data_fim){
			
			//Sumo com a tabela 
			$('#tabela_interacoes').hide();

			//Mostro o loader
			$('#loader_informacao_interacoes').show();

			//Abro o modal
			$('#modal-interacoes').modal('show');

			$.post(
				"funcoes/buscar_informacao_interacoes_ticket.php"
				, {
					codigo_ticket: cod_ticket.toString(),
					dataInicial : data_ini,
					dataFinal: data_fim
				}
				, function (data) {

					//Carrego as informações para o tabela_interacoes do data
					$('#tabela_interacoes').html(data);

					//Sumo com o loader de interações
					$('#loader_informacao_interacoes').hide();

					//Mostro a tabela
					$('#tabela_interacoes').show();
				}
			);
		}

		//Pego o ticket selecionado prioridade
		function selectTicket(selecionado){

			//se for igual a selecione escondo a prioridade alta e normal
			if(selecionado == "selecione"){
				
				$('#prioridadeAlta').hide();
				$('#prioridadeNormal').hide();
				return false;

			}
			//se for igual a 1 mostro a alta e escondo a normal
			else if (selecionado == 1){

				$('#prioridadeNormal').hide();
				$('#prioridadeAlta').show();

			}
			//se naõ for nenhume nem outro escondo a alta e mostro a normal
			else{

				$('#prioridadeAlta').hide();
				$('#prioridadeNormal').show();
			}
		}

		//Nssa função coloco para a chamada de inserir prioridade onde recebo o codticket, e cod_prioridade pode ser null caso nao venha do modal de interações
		function inserirPrioridade(codTicket, cod_prioridade = null){
			
			//Se o cod_prioridade for igual a nulo, sei que ele veio pela tabela e pelo botão, inserir prioridade
			if (cod_prioridade == null){

				//Coloco no input hidden cod_ticket o codigo do ticket via o val
				$('#cod_ticket').val(codTicket);

				$('#prioridadeAlta').hide();
				$('#prioridadeNormal').hide();


				//Deixo o selected de selecione como true
				document.getElementById("selecione").selected = "true";

				document.getElementById("selecione_alta").selected = "true";

				document.getElementById("selecione_baixa").selected = "true";

				$('#btnSalvarInfo').html("Salvar informação");

				//Mostro o modal para inserir as informações
				$('#modal-prioridade-cad').modal('show');

			}
			//Se ele vier igual a 1 sei que ele veio do modal de interações e tambem sei que o usuario, tem prioridade para a prioridade alta
			else if (cod_prioridade == 1) {

				//Deixo o selected de alta como true
				document.getElementById("alta").selected = "true";

				document.getElementById("selecione_alta").selected = "true";
				
				//Escondo a prioridade normal
				$('#prioridadeNormal').hide();

				//Mostro a prioridade alta
				$('#prioridadeAlta').show();

				//Gero um alert para falar mais da informação
				$.alert("Verificamos que você veio pela de interações de mensagens, e pelo que vimos a probabilidade de você escolhe a prioridade alta é grande pelos fatores que o ticket se encontra \n <strong>Deixamos como prioridade no select a Alta</strong>");

				$('#btnSalvarInfo').html("Salvar informação");

				//Abro o modal para inserir a informação
				$('#modal-prioridade-cad').modal('show');

			}
			//Se ele vier igual a 2 sei que ele veio do modal de interações e tambem sei que o usuario, tem prioridade para a prioridade manual
			else if (cod_priordade == 2){

				//Deixo o selected de normal como true
				document.getElementById("normal").selected = "true";
				
				document.getElementById("selecione_baixa").selected = "true";

				//Escondo a prioridade alta
				$('#prioridadeAlta').hide();
				
				//Mostro a prioridade normal
				$('#prioridadeNormal').show();
				
				//Gero um alert para falar mais da informação
				$.alert("Verificamos que você veio pela de interações de mensagens, e pelo que vimos a probabilidade de você escolhe a prioridade normal é grande pelos fatores que o ticket se encontra \n <strong>Deixamos como prioridade no select a Normal</strong>");
				
				$('#btnSalvarInfo').html("Salvar informação");
				
				//Abro o modal para inserir a informação
				$('#modal-prioridade-cad').modal('show');
				
			}
		}


		function salvarInformacao(){

			//Verifico se for salvar a informação se a prioridade está selecionada
			if ($('#informacao_prioriedade_ticket').val() == "selecione"){

				//Se nao estiver gero um alert e retorno false para não continuar
				$.alert("Selecione uma prioridade");
				return false;
			}

			//Verifico se for salvar a informação se a prioridade está selecionada a alta
			if($('#informacao_prioriedade_ticket').val() == "1"){

				//Se estiver a prioridade alta, vejo se o assunto da prioridade alta está selecionado
				if ($('#assunto_prioridade_alta').val() == "selecione"){

					//Se nao estiver gero um alert e retorno false para não continuar
					$.alert("Selecione o porque");
					return false;
				}else{

					//Se estiver com valor armazeno na variavel prioridade
					var prioridade_informacao = $('#assunto_prioridade_alta').val();
				}
			}else{

				//Se estiver a prioridade normal, vejo se o assunto da prioridade alta está selecionado
				if ($('#assunto_prioridade_normal').val() == "selecione"){
					
					//Se nao estiver gero um alert e retorno false para não continuar
					$.alert("Selecione o porque");
					return false;

				}else{

					//Se estiver com valor armazeno na variavel prioridade
					var prioridade_informacao = $('#assunto_prioridade_normal').val();
				}
			}
			
			//Troco o html do botão para salvando a informação
			$('#btnSalvarInfo').html("Salvando a informação... aguarde");

			//Armazendo as informações nas variaveis
			var codTicket = $('#cod_ticket').val();
			var prioridade = $('#informacao_prioriedade_ticket').val();
			var assunto_prioridade = prioridade_informacao;
			

			$.post(
				"funcoes/funcao_ticket.php"
				, {
					ocorrencia: "inserir_prioridade",
					codTicket: codTicket.toString(),
					prioridade : prioridade.toString(),
					assunto_prioridade: assunto_prioridade.toString()
				}
				, function (data) {

					//Se o alert for igual a true deu tudo certo
					if(data == true){
						
						//Gero uma mensagem para mostrar que foi atualizado
						$.alert('Informação atualizada com sucesso');

						$('#modal-prioridade-cad').modal('hide');
						//Vejo se a prioridade for igual a 1
						if(prioridade == 1){

							//Pego a linha da tabela que é constituida de ticket + o codigo do ticket
							var element = document.getElementById('ticket'+codTicket);

							//Seto a cor vermelha
							element.style.background = '#ff6666';
	
						}else{

							//Pego a linha da tabela que é constituida de ticket + o codigo do ticket
							var element = document.getElementById('ticket'+codTicket);

							//Seto a cor amarela
							element.style.background = '#ffff4d';
		
						}
					}else{

						//Se der tudo errado mostro uma informação, de tentar denovo ou entrar em contato com o suporte
						$.alert("Erro ao editar a informação, por favor tente denovo ou entre em contato com o suporte");
					}
				}
			);

		}

		function gerarArquivoJson(){

			//Parâmetros usados para gerar o arquivo json
			var data_inicial = $('#data_inicial').val(); 
			var data_final =  $('#data_final').val();
			var prioridade = $('#buscar_prioridade').val();

			//Troco o html do botão para gerando arquivo json
			$('#btn_arquivo_json').html("Gerando arquivo json");

			$.post(
				"funcoes/funcao_ticket.php"
				, {
					ocorrencia: "gerar json",
					data_inicial: data_inicial.toString(),
					data_final: data_final.toString(),
					prioridade: prioridade.toString()
				}
				, function (data) {
					//Verifico se o data é diferente de false e diferente de null
					if(data != false && data != null){

						//Mostro uma mensagem de sucesso
						$.alert('O Arquivo foi criado com sucesso');
						
						//Mostro o botão de download da informação
						$('#download_json').show();

						//Coloco o caminho do json dentro do href do a
						$("a").prop('href', data);
					}else{

						//Se não deu certo, mostro uma mensagem de por favor tente denovo ou entre em contato 
						$.alert("Erro ao criar arquivo, por favor tente denovo ou entre em contato com o suporte");
					}
				}
			);
		}

		//Função que busca o histórico do ticket
		function buscarHistorico(codTicket){
			
			//Sumo com a div tabela
			$('#tabela_historico').hide();

			//Mostro o loader
			$('#loader_informacao_historico').show();

			//Mostro o histórico
			$('#modal-historico').modal('show');

			$.post(
				"funcoes/buscar_historico.php"
				, {
					codigo_ticket: codTicket,
					
				}
				, function (data) {

					//Escondo o loader
					$('#loader_informacao_historico').hide();

					//Coloco o html no tabela_historico
					$('#tabela_historico').html(data);

					//Mostro a tabela
					$('#tabela_historico').show();
					
				}
			);
		}

	
	</script> 
</body>

</html>