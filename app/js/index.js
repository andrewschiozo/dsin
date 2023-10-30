localStorage.setItem('view', 'login')

if(localStorage.getItem('view') == 'login')
{
    localStorage.clear()
    sessionStorage.clear()
}

const GLOBALS = {
    baseUrl: window.location.origin + 'dsin/app/'
   ,baseUrlService: window.location.origin + '/dsin/service/'
   ,meses: {
        nome:  ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
   }
   ,Today: new Date()
}

$(function(){
    Leila = {
       data: {
            logado: false
            ,usuario: null
            ,token: null
        } 
        ,save: function(){
            localStorage.setItem('data', JSON.stringify(this.data))
        }
        ,get: function(){
            return JSON.parse(localStorage.getItem('data'))
        }
        ,sair: function(){
            this.data.logado = false
            this.data.usuario = null
            this.data.token = null
            this.save()
        }
    }

    Leila.save();

    loadView('views/login/login')
})

function loadView(view){
    $('#view').html('')
    $.get(view + '.html', function(data){
        $('#view').html(data)
    })
}

function sair(){
    Leila.sair()
    loadView('views/login/login')
}

$.requestService = function({route, method = 'GET', data = {}, resource = null, async = true} = {}, callback){

    let config = {
        url: GLOBALS.baseUrlService + route
        ,type: method
        ,contentType: 'application/json'
        ,data: JSON.stringify({data: data, resource: resource})
        ,async: async
        ,dataType: 'json'
        ,success: function(response){
            callback(response)
        }
        ,error: function(request){
            $.notify({message: 'Wooops! Não foi possível fazer isso'},{type: 'danger'})
        }
        ,complete: function(request)
        {
            let notificacaoMensagem = ''
            if(request.responseJSON.message.length > 0)
            {
                $.each(request.responseJSON.message, function(index, mensagem) {
                    notificacaoMensagem += mensagem + '; '
                })
                $.notify({message: notificacaoMensagem},{type: request.responseJSON.status ? 'success' : 'danger'})
            }
        }

    }
    config.headers = route !== 'Login' ? {'Authorization': 'Bearer ' + Leila.get().token} : null

    $.ajax(config)
}