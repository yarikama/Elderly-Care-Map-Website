// 設定 API URL
let baseURL = './function/search_addr.php';
let radiusButtons = document.querySelectorAll('.radius-button');
radiusButtons.forEach(button => {
    button.addEventListener('click', function() {
        let radius = this.dataset.radius;
        geocodeAddress(radius);
    });
});


document.addEventListener("DOMContentLoaded", function() {
    let countySelect = document.getElementById('select-county');
    let districtSelect = document.getElementById('select-district');

    // 從資料庫獲取縣市列表並添加到選單
    fetch(baseURL + '?action=getCounties')
    .then(response => response.json())
    .then(data => {
        data.forEach(county => {
            let option = document.createElement('option');
            option.text = county;
            option.value = county;
            countySelect.add(option);
        });
    })
    .catch(error => console.log(error));

    // 當選擇縣市時，清除並更新鄉鎮市區選單
    countySelect.addEventListener('change', function() {
        // 清除原有的鄉鎮市區選項
        while (districtSelect.options.length > 1) {
            districtSelect.remove(1);
        }

        // 根據選擇的縣市更新鄉鎮市區列表
        let selectedCity = this.value;
        fetch(baseURL + '?action=getDistricts&city=' + selectedCity)
        .then(response => response.json())
        .then(data => {
            data.districts.forEach(district => {
                let option = document.createElement('option');
                option.text = district.dist;
                option.value = JSON.stringify({ name: district.dist, latitude: district.latitude, longitude: district.longitude });
                districtSelect.add(option);
            });

            // 移動地圖中心到選中的縣市
            if (data.districts.length > 0) {
                let cityCenter = new google.maps.LatLng(data.districts[0].latitude, data.districts[0].longitude);
                map.setCenter(cityCenter);
                // 清除舊標記並創建新標記
                markers.forEach(marker => marker.setMap(null));
                markers = createMarkers(map, data);
            }      
        })
        .catch(error => console.log(error));
    });

    districtSelect.addEventListener('change', function() {
        // 根據選中的縣市和區縣獲取長照中心
        let selectedCounty = countySelect.value;
        let selectedDistrict = JSON.parse(this.value);

        // 重新設定地圖的中心
        map.setCenter(new google.maps.LatLng(selectedDistrict.latitude, selectedDistrict.longitude));
        
        fetch(baseURL + '?action=getCenters&city=' + selectedCounty + '&dist=' + selectedDistrict.name)
        .then(response => response.json())
        .then(data => {
            // 清除舊的標記
            markers.forEach(marker => marker.setMap(null));
            // 確保此時的 map 是已經被初始化
            markers = createMarkers(map, data);
            console.log(data); // 打印出獲取的長照中心數據
        })
        .catch(error => console.log(error));
    });

    // 當按鈕被點擊時調用 geocodeAddress 函數
    let searchBtn = document.getElementById('searchBtn');
    searchBtn.addEventListener('click', geocodeAddress);
});

// 搜尋地址後將地圖中心設為該地址並顯示該區域的長照地點
function geocodeAddress(radius) {

    let address = document.getElementById('address').value;
    let geocoder = new google.maps.Geocoder();

    if(address==''){
        alert('請輸入地址');
        return
    }
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            let result = results[0];
            let position = result.geometry.location;
            map.setCenter(position);

            console.log(result)
            // 新增一個地圖標記
            if (window.marker) {
                window.marker.setMap(null);
            }
            

            let marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: 'images/originLocation.png'
            });
            
            // 將新建的標記存入 window.marker 變數
            window.marker = marker;

            if (window.circle) {
                window.circle.setMap(null);
            }
            window.circle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: position,
                radius: parseFloat(radius)// 將傳入的 radius 參數轉換為數值
            });

            // 獲取該地址的縣市和區縣
            let city, dist;
            for (let i = 0; i < results[0].address_components.length; i++) {
                let component = results[0].address_components[i];
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
            
            if(dist){
                // 打印縣市和鄉鎮市區信息
                console.log('縣市:', city);
                console.log('鄉鎮市區:', dist);
                // 根據選中的縣市和區縣獲取長照中心
                fetch(baseURL + '?action=getCenters&city=' + city + '&dist=' + dist)
                .then(response => response.json())
                .then(data => {
                // 清除舊的標記
                    markers.forEach(marker => marker.setMap(null));
                    // 確保此時的 map 是已經被初始化
                    markers = createMarkers(map, data, position);
                    console.log(data); // 打印出獲取的長照中心數據
                })
                .catch(error => console.log(error));
            }
            else{
                fetch(baseURL + '?action=getDistricts&city=' + city)
                .then(response => response.json())
                .then(data => {
                    // 清除舊的標記
                    markers.forEach(marker => marker.setMap(null));
                    // 確保此時的 map 是已經被初始化
                    markers = createMarkers(map, data, position);
                    console.log(data); // 打印出獲取的長照中心數據
                })
                .catch(error => console.log(error));
            }
            
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function createMarkers(map, data, currentPosition = null) {
    console.log('currentPosition:', currentPosition);
    let temp = data.careCenters ? data.careCenters : data;
    let infoWindows = [];
    if (window.markers) {
        for (let i = 0; i < window.markers.length; i++) {
            window.markers[i].setMap(null);
        }
    }

    window.markers = temp.map(center => {
        let position = new google.maps.LatLng(center.latitude, center.longitude);
        let distance = null;
        if (currentPosition) {
            // 使用 computeDistanceBetween 函數來計算當前位置與中心的距離（以公尺為單位）。
            distance = google.maps.geometry.spherical.computeDistanceBetween(currentPosition, position);
            distance = (distance / 1000).toFixed(2); // 轉換為公里並保留兩位小數
        }
        console.log('distance:', distance);
        let icon;
        let rest = parseInt(center.housing_num) - parseInt(center.providing_num);
        if (rest > 10) {
            icon = 'images/greenpin.png';
        } else if (rest <= 10 && rest > 0) {
            icon = 'images/yellowpin.png';
        } else {
            icon = 'images/redpin.png';
        }

        let marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: icon
        });
        let content = '<div class="info-window">' +
        '<h2 class="info-title" style="border-bottom: 1px solid #ccc;">' + center.ins_name + '</h2>' +
        '<div style="display: flex; align-items: center; margin-bottom: 10px;">' +
        '<i class="fas fa-info-circle" style="font-size: 20px; margin-right: 10px;"></i>' +
        '<h3 class="info-subtitle" style="font-size: 18px; color: orange;">資訊</h3>' +
        '</div>' +
        '<p class="info-details" style="font-size: 16px;">' +
        '<strong>地址 :</strong> ' + center.addr + '<br>' +
        '<strong>管理員 :</strong> ' + center.manager + '<br>' +
        '<strong>電話 :</strong> ' + center.phone + '<br>' +
        '<strong>網站 :</strong><a href="' + center.website + '">' + center.website + '</a><br>' +
        '<div style="display: flex; align-items: center; margin-top: 10px;">' +
        '<i class="fas fa-ruler" style="font-size: 20px; margin-right: 10px;"></i>' +
        '<span class="info-subtitle" style="font-size: 18px; color: orange;">距離我 <span style="color: red;">' + distance +'</span><span style="color: orange;"> 公里</span>' +
        '</div>' +        '<div style="display: flex; align-items: center; margin-top: 10px;">' +
        '<i class="fas fa-bed" style="font-size: 20px; margin-right: 10px;"></i>' +
        '<h3 class="info-subtitle" style="font-size: 18px; color: orange;">床位數</h3>' +
        '</div>' +
        '<p class="info-details" style="font-size: 16px;">' +
        '<strong>養護型床位 :</strong> ' + center.nurse_num + '<br>' +
        '<strong>安養型床位 :</strong> ' + center.caring_num + '<br>' +
        '<strong>長照型床位 :</strong> ' + center.long_caring_num + '<br>' +
        '<strong>失智照顧型床位 :</strong> ' + center.dem_num + '<br>' +
        '<strong>總床位數 :</strong> ' + center.housing_num + '<br>' +
        '<strong>總收容人數 :</strong> ' + center.providing_num + '<br>' +
        '<strong>總剩餘床位數 :</strong> ' + rest + '<br>' +
        '</p>' +
        '<form method="post" action="process.php">' +
        '<input type="hidden" name="ins_num" value="' + center.ins_num + '">' +
        '<button type="submit" name="good_list" class="btn btn-primary me-2">加入喜愛列表</button>' +
        '</form>' +
        '</div>';

        let infoWindow = new google.maps.InfoWindow({
            content: content
        });

        marker.addListener('click', function() {
            if (infoWindows.length >= 2) {
                infoWindows[0].close();
                infoWindows.shift();
            }
            infoWindow.open(map, marker);
            infoWindows.push(infoWindow);
        });

        return marker;
    });

    return window.markers;
}
