const burger = document.querySelector('.burger');
const hamburger = document.querySelector('.fa-bars');
const close = document.querySelector('.fa-times');

hamburger.addEventListener('click', () => {
    burger.style.display = 'block';
});

close.addEventListener('click', () => {
    burger.style.display = 'none';
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.burger') && !event.target.closest('.fa-bars')) {
        burger.style.display = 'none';
    }
});
