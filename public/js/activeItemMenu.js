const activeItem = item => {
    document.querySelectorAll('#itemMenu li').forEach(li=>{
        li.classList.remove('active')
    })
    $(`#itemMenu #${item}`).addClass('active');
}