var map;
var myLatLng = {lat: 25.04, lng: 121.512};

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        zoom: 18
    });

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title:'這是總統府'
    });

  marker.setMap(map);
}

/*initMap();

function initMap(){
    var map;
    var markers = [];
    var location;
    var geocoder= new google.maps.Geocoder();
    
        
    map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng, 
        zoom: 18
    });

    console.log(navigator.geolocation.getCurrentPosition());

    //main function of geocoder
    function _geocoder(address, callback){
        geocoder.geocode({
            address: address
        }, function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                location = results[0].geometry.location;
                callback(location);
            }
        });
    }

    _geocoder(iniPosition, function(address){
        var map = new google.maps.Map(document.getElementById('map')),
            
    })

    set the imformation of the content in the window
    info_config.forEach(function(e, i){
        infoWindows[i] = new google.maps.infoWindows({
            content: e
        });
    });

    
    //mark the marker
}*/