
config = {
    route: 'Servico'
}

tblServico =  $('#tbl-Servico').DataTable({
    columns: [
        { data: 'id' }
        ,{ data: 'nome' }
        ,{ data: 'descricao' }
    ]
})

tblServico.__proto__.reload = function(){

    config.method = 'GET'
    config.resource = 'listar'
    
    $.requestService(config, function(response){
        tblServico.clear()
        tblServico.rows.add( response.data ).draw()
    })
}

tblServico.reload()


$('#tbl-Servico tbody').on('dblclick', 'tr', function () {
    console.log(tblServico.row(this).data())
})