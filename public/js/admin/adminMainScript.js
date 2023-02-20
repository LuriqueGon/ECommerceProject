const formatTel = () => {
    let telefones = document.querySelectorAll('.telefone')


    telefones.forEach((telefone, idx) => {
        let tel = telefone.textContent
        if(tel.length == 10){
            document.querySelectorAll('.telefone')[idx].textContent =`(${tel[0]}${tel[1]}) ${tel[2]}${tel[3]}${tel[4]}${tel[5]} - ${tel[6]}${tel[7]}${tel[8]}${tel[9]}`
        }else if(tel.length == 11){
            document.querySelectorAll('.telefone')[idx].textContent =`(${tel[0]}${tel[1]}) ${tel[2]} ${tel[3]}${tel[4]}${tel[5]}${tel[6]} - ${tel[7]}${tel[8]}${tel[9]}${tel[10]}`
        }
    });
}

formatTel();

const deletarItem = id => {
    if(confirm(`Deseja mesmo deletar o Usuario de Id ${id} ??`)){
        location.href = `/admin/users/delete?id=${id}`
    }
}

const deletarCategoria = id => {
    if(confirm(`Deseja mesmo deletar a Categoria de Id ${id} ??`)){
        location.href = `/admin/categoria/delete?id=${id}`
    }
}