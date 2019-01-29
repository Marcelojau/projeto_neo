<?php
   require '../controllers/Tickets.php';
   date_default_timezone_set('America/Sao_Paulo');
  
   //Código do ticket enviado 
   $codTicket = $_POST['codigo_ticket'];


   //Instâncio a classe
   $ticket = new \controllers\Tickets;

   //Chamo o método buscarHistoricoPorTicket, passando para ele o código do ticket selecionado
   $resultado_informacao = $ticket->buscarHistoricoPorTicket($codTicket);

   if(count($resultado_informacao) >0){
?>


<div class="table-responsive">
    <table class="table table-hover" >
        <thead>
            <tr>
                <th><center> Codigo Histórico </center></th>
                <th><center> Data Historico </center></th>
                <th><center> Prioridade  </center></th>
                <th><center> Assunto </center></th>
            </tr>
        </thead>
        <tbody>
            <?php
                 foreach($resultado_informacao as $informacao_historico){
            ?>
            <tr>
                <td><center><?= $informacao_historico['cod_historico'] ?> </center></td>
                <td><center><?= date('d/m/Y', strtotime($informacao_historico['data_historico'])) ?> </center></td>
                <td><center><?= $informacao_historico['prioridade'] ?> </center></td>
                <td><center><?= utf8_encode($informacao_historico['assunto_prioridade']) ?> </center></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
<?php } else {?>
<div align="center">   
    <H3><strong>Nenhum histórico cadastrado</strong></H3>
</div>
<?php } ?>