<?php
require 'controllers/Tickets.php';
//Autoload
$loader = require 'vendor/autoload.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//Instanciando objeto
$app = new Slim\App(array(
    'templates.path' => 'templates'
));


//Api de buscar a informação de quantos registros tem no json, e de quantos tickets tem em alta
$app->get("/buscarInformacaototal", function() use($app){
	
	//Nome do arquivo
	$arquivo = "json/tickets.json";

	//Pegando as informações do arquivo
	$info = file_get_contents($arquivo);
	
	//Decodificando a informação
	$response = json_decode($info);

	//Sabendo quantos registros tem
	$contador = count($response);

	//Instânciando a classe
	$tickets = new \controllers\Tickets;

	//Chamando o metodo buscarInformacaoTicketAlta
	$retorno_informacao = $tickets->buscarInformacaoTicketAlta();


	//Colocando no array o contador de registros do json e o contador de registro com tickets em prioridade alta
	$arrayInfo = array(
		"contador" => $contador,
		"contador_ticket_alto"=> $retorno_informacao[0]['Contador'],
	);

	//Transformando em json 
	$arrayInfo =  json_encode($arrayInfo);
	
	//Retornando a informação
	return $arrayInfo;
	
});


//Url usada para inserirInformacao No banco via o json tickets
$app->get("/inseririnformacaobanco", function() use($app){
	
	//Nome do arquivo
	$arquivo = "json/tickets.json";

	//Pegando as informações
	$info = file_get_contents($arquivo);
	
	//Decodificando elas
	$response = json_decode($info);
	
	//Instânciando a classe
	$tickets = new \controllers\Tickets;

	//Chamando a funcao inserirInformacao passando para ela o json com as informações
	$retorno_informacao = $tickets->inserirInformacao($response);

	if($retorno_informacao == true){
		
		//Colocando no array se deu tudo certo
		$arrayInfo = array(
			"Ok" => "Ok",
		);

	}else{
		$arrayInfo = array(
			"Ok" => "Nao",
		);	
	}
	//Transformando em json 
	$arrayInfo =  json_encode($arrayInfo);
			
	//Retornando a informação
	return $arrayInfo;
	
});

//Api de busca de informações por parâmetro
$app->post('/buscarInformacaoParametro',function (Request $request, Response $response, array $args )  {
	//Pegando as informações enviadas
	$informacao  =  $request->getParsedBody();
	
	//Instânciando a classe
	$tickets = new \controllers\Tickets;

	//Cahamda da função de buscarInformaçãoPorParametro enviando a ele os parâmetros requisitados para a consulta
	$retorno_informacao = $tickets->buscarInformacaoPorParametro($informacao);
	
	//Gerando o json
	$json_informacao = json_encode($retorno_informacao);

	//Retornando o valor 
	return $json_informacao;

});

//Api de gerar as informações em json
$app->post('/gerarInformacaoParametroJson',function (Request $request, Response $response, array $args )  {
	//Pegando os parâmetros usados
	$informacao  =  $request->getParsedBody();

	//Instânciado a classe
	$tickets = new \controllers\Tickets;

	//Chamando o metodo gerarInformacaoPorParametroJson passando para ele os parâmetros
	$retorno_informacao = $tickets->gerarInformacaoPorParametroJson($informacao);
	
	//Informações de array usadas dentro do foreach
	$json_string= array();
	$informacao_array = array();
	$informacao_interacao = array();
	$json_string_array = array();
	$result_ticket = array();
	$result_interacao = array();
	$array_final = array();

	//passando o primeiro foreach em tickets
	foreach ( $retorno_informacao as $informacao )
	{
		//Armazenando em um json_string
		$json_string
			= array("codigo_ticket"=> $informacao['cod_tickets_padrao'],
				"categoria_id"=> $informacao['categoria_id'],
				"customer_id"=> $informacao['customerId'],
				"nome_cliente"=> $informacao['nome_cliente'],
				"email_cliente"=> $informacao['email_cliente'],	
				"data_criacao"=> $informacao['data_criacao'],	
				"data_update"=> $informacao['data_update'],	
				"prioridade"=> $informacao['prioridade'],	
				"data_atualizacao_ticket_prioridade"=> $informacao['data_atualizacao_ticket_prioridade'],	
				"causa_prioridade_ticket"=> utf8_encode($informacao['causa_prioridade_ticket']),	
		); 

		//Colocando a informação em um outro array chamado informacao_array
		array_push($informacao_array, $json_string);

		//Instânciando a classe
		$tickets = new \controllers\Tickets;

		//Chamada da função de buscarInteracaoPorTicket passando o codigo do ticket
		$retorno_informacao_interacao = $tickets->buscarInteracaoPorTicket($informacao['cod_tickets_padrao']);
		
		//Segundo for each para fazer um array com as interações do ticket
		foreach ( $retorno_informacao_interacao as $interacao ){
			$json_string_array = array(
				"codigo_interacao"=> $interacao['cod_interacao'],
				"codigo_ticket"=>$interacao['cod_ticket_padrao_'],
				"assunto"=> $interacao['assunto'],
				"mensagem"=> $interacao['mensagem'],
				"data_criacao"=> $interacao['data_criacao'],
				"remetente"=> $interacao['remetente'],
			);
			 
			//Colocando a informação em um outro array chamado informacao_interacao
			array_push($informacao_interacao, $json_string_array);
			
		}
		//Colocando um nome para os tickets e recebendo o array de ticket
		$dados['Tickets'] = $informacao_array;
		//Colocando um nome para as Interações e colocando o array das interações
		$dados['Interacoes'] = $informacao_interacao;

		//Armazenando a informação em array final que é o que sera usada para gerar o arquivo json
		array_push($array_final, $dados);

		//Setos as informações como um array novo para gerar os registros e começar denovo
		$informacao_array = array();
		$informacao_interacao = array();
		
	
	}

	//Pego o caminho do arquivo json 
	$caminho_json = "json/arquivo_neo.json";

	//Abro o arquivo
	$fp = fopen($caminho_json, 'w');

	//Copio as informações para ele do array
	fwrite($fp, json_encode($array_final));

	//Fexo o arquivo
	fclose($fp);

	//Guardo o caminho eu um array
	$arrayInfo = array(
		"caminho_json" => $caminho_json,
	);

	//Gero o json
	$json_caminho = json_encode($caminho_json);

	//Retorno o json codificado
	return $json_caminho;

});


function buscarParametro(){
	$inserirInformacao = new \controllers\Tickets;
	$retorno_informacao = $inserirInformacao->buscarParametro();
}


$app->run();




