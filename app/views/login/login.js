requestConfig = {
    route: 'Login'
}

$('#btn-login').click(function(){
    requestConfig.method = 'POST'
    requestConfig.data = {
         usuario: $('#login-usuario').val()
        ,senha: $('#login-senha').val()
    }
    
    $.requestService(requestConfig, function(response){
        if(!response.status)
            return

        $.notify({message: 'Sucesso!'},{type: 'success'})

        Leila.data.logado = true
        Leila.data.token = response.data.token
        Leila.data.usuario = parseJwt(response.data.token)
        Leila.save()

        loadView(Leila.get().usuario.data.perfil == 'Gerente' ? 'home/home_gerente' : 'home/home_cliente', '#view')
    })
})