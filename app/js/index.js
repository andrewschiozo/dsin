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
