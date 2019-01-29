$(document).ready(function(){
    $(".navbar-toggle").click(function(){
        $(".menu").toggleClass("menu-open");
    })
});


function excluirUsuario(codusuario){
    $.confirm({
        title: 'Atenção!',
        content: 'Confirma a exclus]ao do usuário!',
        buttons: {
            Sim: function () {
                
                $.get('/excluirUsuario/' + codusuario, 
                    function (data) {
                        if(data.status == "Ok"){
                            alert('Excluido com sucesso, iremos atualizar a página');
                            location.reload();
                        }else{
                            alert('Erro ao excluir por favor contate o suporte');
                        }
                });
            },
            Não: function () {
                $.alert('Evento cancelado');
            }
        }
    });
    
}

function excluirBandaArtista(codBandaArtista){
    $.confirm({
        title: 'Atenção!',
        content: 'Confirma exclusão da banda ou artista, lembrando que será apagado todos os albuns vinculadas a ele!',
        buttons: {
            Sim: function () {
                
                $.get('/excluirBandaArtista/' + codBandaArtista, 
                function (data) {
                   if(data.status == "Ok"){
                       $.alert('Excluido com sucesso, iremos atualizar a página');
                       location.reload();
                   }else{
                       $.alert('Erro ao excluir por favor contate o suporte');
                   }
               });
            },
            Não: function () {
                $.alert('Evento cancelado');
            }
        }
    });
  
}

function excluirAlbum(codAlbum){
    $.confirm({
        title: 'Atenção!',
        content: 'Confirma exclusão do album, lembrando que será apagadas todas faixas vinculadas a ele!',
        buttons: {
            Sim: function () {
                
                $.get('/excluirAlbum/' + codAlbum, 
                    function (result) {
                        
                    if(result.status == "Ok"){
                        $.alert('Excluido com sucesso, iremos atualizar a página');
                        location.reload();
                    }else{
                        $.alert('Erro ao excluir por favor contate o suporte');
                    }
                });
            },
            Não: function () {
                $.alert('Evento cancelado');
            }
        }
    });
}

function selecionarBanda(){
    $('#mostrarDivFaixa').css({display:'block'});
}

function adicionarFaixas(){
    contador = $('#valorFaixa').val();
    $('#botaoGerar').html('Criando as faixas');

    for( i =0; i< contador; i++){
        numeroFaixa = i+1;
        $('#addInf').append('<div align="center"><h4 align="center">Faixa '+numeroFaixa+'</h4></div>');
        $('#addInf').append("<div class='row'><div class='col-sm-6'><label for='nomeMusica'>Nome da musica </label><input type='text' name='nomeMusica"+i+"' id='nomeMusica"+i+"' /></div></div>");
        $('#addInf').append("<div class='row'><div class='col-sm-6'><label for='duracaoMusica'>Duração da musica </label><input type='time' name='duracaoMusica"+i+"' id='duracaoMusica"+i+"' /></div></div>");
        $('#addInf').append("<div class='row'><div class='col-sm-6'><label for='compositorMusica'>Compositor(a)</label> <input type='text' name='compositorMusica"+i+"' id='compositoroMusica"+i+"' /></div></div>");
        $('#addInf').append("<div class='row'><div class='col-sm-6'><label for='Numero'>Numero da Faixa </label><input type='text' name='numeroFaixa"+i+"' id='numeroFaixa"+i+"' /></div></div>");
        $('#addInf').append("<br/>");
    }
    $('#addInf').css({display: "block"});
    $('#botaoGerar').html('Faixas Criadas');

    
  
}

function excluirFaixa(codFaixa){
    $.get('/excluirFaixa/' + codFaixa, 
     function (data) {
        if(data.status == "Ok"){
            alert('Excluido com sucesso, iremos atualizar a página');
            location.reload();
        }else{
            alert('Erro ao excluir por favor contate o suporte');
        }
    });
}

function mostrarTexto(valor){
  
    if(valor == 1){
        $('#mostrarUsuario').css({display: "none"});
        $('#mostrarAdmin').css({display: "none"});
        $('#mostrarPublico').css({display: "block"});
    }else if(valor == 2){
        $('#mostrarAdmin').css({display: "none"});
        $('#mostrarPublico').css({display: "none"});
        $('#mostrarUsuario').css({display: "block"});
        
    }else{
        $('#mostrarPublico').css({display: "none"});
        $('#mostrarUsuario').css({display: "none"});
        $('#mostrarAdmin').css({display: "block"});
    }
}

function validarNome(){
    var _token = $("input[name='_token']").val();
    var nome = $('#nomeUsuario').val();

    $.ajax({
        type: "POST",
        url: '/validarNome',
        data: { _token, nome},
        success: function(resposta) {
           var status = resposta.status;
            if(status == "Ok"){
                $.alert('Nome já cadastrado no sistema');
                $('#nomeUsuario').val("");
            }else{
                return false;
            }
        }
    });
}

function validarEmail(){
    var _token = $("input[name='_token']").val();
    var email = $('#emailUsuario').val();

    $.ajax({
        type: "POST",
        url: '/validarEmail',
        data: { _token, email},
        success: function(resposta) {
           var status = resposta.status;
            if(status == "Ok"){
                $.alert('Email já cadastrado no sistema');
                $('#emailUsuario').val("");
            }else{
                return false;
            }
        }
    });
}

function validarBandaArtista(){
    var _token = $("input[name='_token']").val();
    var banda = $('#nomeBandaArtista').val();
    $.ajax({
        type: "POST",
        url: '/validarBanda',
        data: { _token, banda},
        success: function(resposta) {
           var status = resposta.status;
            if(status == "Ok"){
                $.alert('Banda já cadastrada no sistema');
                $('#nomeBandaArtista').val("");
            }else{
                return false;
            }
        }
    });

}
function visualizarBanda(codigo){
    var _token = $("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: '/visualizarBanda',
        data: { _token, codigo},
        success: function(resposta) {
           $('#nomeBanda').html(resposta.nome_banda);
           $('#generoBanda').html(resposta.genero);
           $('#descricao').html(resposta.descricao);

           $("#modal-id").modal();
        }
    });

}

function visualizarAlbum(codigo){
    var _token = $("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: '/visualizarAlbum',
        data: { _token, codigo},
        success: function(resposta) {
           $('#nomeBanda').html(resposta.nome_banda);
           $('#generoBanda').html(resposta.genero);
           $('#descricao').html(resposta.descricao);

           $("#modal-id").modal();
        }
    });
}

function visualizarFaixa(codigo){
    var _token = $("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: '/visualizarFaixa',
        data: { _token, codigo},
        success: function(resposta) {
           $('#nomeFaixa').html(resposta.nome_faixa);
           $('#duracao').html(resposta.duracao);
           $('#numeroFaixa').html(resposta.numero_faixa);

           $("#modal-faixa").modal();
        }
    });
}

function visualizarAlbum(codigo){
    var _token = $("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: '/visualizarAlbum',
        data: { _token, codigo},
        success: function(resposta) {
           $('#nomeAlbumCad').html(resposta.nome_album);
           $('#anoLancamento').html(resposta.ano_lancamento);
          
           $("#modal-album").modal();
        }
    });
}

