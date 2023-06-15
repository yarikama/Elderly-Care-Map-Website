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
    var location;
    var geocoder = new google.maps.Geocoder();
    markers = [];

    // set current position
    getPosition()
    .then((position) => {
        var myLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};

        // set current position to the center of map
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
            icon: imageRoute[2]
        });
        markers.push(Center);

        // Add event listener for district select change
        districtSelect.addEventListener('change', function() {
            // 清除原有的标记
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
            
            // 根據選中的縣市和區縣獲取長照中心
            let selectedCounty = countySelect.value;
            let selectedDistrict = this.value;
            fetch(baseURL + '?action=getCenters&county=' + selectedCounty + '&district=' + selectedDistrict)
            .then(response => response.json())
            .then(data => {
            }).catch(error => console.log(error));
        });
    })
    .catch(error => errorCallback(error))
    window.addEventListener('resize', function() {
        // Calculate the new zoom level based on the initial zoom and the window dimensions
        var newZoom = Math.round(initialZoom * (window.innerWidth / initialWindowWidth));
    
        // Set the new zoom level on the map
        map.setZoom(newZoom);
      });
}
