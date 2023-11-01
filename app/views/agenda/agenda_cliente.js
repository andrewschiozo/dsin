
config = {
    route: 'Agenda'
}


tblAgenda =  $('#tbl-Agenda').DataTable({
    columns: [
        { data: 'id' }
        ,{ data: 'data' }
        ,{ data: 'servico' }
        ,{ data: 'situacao' }
        ,{ data: null, render: function(){
            return ''
        } }
    ]
})

tblAgenda.__proto__.reload = function(){

    config.route = 'Agenda'
    config.method = 'GET'
    config.resource = 'listar'
    config.data = {}
    
    $.requestService(config, function(response){
        tblAgenda.clear()
        tblAgenda.rows.add( response.data ).draw()
    })
}

tblAgenda.reload()
getServicos()

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

function limparForm()
{
    $('.inputFormAgenda').val(1)
    $('.inputFormAgenda').val('')
}

$('#formAgenda').submit(function(e){
    e.preventDefault()

    $('#btn-salvar').attr('disabled', 'disable')

    // if(!validarForm())
    //     return
    config.route = 'Agenda'
    config.method = 'POST'
    config.resource = 'agendar'

    config.data = {
        servico: $('#servico').val()
        ,data: $('#data').val()
    }

    $.requestService(config, (response) => {

        limparForm()
        $('#modalAgenda').modal('hide')
        tblAgenda.reload()
    })
    $('#btn-salvar').removeAttr('disabled')
})