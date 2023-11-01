
config = {
    route: 'Agenda'
}


tblAgenda =  $('#tbl-Agenda').DataTable({
    columns: [
        { data: 'id' }
        ,{ data: 'data', render: function(data){
            d = new Date(data)
            return d.toLocaleString()
        } }
        ,{ data: 'cliente'}
        ,{ data: 'servico' }
        ,{ data: 'situacao' }
    ]
    ,createdRow: function (row, data, dataIndex) {
        className = ''
        switch (data.situacao) {
            case 'Aguardando confirmação':
                className = 'table-warning'
                break;
        
            case 'Confirmado':
                className = 'table-primary'
                break;

            case 'Finalizado':
                className = 'table-success'
                break;

            case 'Cancelado':
                className = 'table-danger'
                break;
        }
        $(row).addClass(className);
    }
})

tblAgenda.__proto__.reload = function(){

    config.route = 'Agenda'
    config.method = 'GET'
    config.data = {}
    
    $.requestService(config, function(response){
        tblAgenda.clear()
        tblAgenda.rows.add( response.data ).draw()
    })
}

tblAgenda.reload()
getServicos()
getClientes()
getServicoStatus()

function getServicos()
{
    config.route = 'Servico'
    config.method = 'GET'
    config.data = {}
    config.resource = 'listar';
        
    $.requestService(config, function(response){
        $.each(response.data, function(index, servico){
            $('#servico').append($('<option>').val(servico.id)
                                            .text(servico.nome))
        })
    })
}

function getServicoStatus()
{
    config.route = 'Servico'
    config.method = 'GET'
    config.data = {}
    config.resource = 'getStatus';
        
    $.requestService(config, function(response){
        $.each(response.data, function(index, status){
            $('#status').append($('<option>').val(status.id)
                                            .text(status.nome))
        })
    })
}

function getClientes()
{
    config.route = 'Usuario'
    config.method = 'GET'
    config.data = {}
    config.resource = 'getCliente';
        
    $.requestService(config, function(response){
        $.each(response.data, function(index, cliente){
            $('#cliente').append($('<option>').val(cliente.id)
                                            .text(cliente.nome))
        })
    })
}

function limparForm()
{
    $('.inputFormAgenda').val('')
}


function popularForm(Agenda)
{
    limparForm()

    $('#id').text(Agenda.id)
    $('#servico').val(Agenda.servico_id)
    $('#cliente-nome').text(Agenda.cliente)
    $('#cliente').val(Agenda.cliente_id)
    $('#data').val(Agenda.data)
    $('#status').val(Agenda.situacao_id)


    $('#modalCliente').modal('show')
}

$('#tbl-Agenda tbody').on('dblclick', 'tr', function () {
    popularForm(tblAgenda.row(this).data())
    $('#modalAgenda').modal('show')
})

$('#formAgenda').submit(function(e){
    e.preventDefault()

    $('#btn-salvar').attr('disabled', 'disable')

    // if(!validarForm())
    //     return
    config.route = 'Agenda'
    config.method = 'POST'

    config.data = {
         cliente: $('#cliente').val()
        ,servico: $('#servico').val()
        ,data: $('#data').val()
        ,status: $('#status').val()
    }

    if($('#id').text())
    {
        config.data.id = $('#id').text()
        config.method = 'PUT'
    }

    $.requestService(config, (response) => {

        limparForm()
        $('#modalAgenda').modal('hide')
        tblAgenda.reload()
    })
    $('#btn-salvar').removeAttr('disabled')
})