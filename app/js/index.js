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

    loadView('login/login', '#view')
})

function loadView(view, selector = '#app-page'){
    $(selector).html('')
    $.get('views/' + view + '.html', function(data){
        $(selector).html(data)
    })
}

function sair(){
    Leila.sair()
    loadView('login/login', '#view')
}

function parseJwt (token) {
    var base64Url = token.split('.')[1]
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/')
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
    }).join(''))

    return JSON.parse(jsonPayload)
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
            if(request.responseJSON.message.length == 0)
            {
                $.notify({message: 'Wooops! Não foi possível fazer isso'},{type: 'danger'})
            }
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