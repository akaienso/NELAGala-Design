
(function(){
    var burger = document.querySelector('.burger-container'),
        nav = document.querySelector('.nav'),
        menuItem = document.querySelectorAll('.menu-item');
    
    burger.onclick = function() {
        nav.classList.toggle('menu-opened');
    }

    menuItem.forEach(item => {
       item.onclick = function() {
        nav.classList.toggle('menu-opened');
    }
    })
}());

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const target = document.querySelector(this.getAttribute('href'));

        window.scrollTo({
            top: target.offsetTop,
            behavior: 'smooth'
        });
    });
});
