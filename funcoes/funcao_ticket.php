
<?php
require '../controllers/Tickets.php';

//Pego a ocorrência desejada pelo usuario 
$ocorrencia = $_POST['ocorrencia'];
switch ($ocorrencia) {
    //Se for a buscar informacao pego pela api de buscar informacao total onde mostrar o total de arquivo no json e o total de tickets em alta no banco
    case 'buscar_informacao':
        $ch = curl_init();

        //Api usada
        curl_setopt($ch, CURLOPT_URL, "http://localhost/projeto_neoassist/buscarInformacaototal");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, False);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));

        //Resposta do curl em formato json
        $response = curl_exec($ch);
        // $informacao = json_decode($response);

        //Verifico se existe resposta, se existir retorno ela para o javascript usando o echo
        if ($response == False || $response == null){
            echo "Não existe informação de ticket aberto";
        }else{
            echo $response;
        }
    break;

    //Inserindo a informação do json no banco
    case 'inserir_informacao_banco':

        
        $ch = curl_init();
        //Api de uso para inserir no banco
        curl_setopt($ch, CURLOPT_URL, "http://localhost/projeto_neoassist/inseririnformacaobanco");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, False);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));

        //Resposta via curl
        $response = curl_exec($ch);
        //decodificando a informação
        $informacao = json_decode($response);

        if($informacao->Ok == "Ok"){
            echo true;
        }else{
            echo false;
        }


    
    break;

    //Inserindo a prioridade de um ticket no banco
    case 'inserir_prioridade':
        //Variáveis usada para o procedimento
        $codTicket = $_POST['codTicket'];
        $prioridade = $_POST['prioridade'];
        $assunto_prioridade = $_POST['assunto_prioridade'];
        
        //Instâncio a classe
        $ticket = new \controllers\Tickets;
        //Passo para o método inserirPrioridade o codigo do ticket a prioridade e o assunto da prioridade
        $resultado_informacao = $ticket->inserirPrioridade($codTicket, $prioridade, $assunto_prioridade);

        //Se a informação for igual a true , tudo ocorreu bem senão retorno false que é que deu algum problema 
        if($resultado_informacao == true){
            echo true;
        }else{
            echo false;
        }


    break;

    //Gerando o arquivo json com as informações
    case 'gerar json':

        //Variáveis usadas para gerar o procedimento
        $dataInicial = $_POST['data_inicial'];
        $dataFinal = $_POST['data_final'];
        $prioridade = $_POST['prioridade'];    
            
        $ch = curl_init();

        //Crio um array com a informação usada
        $arrayInfo = array(
            "data_inicial" => $dataInicial,
            "data_final" => $dataFinal,
            "prioridade"=> $prioridade
        );
        
        //Coloco ela em um json 
        $informacao_tickets = json_encode($arrayInfo);
        curl_setopt($ch, CURLOPT_URL, "http://localhost/projeto_neoassist/gerarInformacaoParametroJson");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "POST");
        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $informacao_tickets);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));

        //Executo e obtenho a resposta 
        $response = curl_exec($ch);
        //Pego a informação que veio de resposta do json
        $informacao = json_decode($response);

        //Envio para a funco do javascript com o echo
        echo $informacao;

    break;
    
    default:
        # code...
        break;
}

?>