
config = {
    route: 'Usuario'
}

tblCliente =  $('#tbl-Cliente').DataTable({
    columns: [
        { data: 'id' }
        ,{ data: 'nome' }
        ,{ data: 'email' }
        ,{ data: 'telefone' }
        ,{ data: 'usuario' }
    ]
})

tblCliente.__proto__.reload = function(){

    config.method = 'GET'
    config.data = {}
    
    $.requestService(config, function(response){
        tblCliente.clear()
        tblCliente.rows.add( response.data ).draw()
    })
}

tblCliente.reload()

function limparForm()
{
    $('.inputFormCliente').text('')
    $('.inputFormCliente').val('')
}

function popularForm(Cliente)
{
    limparForm()

    $('#id').text(Cliente.id)
    $('#nome').val(Cliente.nome)
    $('#email').val(Cliente.email)
    $('#telefone').val(Cliente.telefone)

    $('#modalCliente').modal('show')
}

$('#tbl-Cliente tbody').on('dblclick', 'tr', function () {
    popularForm(tblCliente.row(this).data())
    $('#modalCliente').modal('show')
})


$('#formCliente').submit(function(e){
    e.preventDefault()

    $('#btn-salvar').attr('disabled', 'disable')

    // if(!validarForm())
    //     return

    config.method = 'POST'

    config.data = {
        nome: $('#nome').val()
        ,email: $('#email').val()
        ,telefone: $('#telefone').val()
    }

    if($('#id').text())
    {
        config.data.id = $('#id').text()
        config.method = 'PUT'
    }

    $.requestService(config, (response) => {
        if(response.data.senha)
            $.notify({title: 'Senha: ', message: '<strong>' + response.data.senha + '</strong>'})
        
        limparForm()
        $('#modalCliente').modal('hide')
        tblCliente.reload()
    })
    $('#btn-salvar').removeAttr('disabled')
})