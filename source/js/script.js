// document.addEventListener('DOMContentLoaded', function() {
//     var hamburger = document.querySelector('.hamburger');
//     var navList = document.querySelector('.event-navigation ul');

//     hamburger.addEventListener('click', function() {
//         hamburger.classList.toggle('open');
//         navList.classList.toggle('open');
//     });
// });


(function(){
    var burger = document.querySelector('.burger-container'),
        nav = document.querySelector('.nav');
    
    burger.onclick = function() {
        nav.classList.toggle('menu-opened');
    }
}());

//  Google Maps API Key: AIzaSyBGZLfom_9gzVfPI39FCQ1MHWGxNjxUqDg
