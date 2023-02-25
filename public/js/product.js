const ativarItem = id => {
    document.querySelectorAll('ul.product-tab li').forEach(li => {
        li.classList.remove('active')
    })
    document.querySelectorAll('div.tab-content div.fade').forEach(li => {
        li.classList.remove('active')
    })

    // console.log(document.querySelector(`#${id}`))
    // console.log(document.querySelector(`.${id}`))
    document.querySelector(`#${id}`).classList.add('in')
    document.querySelector(`#${id}`).classList.add('active')
    document.querySelector(`.${id}`).classList.add('active')
}

const stars = document.querySelectorAll('.star');
const rating = document.querySelector('#rating');
const nota = document.querySelector('#nota');

stars.forEach(star => {
    star.addEventListener('click', () => {
        stars.forEach(s => s.classList.remove('selected'));
        let idx = star.getAttribute('data-value')

        for (let i = 0; i < idx; i++) {
            document.querySelectorAll('#stars li')[i].classList.add('selected')
        }

        // star.classList.add('selected');
        const ratingValue = parseFloat(star.getAttribute('data-value'));
        if (ratingValue % 1 !== 0) {
            rating.textContent = ratingValue.toFixed(1);
            nota.value = ratingValue.toFixed(1);
        } else {
            rating.textContent = ratingValue;
            nota.value = ratingValue;
        }
    });
});