
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
function initMap() {
    // Confirm eventData is defined and contains the event location.
    if (eventData && eventData.eventLocation) {
        var eventLoc = {
            lat: parseFloat(eventData.eventLocation.lat),
            lng: parseFloat(eventData.eventLocation.lng)
        };

        // Initialize the map centered at the event location.
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: eventData.eventLocation.zoom ? parseInt(eventData.eventLocation.zoom) : 15, // Use the provided zoom or default to 15
            center: eventLoc
        });

        // Optionally, place a marker at the event location.
        new google.maps.Marker({
            position: eventLoc,
            map: map,
            title: eventData.eventLocation.name || 'Event Location'
        });
    }
}

