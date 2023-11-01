
config = {
    route: 'Usuario'
}

tblCliente =  $('#tbl-Cliente').DataTable({
    columns: [
        { data: 'id' }
        ,{ data: 'nome' }
        ,{ data: 'email' }
        ,{ data: 'telefone' }
        ,{ data: null, className: 'text-center', render: function(data){
            return '--'
        }}
    ]
})

tblCliente.__proto__.reload = function(){

    config.method = 'GET'
    
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