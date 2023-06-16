var map;
var markers = [];
var location;
var Locate = {lat: 25.04, lng: 121.512};
const imageRoute= ["images/redpin.png","images/yellowpin.png","images/greenpin.png"];
const highLevel= 500, lowLevel= 100;

function getPosition(){
    if(navigator.geolocation){
        return new Promise((resolve, reject) => {
            let option = {
                enableAcuracy:false, // 提高精確度
                maximumAge:0, // 設定上一次位置資訊的有效期限(毫秒)
                timeout:10000 // 逾時計時器(毫秒)
            };
            navigator.geolocation.getCurrentPosition(resolve, reject, option);
        });
    }
    else
        alert("Can't locate your position.");
}

function errorCallback(error){
    console.log(error.message);
}

function initMap() {
    let districtSelect = document.getElementById('select-district');
    let countySelect = document.getElementById('select-county');
    let baseURL = './function/search_addr.php';
    var myLatLng;
    var geocoder = new google.maps.Geocoder();
    console.log('myLatLng:' + myLatLng);
    getPosition()
    .then((position) => {
        myLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};

        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 15,
            mapTypeControl: false,
            scaleControl: true,
            streetViewControl: false,
            fullscreenControl: false,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM
            }
        });

        // set center marker
        var Center = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: '你的位置',
            icon: 'images/originLocation.png'
        });

    // geocode the current position to get city and district
        geocoder.geocode({location: myLatLng}, function(results, status) {
            if (status === 'OK') {
                let result = results[0];
                let city, dist;
                for (let i = 0; i < result.address_components.length; i++) {
                    let component = result.address_components[i];
                    if (component.types.includes('administrative_area_level_1')) {
                        city = component.short_name;
                    } else if (component.types.includes('administrative_area_level_2')) {
                        city = component.short_name;
                    } else if (component.types.includes('administrative_area_level_3')) {
                        dist = component.short_name;
                    }
                }
                if(city[0]==='台')
                    city= city.replace('台', '臺');

                // Based on the city and district, get the care centers
                if(dist){
                    fetch(baseURL + '?action=getCenters&city=' + city + '&dist=' + dist)
                    .then(response => response.json())
                    .then(data => {
                        markers = createMarkers(map, data, myLatLng);
                        markers.push(Center);
                        console.log(data); // log the data
                    })
                    .catch(error => console.log(error));
                }
                else{
                    fetch(baseURL + '?action=getDistricts&city=' + city)
                    .then(response => response.json())
                    .then(data => {
                        markers = createMarkers(map, data, myLatLng);
                        console.log(data); // log the data
                        markers.push(Center);

                    })
                    .catch(error => console.log(error));
                }
            } else {
                console.error('Geocoder failed due to: ' + status);
            }
        });
    })
    .catch(error => errorCallback(error))
}