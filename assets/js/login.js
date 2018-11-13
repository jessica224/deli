$('document').ready(function () {

    var base_url = $('#BASE_URL').val();
    var url_login = base_url + '/login';
    var url_admin = base_url + '/admin';

    console.log("end point api: " + base_url)
    //submetendo o formulado login
    $('form[name="login-form"]').submit(function () {
        var form = $(this);
        var botao = $(this).find(':button');

        $.ajax({
            url: url_login,
            type: "POST",
            data: form.serialize(),
            beforeSend: function () {
                botao.html('<i class="fa fa-spinner fa-spin fa-1x"></i> <h6> verificando login...</h6>').attr('disabled', true);
            },
            success: function (result) {
                console.log(result)
                var res = $.trim(result);
                if (res == 1) {
                    console.log("erro ao tentar login")
                    Materialize.toast('Login e senha incorretos', 5000);
                    botao.attr('disabled', false).html('<i class="fa fa-user-secret fa-2x"></i> Login');
                } else if (res == 3) {
                    console.log("Limite de tentativas de login excedidos")
                    Materialize.toast('Limite de tentativas de login excedidos !!!', 5000);
                    botao.attr('disabled', false).html('<i class="fa fa-user-secret fa-2x"></i> Login');
                } else {
                    window.location.href = url_admin;
                }
            },
            error: function (request, status, erro) {
                alert("Problema ocorrido: " + status + "\n Descrição: " + erro);
                //Abaixo está listando os header do conteudo que você requisitou, só para confirmar se você setou os header e dataType corretos
                alert("Informações da requisição: \n" + request.getAllResponseHeaders());
            }
        });

        return false;
    });
});