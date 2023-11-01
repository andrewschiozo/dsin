setMenu()

$('.usuario-nome').text(Leila.get().usuario.data.nome)

//Funções
function setMenu()
{
    $('#menus').html('')
    $.each(Leila.get().usuario.data.menu, function(index, menu){
        $('#menus').append($('<div>').addClass('col-1')
                .append($('<button>').addClass('btn-menu btn')
                                     .addClass(menu.nome == 'Home' ? 'btn-dark' : '')
                                     .attr('view', menu.href)
                                     .append($('<i>').addClass(menu.icone))
                                     .append(menu.nome)))
    })
}

//Eventos
$('.btn-menu').click(function(){
    let view = $(this).attr('view')

    $('.btn-menu').removeClass('btn-dark')
    $(this).addClass('btn-dark')
    
    if(view == 'home/home_cliente' || view == 'home/home_gerente')
    {
        console.log(view)
        loadView(view, '#view')
        return
    }
    loadView(view)  
})