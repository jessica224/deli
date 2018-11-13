$('document').ready(function () {

    var base_url = $('#BASE_URL').text();
	//var base_url = $('#BASE_URL').val();
    console.log(base_url)


//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    // PRODUTOS
    $("#submitFormProduto").click(function () {
        if ($("#cat_codigo").val() == null) {
            Materialize.toast('informe uma categoria para o produto', 3000);
            return false
        }
        if ($("#prd_descricao").val() == '') {
            Materialize.toast('informe uma descrição para o produto', 3000);
            return false
        }
        if ($("#prd_preco").val() == '') {
            Materialize.toast('informe uma descrição para o produto', 3000);
            return false
        }
        if ($("input:file").val() == '') {
            Materialize.toast('Escolha uma imagem para cadastrar o produto', 3000);
            return false
        }
    });
    $('#prd_preco').mask("###0.00", {reverse: true});

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    // APP BAIRROS
    $('#bai_taxa').mask("###0.00", {reverse: true});
    $("#submitFormBairro").click(function () {
        if ($("#bai_nome").val() == '') {
            Materialize.toast('informe o nome do bairro', 3000);
            return false
        }
        if ($("#bai_taxa").val() == '') {
            Materialize.toast('informe uma taxa de entrega para o bairro', 3000);
            return false
        }
    });

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//################################################################# 

    // APP CATEGORIA
    $("#submitFormCategoria").click(function () {
        if ($("#cat_descricao").val() == '') {
            Materialize.toast('informe a categoria', 3000);
            return false
        }

        if ($("input:file").val() == '') {
            Materialize.toast('Escolha uma imagem para cadastrar a categoria', 3000);
            return false
        }

    });

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    // APP ADICIONAL DO PRODUTO
    $("#submitFormAdicional").click(function () {

        if ($("#adc_descricao").val() == '') {
            Materialize.toast('informe o nome do adicional para o produto', 3000);
            return false
        }
        if ($("#adc_preco").val() == '') {
            Materialize.toast('informe uma descrição para o produto', 3000);
            return false
        }
        if ($("input:file").val() == '') {
            Materialize.toast('Escolha uma imagem para cadastrar o adicional', 3000);
            return false
        }

    });

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    $('select').material_select();
    $('#data-table-simple').DataTable({
        "language": {
            "lengthMenu": "Mostrando _MENU_ registros por pagina",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro",
            "loadingRecords": "Carregando dados...",
            "infoFiltered": "(filtrado de _MAX_ total registros)",
            "search": "Pesquisar",
            "paginate": {
                "first": "Primeiro",
                "last": "Último",
                "next": "Próximo",
                "previous": "Anterior"
            }}
    });

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    $("#operarEmpresa").click(function () {
        $.ajax({
            url: base_url + '/update',
            type: 'GET',
            success: function (data) {
                console.log(data)
                //  $(location).attr('href', base_url + '/admin')
            }
        });
    });

//##################################################################
//##################################################################
//##################################################################
//##################################################################
//##################################################################

    $('#usu_contato').mask("(99)9-9999-9999");

    var diaSemana = ['Domingo', 'Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado'];
    var mesAno = ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    var data = new Date();
    var hoje = diaSemana[data.getDay()] + ', ' + mesAno[data.getMonth()] + ' de ' + data.getFullYear();
    $("#dataPesquisa").attr("value", hoje);
    $(".datepicker").pickadate({
        monthsFull: mesAno,
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        weekdaysFull: diaSemana,
        weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        selectMonths: true,
        selectYears: true,
        clear: false,
        format: 'dd/mm/yyyy',
        today: "Hoje",
        close: "Fechar",
        min: new Date(data.getFullYear() - 1, 0, 1),
        max: new Date(data.getFullYear() + 1, 11, 31),
        closeOnSelect: true
    });

    $("#dataPesquisa").click(function (event) {
        event.stopPropagation();
        $(".datepicker").first().pickadate("picker").open();
    });


    $('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    }
    );

});