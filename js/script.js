document.addEventListener('DOMContentLoaded', function() {
    var hamburger = document.querySelector('.hamburger');
    var navList = document.querySelector('.event-navigation ul');

    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('open');
        navList.classList.toggle('open');
    });
});

//  Google Maps API Key: AIzaSyBGZLfom_9gzVfPI39FCQ1MHWGxNjxUqDg
