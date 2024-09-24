function initMap() {
    let mapOptions = {
        zoom: 13,
        center: { lat: -34.397, lng: 150.644 }, // Set initial map center
    };
    
    let map = new google.maps.Map(document.getElementById("map"), mapOptions);
    let marker;

    map.addListener("click", function (event) {
        let lat = event.latLng.lat();
        let lng = event.latLng.lng();

        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
            });
        }

        document.getElementById("lat").value = lat;
        document.getElementById("lng").value = lng;
    });
}

window.initMap = initMap;
