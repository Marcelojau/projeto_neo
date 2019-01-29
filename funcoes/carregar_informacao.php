<?php

    //Variavies usadas para gerar os tickets
    $ocorrencia = $_POST['ocorrencia'];
    $dataInicial = $_POST['data_inicial'];
    $dataFinal = $_POST['data_final'];
    $prioridade = $_POST['prioridade'];    
        
    $ch = curl_init();
    //Api para buscar todos os tickets
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


    $response = curl_exec($ch);
    $informacao = json_decode($response);

    //
    if ($informacao->contador == 0){
        echo "Não existe informação de ticket aberto";
    }else{
        echo $informacao->contador;
    }



?>