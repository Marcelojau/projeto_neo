<?php

    //Recebo os parâmestro que são enviados via ajax por post
    $dataInicial = $_POST['data_inicial'];
    $dataFinal = $_POST['data_final'];
    $prioridade = $_POST['prioridade'];    
        
    $ch = curl_init();

    //Armazendo em um array para depois , gerar um json deles e enviar para a api
	$arrayInfo = array(
        "data_inicial" => $dataInicial,
        "data_final" => $dataFinal,
        "prioridade"=> $prioridade
    );
    
    //armazendo a informação, já em json para enviar por post via api
    $informacao_tickets = json_encode($arrayInfo);

    //Envio a informação dos parâmetros via api 
    curl_setopt($ch, CURLOPT_URL, "http://localhost/projeto_neoassist/buscarInformacaoParametro");
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

    //Recebo a resposta
    $response = curl_exec($ch);
    //Uso o json_decode para decodificar a informação e ela me criar um array 
    $informacao = json_decode($response);
?>


<div class="table-responsive">
    <table class="table table-hover" id="tabela_ticket">
        <thead>
            <tr>
                <th><center> Código ticket </center></th>
                <th><center> Nome do cliente </center></th>
                <th><center> Email  </center></th>
                <th><center> Data criação </center></th>
                <th><center> Ultima atualização </center></th>
                <th><center> Prioridade </center></th>
                <th><center> Ação </center></th>
            </tr>
        </thead>
        <tbody>
            <?php
                 foreach($informacao as $informacoes_tickets){
                   
                    //Verifico se a prioridade é alta se for alta a cor fica em um vermelho senão for fica em amarelo, e se não tem nada cadastrado continuar em branco
                    if ($informacoes_tickets->prioridade == "Alta"){
                        $classe = "background-color: #ff6666;";
                    }else if ($informacoes_tickets->prioridade == "Normal"){
                        $classe = "background-color: #ffff4d;";
                    }else{
                        $classe = null;
                    }
              
            ?>
            <!-- Armazeno na tr o id do ticket + codigo do ticket para depois eu fazer a atualização automaticamente da cor que será usada de acordo com a prioridade-->
            <tr id="ticket<?= $informacoes_tickets->cod_tickets_padrao ?>" style="<?= $classe ?>">
                <td><center><?= $informacoes_tickets->cod_tickets_padrao ?> </center></td>
                <td><center><?= $informacoes_tickets->nome_cliente ?> </center></td>
                <td><center><?= $informacoes_tickets->email_cliente ?> </center></td>
                <td><center><?= date('d/m/Y', strtotime($informacoes_tickets->data_criacao)) ?> </center></td>
                <td><center><?= date('d/m/Y', strtotime($informacoes_tickets->data_update)) ?> </center></td>
                <td><center><?= isset($informacoes_tickets->prioridade)? $informacoes_tickets->prioridade : "-"  ?> </center></td>
                <td>
                    <center>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
                            <!-- Busco todas as interações que esse ticket tv, enviando a ele o codigo de ticket padrão-->  
                            <button type="button" class="btn btn-primary btn-flat" onclick="informacaoInteracaoPorTicket('<?= $informacoes_tickets->cod_tickets_padrao?>', '<?= $informacoes_tickets->data_criacao ?>', '<?= $informacoes_tickets->data_update ?>')">Verificar interações</button>
                        </div>
                        
                        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
                            <br/>
                            <!-- Envio para o inserirPrioridade o código do ticket padrão para ser atualizado -->     
                            <button type="button" class="btn btn-danger btn-flat" onclick="inserirPrioridade('<?= $informacoes_tickets->cod_tickets_padrao?>')">Selecionar prioridade</button>
                        </div>

                        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
                            <br/>
                            <!-- Envio para o buscarHistorico o código do ticket padrão para ser procurado no historico -->     
                            <button type="button" class="btn btn-success btn-flat" onclick="buscarHistorico('<?= $informacoes_tickets->cod_tickets_padrao?>')" >Buscar histórico</button>
                        </div>
                    </div>
                    </center>
                </td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>   
</div>
