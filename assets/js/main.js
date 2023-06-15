var map;
var markers = [];
var location;
var Locate = {lat: 25.04, lng: 121.512};
const imageRoute= ["images/redpin.png","images/yellowpin.png","images/greenpin.png"];
const highLevel= 500, lowLevel= 100;

$('#searchBtn').click(getTypingAddress);

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
                console.log(data); // 打印出獲取的長照中心數據

                data.forEach(center => {
                    let lat = center.latitude; // 从你的数据中获取纬度
                    let lng = center.longitude; // 从你的数据中获取经度

                    // 创建并添加一个新的标记
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        map: map
                    });
                    markers.push(marker);
                });
            }).catch(error => console.log(error));
        });
    })
    .catch(error => errorCallback(error))
}


function getTypingAddress(){
    sendAddress('typeAddress', $('#address').val());
}

function geocodeAddress() {
    // 獲取用戶輸入的地址
    let address = document.getElementById('address').value;

    // 建立一個 Geocoder 物件來進行地理編碼
    let geocoder = new google.maps.Geocoder();

    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            // 如果地理編碼成功，將地圖的中心點設定為地址的地理位置
            map.setCenter(results[0].geometry.location);

            // 將標記添加到地圖上
            new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

/*function sendAddress(method, address){
    var dataJSON= {};
    dataJSON[method]= address;
    $.ajax({
        url: 'select.php',
        data: JSON.stringify(dataJSON),
        type: "POST",
        dataType: "json",
        contentType: "application/json;charset=utf-8",
        success: function(resp){
            searchMap(address);
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(xhr.status);
            alert(thrownError);
        }
    });
}*/


// send data to php
// data type:
//      by select county and districk   => {"selectArea":"新北市汐止區"}
//      by typing address               => {"typeAddress":"陽明交通大學"}
/*function sendAddress(method, address){
    var dataJSON= {};
    dataJSON[method]= address;
    $.ajax({
        url: 'select.php',
        data: JSON.stringify(dataJSON),
        type: "POST",
        dataType: "json",
        contentType: "application/json;charset=utf-8",
        success: function(resp){
            alert(resp);
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(xhr.status);
            alert(thrownError);
        }
    });
}*/

/*function searchMap(address){
    geocoder.geocode({
        'address': address
    }, function(results, status){
        if(status == google.maps.GeocoderStatus.OK){
            var location = results[0].geometry.location;
            map.setCenter(location);
        }
    });
}*/

// //change the center after searching
// function searchMap(address, callback){
//     geocoder.geocode({
//         'address': address//地址
//     }, function(results, status){
//         if(status == google.naps.GeocoderStatus.OK){
//             location = results[0].geometry.location;
//             callback(location);
//         }
//     });
// }

// searchMap('地址', function(address){
//     map.Center = address;
//     markers = [];
//     var info__config = [];
//     var infoWindows = [];
//     var newMarkers = [];

//     //mark the institute nearby the address or institute where, the users input
//     nearbyIns.forEach((institute)=>{
//         //classifying institute into three level
//         var image;
//         if(institute['bedNum']>=highLevel)
//             image= imageRoute[2];
//         else if(institute['bedNum']>=lowLevel)
//             image= imageRoute[1];
//         else
//             image= imageRoute[0];

//         //add marker
//         var tmp= new google.maps.Marker({
//             position: institute['pos'],
//             map: map,
//             title: institute['name'],
//             icon: image
//         });
//         markers.push(tmp);
        
//         //add each info to info_config array
//         var tmpInfo = '<h2>' + func_name + '</h2>' 
//                     + '<p>服務類型 : ' + type 
//                     + '<br>電話 : ' + phone 
//                     + '<br>網站 : <a href="' + func_website + '"></a>'
//                     + 'Email : ' + email + '</p>';
//         info__config.push(tmpInfo)
//     })

//     //set the content in the window
//     info__config.forEach(function (e, i){
//         infoWindows[i] = new google.maps.infoWindows({
//             content: e
//         });
//     });

//     //標出marker, 點擊marker跳出資訊
//     markers.forEach(function(e, i){
//         searchMap(e.address, function(address){
//             var marker = {
//                 position: address,
//                 map: map
//             }
//             newMarkers[i] = new google.maps.Marker(marker);
//             newMarkers[i] = setMap(map);
//             newMarkers[i].addListener('click', function(){
//                 infoWindows[i].open(map, newMarkers[i]);
//             });
//         });
//     });
// })