const activeItem = item => {
    document.querySelectorAll('#itemMenu li').forEach(li=>{
        li.classList.remove('active')
    })
    console.log(1)
    $(`#itemMenu #${item}`).addClass('active');
}