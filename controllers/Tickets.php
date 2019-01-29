<?php

namespace controllers{
	/*
	Classe pessoa
	*/
	class Tickets{
		//Atributo para banco de dados
		private $PDO;

		/*
		__construct
		Conectando ao banco de dados
		*/
		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=tickets_neo', 'root', ''); //Conexão
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
		}
		
		//Inserindo a informação que veio do json para o banco de dados
		public function inserirInformacao($tickets_inserir){
			global $app;

			//Passo o primeiro foreach pegando o ticket e inserindo ele no banco
			foreach ($tickets_inserir as $valor){
				
				$sth = 
				$this->PDO->prepare("INSERT INTO tickets_padrao (cod_ticket, categoria_id, customerId, nome_cliente, email_cliente, data_criacao, data_update) 
				VALUES (".$valor->TicketID.", ".$valor->CategoryID.", ".$valor->CustomerID.", '".$valor->CustomerName."', '".$valor->CustomerEmail."', '".$valor->DateCreate."', '".$valor->DateUpdate."')");
				$sth->execute();
				
				//Ultimo id de ticket inserido, armazendo ele em uma variável para gerar todas as interações que ele teve 
				$codInserido =  $this->PDO->lastInsertId(); 
				
				//Passo o foreach agora a partir das interações , salvando todas no banco também
				foreach($valor->Interactions as $informacaoMensagem){
					$informacao = $informacaoMensagem;
					$sth = null;
					$sth = $this->PDO->prepare("INSERT INTO interacoes (assunto, mensagem, data_criacao, remetente, cod_ticket_padrao_) 
					VALUES ('".$informacaoMensagem->Subject."', '".$informacaoMensagem->Message."', '".$informacaoMensagem->DateCreate."', '".$informacaoMensagem->Sender."', ".$codInserido.")");
					$resultado = $sth->execute();
				}
			}
		
			//Se tudo ocorreu bem envio a informação de retorno passando true = para tudo certo, false = para errado
			if($resultado = true){
				return true;
			}else{
				return false;
			}
		}

		//Busco a informação via parâmetros enviado
		// Data inicio , Data Fim e Prioridade
		public function buscarInformacaoPorParametro($parametros){
			global $app;
			$data_inicio = $parametros['data_inicial'];
			$data_final = $parametros['data_final'];
			$prioridade = $parametros['prioridade'];
			//prioridade for igual a todas mostro todos os tickets independente de prioridade
			if($prioridade == "todas"){
				$sth = 
				$this->PDO->prepare("
					SELECT 
					cod_tickets_padrao, nome_cliente, email_cliente, data_criacao, data_update, prioridade
					FROM 
						tickets_padrao t 
					WHERE 
					 Date(data_criacao) Between :dataInicio AND :dataFinal
					ORDER BY data_criacao, data_update, prioridade  DESC");
				//Seto os valores de dataInicio, dataFinal com bindvalue, usando aqueles que o usuario digitou
				$sth ->bindValue(':dataInicio',$data_inicio);
				$sth ->bindValue(':dataFinal',$data_final);
				
			}else{
				//Se ele não for igual mostro apenas aquele que o usuário pediu
				$sth = 
				$this->PDO->prepare("
					SELECT 
						cod_tickets_padrao, nome_cliente, email_cliente, data_criacao, data_update, prioridade
					FROM 
						tickets_padrao t
					WHERE 
					 Date(t.data_criacao) Between :dataInicio AND :dataFinal AND prioridade = :prioridade_informacao
					 ORDER BY data_criacao, data_update, prioridade  DESC");
				//Seto os valores de dataInicio, dataFinal e prioridade_informacao  com bindvalue, usando aqueles que o usuario digitou
				$sth ->bindValue(':dataInicio',$data_inicio);
				$sth ->bindValue(':dataFinal',$data_final);
				$sth ->bindValue(':prioridade_informacao', $prioridade);
			}

			
			$sth->execute();
			$result_informacao = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result_informacao;
		}

		//Informação para gerar o json do arquivo atualizado de acordo com os parâmetros
		public function gerarInformacaoPorParametroJson($parametros){
			global $app;
			$data_inicio = $parametros['data_inicial'];
			$data_final = $parametros['data_final'];
			$prioridade = $parametros['prioridade'];

			//Segue a mesma lógica do buscarInformacaoPorParametro($parametro)
			if($prioridade == "todas"){
				$sth = 
				$this->PDO->prepare("
					SELECT 
					*
					FROM 
						tickets_padrao 
					WHERE 
					 Date(data_criacao) Between :dataInicio AND :dataFinal
					 GROUP BY cod_tickets_padrao ORDER BY data_criacao, data_update, prioridade DESC ");
				$sth ->bindValue(':dataInicio',$data_inicio);
				$sth ->bindValue(':dataFinal',$data_final);
				
			}else{
				$sth = 
				$this->PDO->prepare("
					SELECT 
						*
					FROM 
						tickets_padrao 
					WHERE 
					 Date(data_criacao) Between :dataInicio AND :dataFinal AND prioridade = :prioridade_informacao
					 GROUP BY cod_tickets_padrao  ORDER BY data_criacao, data_update, prioridade  DESC ");
	
				$sth ->bindValue(':dataInicio',$data_inicio);
				$sth ->bindValue(':dataFinal',$data_final);
				$sth ->bindValue(':prioridade_informacao', $prioridade);
			}

			
			$sth->execute();
			$result_informacao = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result_informacao;
		}
		
		//Buscando os tickets, onde a prioridade dele é alta
		public function buscarInformacaoTicketAlta(){
			$sth = 
			$this->PDO->prepare("
				SELECT 
					COUNT(*) As Contador 
				FROM 
					tickets_padrao 
				WHERE 
					prioridade = 'Alta'
				");
			$sth->execute();
			$result_informacao = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result_informacao;
		}

		//Buscando as interações que o ticket teve, que são as mensagens..., ele buscar de acordo com o código do ticket pedido
		public function buscarInteracaoPorTicket($codTicket){
			$sth = 
				$this->PDO->prepare("
					SELECT 
						* 
					FROM 
						interacoes 
					WHERE 
						cod_ticket_padrao_ = :codigoInteracao
					ORDER BY data_criacao  DESC");
	
			$sth ->bindValue(':codigoInteracao',$codTicket);
			$sth->execute();
			$result_informacao = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result_informacao;
		}

		//Buscar histórico de ticket por cod_ticket
		public function buscarHistoricoPorTicket($cod_ticket){
			$sth = 
			$this->PDO->prepare("
				SELECT 
					* 
				FROM 
					historico_tickets_prioridade 
				WHERE 
					cod_ticket = :codigoTicket
				ORDER BY data_historico  DESC");

			$sth ->bindValue(':codigoTicket',$cod_ticket);
			$sth->execute();
			$result_informacao = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result_informacao;
		}

		//Inserindo a prioridade de um ticket, que aqui traz, o código do ticket, a prioridade do ticket e o assunto da prioridade do ticket
		public function inserirPrioridade($cod_ticket, $prioridade, $assunto_prioridade){
			//vejo qual é a prioridade desse tick 1 - alta senão e Normal
			if($prioridade == 1){
				$prioridade = "Alta";
			}else{
				$prioridade = "Normal";
			}

			//Qual e o assunto que esse ticket teve para gerar a prioridade de alta ou normal
			if($assunto_prioridade == "1_alta"){

				$assunto = "Consumidor insatisfeito com produto ou serviço";

			}else if ($assunto_prioridade == "2_alta"){

				$assunto = "Prazo de resolução do ticket alta";

			}else if ($assunto_prioridade == "3_alta"){

				$assunto = "Consumidor sugere abrir reclamação como exemplo Procon ou ReclameAqui";

			}else if ($assunto_prioridade == "1_normal"){
				$assunto = "Consumidor insatisfeito com produto ou serviço";
			}else{
				$assunto = "Consumidor não demonstra irritação";
			}
			
			//Uso o utf8_decode na informação para nao dar problema e salvar os caracteres todos errados
			$assunto = utf8_decode($assunto);
			//Data de hoje, uso para atualizar o dia que foi atualizado esse ticket
			$data_agora = date('Y-m-d');

			//Faço um update setando nos bindvalue seus devidos valores
			$sth = 
				$this->PDO->prepare(" UPDATE tickets_padrao SET prioridade = :prioridade, causa_prioridade_ticket = :infPrioridade,  data_atualizacao_ticket_prioridade = :data_agora WHERE cod_tickets_padrao = :codpadrao LIMIT 1"); 
					
				$sth ->bindValue(':prioridade', $prioridade);
				$sth ->bindValue(':infPrioridade', $assunto);
				$sth ->bindValue(':data_agora', $data_agora);
				$sth ->bindValue(':codpadrao', $cod_ticket);
				$resultado_informacao = $sth->execute();

			//Se ocorreu tudo bem, salvo um histórico de informação de prioridade , salvando o dia de histórico, código do ticket a prioridade e o assunto
			if ($resultado_informacao == true){
				$sth = $this->PDO->prepare("INSERT INTO historico_tickets_prioridade (data_historico, cod_ticket, prioridade, assunto_prioridade) 
				VALUES ('".$data_agora."', ".$cod_ticket.", '".$prioridade."', '".$assunto."')");
				$resultado = $sth->execute();

				//Se tudo ocorreu bem , retorno true
				if($resultado == true){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	
	}
}