<!DOCTYPE html>
<html>

<head>
	<title>Dashboard</title>
	<meta http-equiv=”Content-Type” content=”text/html; charset=iso-8859-1″>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.1.1.js"></script>

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

	

	<!-- style do loader e do botão de circulo -->
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
		.btn-circle {
			width: 30px;
			height: 30px;
			text-align: center;
			padding: 6px 0;
			font-size: 12px;
			line-height: 1.428571429;
			border-radius: 15px;
		}

		.btn-circle.btn-xl {
			width: 70px;
			height: 70px;
			padding: 10px 16px;
			font-size: 24px;
			line-height: 1.33;
			border-radius: 35px;
		}
	</style>
</head>

<body>

	<?php
		include ('menu_informacao.php');
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12 text-center" align="center">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Informação tickets</h3>
					</div>
					
					<div class="panel-body">
						<div class="row" align="center">
							<div class="col-sm-12 col-xs-6 col-sm-6">
								<h3>Tickets no arquivo json</h3>
							
								<br/>
								<div id="loader_informacao" align="center">
									<div class="loader"></div>
									<br/>
									<label for="">Carregando as informações aguarde...</span>

									
								</div>
								<div id="informacao_total" style="display:none">
									<h3><strong><span id="informacao_contador"></span></strong></h3>
								</div>
							</div>
							<div class="col-sm-12 col-xs-6 col-sm-6" >
								<label>Tickets em prioridade alta</label>
								<br/>
								<!-- funcao onclick buscarInformacaoTicket buscar os tickets ja em alta -->
								<button type="button" class="btn btn-danger btn-circle btn-xl" onclick="buscarInformacaoTicket()"><strong><span id="contador_informacao_ticket_alta">...</span></strong></button>

							</div>
						</div>
						
					</div>
					<br/>

					<div class="row">
						<div class="col-sm-12 col-xs-12 col-md-12" align="center">		
							<button type="button" class="btn btn-success btn-flat" onclick="inserindoInformacoes()" id="botao_inserir">Inserir tickets no banco </button>
						</div>
					</div>
					<br/>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="content">
		<div class="container-fluid">
			
		
		</div>
	</div> -->

	<script type="text/javascript" src="js/jquery-3.1.1.js"></script>
	<script>
		$( document ).ready(function() {
			//Funcao para ja começar a buscar os contadores, os totais de arquivo no json e os tickets em alta
			$.post(
				"funcoes/funcao_ticket.php"
				, {
					ocorrencia: "buscar_informacao"
				}
				, function (data) {
					//informacao aonde quebro o json e crio um array
					var informacao = JSON.parse(data);
					//Escondo o loader de informação
					$('#loader_informacao').hide();
					//insiro no informacao_contador a quantidade do contador do json
					$('#informacao_contador').html(informacao.contador);
					//insiro no informacao_contador a quantidade do contador dos tickets em alta
					$('#contador_informacao_ticket_alta').html(informacao.contador_ticket_alto);
					//Mostro a informação
					$('#informacao_total').show();
				}
			);
		});

		function atualizarInformacao (){
			$.post(
				"funcoes/funcao_ticket.php"
				, {
					ocorrencia: "buscar_informacao"
				}
				, function (data) {
					//informacao aonde quebro o json e crio um array
					var informacao = JSON.parse(data);
					//Escondo o loader de informação
					$('#loader_informacao').hide();
					//insiro no informacao_contador a quantidade do contador do json
					$('#informacao_contador').html(informacao.contador);
					//insiro no informacao_contador a quantidade do contador dos tickets em alta
					$('#contador_informacao_ticket_alta').html(informacao.contador_ticket_alto);
					//Mostro a informação
					$('#informacao_total').show();
				}
			);
		}

		function inserindoInformacoes(){
			//Troco a informação do html para inserindo as informações aguarde, para que o usuario veja a informação fluindo
			$('#botao_inserir').html("Inserindo as informações aguarde...");
			$.post(
				"funcoes/funcao_ticket.php"
				, {
					ocorrencia: "inserir_informacao_banco"
				}
				, function (data) {
					//Se o data == true é que tudo deu certo
					if(data == true){
						//Mensagem de ok
						alert("Informação inserida com sucesso");
						//Retorno a informação inserida com sucesso
						$('#botao_inserir').html("Inserido  com sucesso...");
						//Chamo a função atualizar informação para ver o que mudou
						atualizarInformacao();
					}else{
						//Se não deu certo tente denovo
						alert("Erro por favor tente denovo");
						return false;
					}
				}
			);
		}

		
		function buscarInformacaoTicket(){
			//Envio para o tickets_cad um get escrito buscar_informacao_alta para buscar ja diretamente os tickets em alta
			window.location.href = "http://localhost/projeto_neoassist/tickets_cad.php?buscar_informacao_alta";
		}
	</script>
</body>

</html>