
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