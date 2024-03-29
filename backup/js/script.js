console.log('NG custom script file loaded');
(function(){
    var burger = document.querySelector('#burger'),
        nav = document.querySelector('.nav'),
        menuItem = document.querySelectorAll('.menu-item, .home');
    
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

// function initMap() {
//     console.log("initMap started");
//     var map = new google.maps.Map(document.getElementById('map'), {
//         zoom: 10, // Example zoom level
//         center: { lat: -34.397, lng: 150.644 } // Example coordinates
//     });
//     console.log("Map should be initialized");
// }

function initMap() {
    if (eventData && eventData.eventLocation) {
        var eventLoc = {
            lat: parseFloat(eventData.eventLocation.lat),
            lng: parseFloat(eventData.eventLocation.lng)
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10, // Initial zoom, but we will adjust this dynamically
            center: eventLoc
        });

        var bounds = new google.maps.LatLngBounds();

        // Marker for the event location
        var eventMarker = new google.maps.Marker({
            position: eventLoc,
            map: map,
            title: eventData.eventLocation.name || 'Event Location'
        });
        bounds.extend(eventMarker.getPosition());

        // Markers for the hotels
        if (eventData.hotels && eventData.hotels.length > 0) {
            eventData.hotels.forEach(function(hotel) {
                var hotelLoc = {
                    lat: parseFloat(hotel.location.lat),
                    lng: parseFloat(hotel.location.lng)
                };
                var marker = new google.maps.Marker({
                    position: hotelLoc,
                    map: map,
                    title: hotel.name
                });
                bounds.extend(marker.getPosition());
            });
        }

        // Adjust the map view to include all markers
        map.fitBounds(bounds);

        // Optional: Adjust the zoom level after fitting bounds if the zoom is too high
        var listener = google.maps.event.addListener(map, "idle", function() {
            if (map.getZoom() > 16) map.setZoom(16);
            google.maps.event.removeListener(listener);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var countdownContainer = document.getElementById('countdown');
    if (countdownContainer) {
        var eventTime = parseInt(countdownContainer.getAttribute('data-event-time'), 10);
        var updateCountdown = function() {
            var currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
            var timeRemaining = eventTime - currentTime;
            
            // If within one week (but more than 24 hours), show days and hours only
            if (timeRemaining > 86400) {
                var days = Math.floor(timeRemaining / 86400);
                timeRemaining %= 86400;
                var hours = Math.floor(timeRemaining / 3600);
                countdownContainer.innerHTML = 'The Gala begins in ' + days + ' days, ' + hours + ' hours.';
            } 
            // If within last 24 hours, show detailed countdown
            else if (timeRemaining > 0) {
                var hours = Math.floor(timeRemaining / 3600);
                timeRemaining %= 3600;
                var minutes = Math.floor(timeRemaining / 60);
                var seconds = timeRemaining % 60;

                countdownContainer.innerHTML = 'The Gala begins in ' + hours + ' hours, ' + minutes + ' minutes, ' + seconds + ' seconds until the event.';
            } else {
                countdownContainer.innerHTML = 'The event has started!';
                clearInterval(interval); // Stop updating the countdown
            }
        };

        updateCountdown(); // Run once on load
        var interval = setInterval(updateCountdown, 1000); // Update every second
    }
});

document.addEventListener('scroll', function() {
    const scrollPosition = window.scrollY;
    const headerImgMain = document.querySelector('.header-img-main');
  
    // Initial zoom and position
    const initialZoom = 110; // Starting zoomed in at 110%
    const initialPosition = 75; // Your chosen starting position
  
    // Slow down the zoom effect by using a smaller factor
    let backgroundSize = initialZoom - (scrollPosition * 0.01); // Reduced factor for slower zoom
    backgroundSize = backgroundSize < 100 ? 100 : backgroundSize; // Ensure it doesn't zoom out too much
  
    // Slow down the pan effect by using a smaller factor
    let positionAdjustment = (scrollPosition * 0.02); // Reduced factor for slower panning
    let newPosition = initialPosition - positionAdjustment; // Calculate new position based on scroll
    newPosition = newPosition < 0 ? 0 : newPosition; // Prevent it from going below 0%
  
    // Apply the dynamic background size and position
    headerImgMain.style.backgroundSize = `${backgroundSize}%`;
    headerImgMain.style.backgroundPosition = `center ${newPosition}%`;
  });
  
 