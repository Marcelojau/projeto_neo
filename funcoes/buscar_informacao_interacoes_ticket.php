<?php
   require '../controllers/Tickets.php';
   date_default_timezone_set('America/Sao_Paulo');
  
   //Código do ticket enviado 
   $codTicket = $_POST['codigo_ticket'];

   //Data inicial e data final do filtro usado para trazer os tickets
   $dataInicial = date('Y-m-d', strtotime($_POST['dataInicial']));
   $dataFinal = date('Y-m-d', strtotime($_POST['dataFinal']));

   //Gero os datetime de cada um para pegar a diferença de dias das datas, para saber quanto tempo esse ticket está sendo usado
   $data1 = new DateTime( $dataFinal );
   $data2 = new DateTime( $dataInicial );

   //Pego o intervalo de dias das datas
   $intervalo = $data1->diff( $data2 );
   //Armazendo na variável para ser usada depois
   $dias_intervalo = $intervalo->d;

   //Instâncio a classe
   $ticket = new \controllers\Tickets;

   //Chamo o método buscarInteracaoPorTicket, passando para ele o código do ticket selecionado
   $resultado_informacao = $ticket->buscarInteracaoPorTicket($codTicket);
?>


<div class="table-responsive">
    <table class="table table-hover" >
        <thead>
            <tr>
                <th><center> Assunto </center></th>
                <th><center> Mensagem </center></th>
                <th><center> Data criacao  </center></th>
                <th><center> Remetente </center></th>
            </tr>
        </thead>
        <tbody>
            <?php
                //$resultadoCustomer e $resultadoExpert , sao variáveis de contadores , para saber quantas vezes foi para customer e quantas foram para expert
                 $resultadoCustomer = 0;
                 $resultadoExpert = 0;
                 //Faço o foreach com o resultado da variavel $resultado_informacao
                 foreach($resultado_informacao as $informacao_interacao){
                    //Verifico qual é o remetente para somar no contador
                    if($informacao_interacao['remetente'] == "Expert"){
                        $resultadoExpert +=1;
                    }else{
                        $resultadoCustomer +=1;
                    }
            ?>
            <tr>
                <td><center><?= $informacao_interacao['assunto'] ?> </center></td>
                <td><center><?= $informacao_interacao['mensagem'] ?> </center></td>
                <td><center><?= date('d/m/Y', strtotime($informacao_interacao['data_criacao'])) ?> </center></td>
                <td><center><?= $informacao_interacao['remetente'] ?> </center></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
<div class ="row">
    <!-- Armazendo o código do ticker para um input hidden para ser usado depois -->
    <input type="hidden" name="cod_ticket" id="cod_ticket" class="form-control" value="<?= $codTicket ?>">

    <!-- Quantidade de mensagens usada para customer -->
    <div class="col-sm-12 col-md-12 col-lg-12" style="align:center">
        <h4>Total de mensagens  com remetente customer: <strong><?= $resultadoCustomer ?></strong><h4>
        
    </div>

    <!-- Quantidade de mensagens usada para expert -->
    <div class="col-sm-12 col-md-12 col-lg-12" style="align:center">
        <h4>Total de mensagens  com remetente Expert:  <strong><?= $resultadoExpert ?></strong><h4>
    </div>

    <!-- Quantidade de dias do ticket desde sua data inicial até seu ultimo update -->
    <div class="col-sm-12 col-md-12 col-lg-12" style="align:center">
        <h4>Quantidade de dias entre o dia inicial do ticket até seu ultimo update: <strong><?= $dias_intervalo ?></strong></h4>
        
    </div>
</div>
<br/>
<div class="row">
    <?php
        //Verifico se o dia de intervalo entre a data de inicial e a data de update é de <= 3 para dar uma ideia que esse ticket pode ser atualizado como prioridade normal 
        if ($dias_intervalo <= 3) 
    {   
    ?>
        <!-- Prioridade Normal -->
        <div class="col-sm-12 col-xs-12 col-lg-12">
            <label>Verificando que o tempo de intervalo ainda é pequeno você poderia colocar com prioridade baixa</label>
        </div>
        <div class="col-sm-12 col-xs-12 col-lg-12" align="center">
            <!-- inserirPrioridade($codTicket, 2) passo para a inserirPrioridade o código do ticket selecionado, para depois aparecer no outro modal, já setando a informação , 2 - manual -->
            <button type="button" class="btn btn-warning btn-flat" onclick="inserirPrioridade(<?= $codTicket ?>, 2)">Cadastrar prioridade</button>
        </div>
    <?php } else {?>
        <div class="col-sm-12 col-xs-12 col-lg-12">
            <label>Verificando que o tempo de intervalo é grande você poderia colocar com prioridade alta, para dar mais atenção nesse ticket</label>
        </div>
        <!-- Prioridade alta -->
        <div class="col-sm-12 col-xs-12 col-lg-12"  align="center">
            <!-- inserirPrioridade($codTicket,1) passo para a inserirPrioridade o código do ticket selecionado, para depois aparecer no outro modal, já setando a informação, 1 - alta -->
            <button type="button" class="btn btn-danger btn-flat" onclick="inserirPrioridade(<?= $codTicket ?>, 1)">Cadastrar prioridade</button>
        </div>
    <?php } ?>
    
    
    
</div>
