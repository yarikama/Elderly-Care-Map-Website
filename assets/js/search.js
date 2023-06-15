// 設定 API URL
let baseURL = './function/search_addr.php';

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
            data.forEach(district => {
                let option = document.createElement('option');
                option.text = district.dist;
                option.value = JSON.stringify({ name: district.dist, latitude: district.latitude, longitude: district.longitude });
                districtSelect.add(option);
            });
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
            if (window.markers) {
                for (let i = 0; i < window.markers.length; i++) {
                    window.markers[i].setMap(null);
                }
            }
            // 建立新的標記並添加到地圖
            console.log(data)
            window.markers = data.map(center => {
                let position = new google.maps.LatLng(center.latitude, center.longitude);
                let marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: 'images/redpin.png'
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
                '<i class="fas fa-bed" style="font-size: 20px; margin-right: 10px;"></i>' +
                '<h3 class="info-subtitle" style="font-size: 18px; color: orange;">床位數</h3>' +
                '</div>' +
                '<p class="info-details" style="font-size: 16px;">' +
                '<strong>養護型床位 :</strong> ' + center.nurse_num + '<br>' +
                '<strong>安養型床位 :</strong> ' + center.caring_num + '<br>' +
                '<strong>長照型床位 :</strong> ' + center.long_caring_num + '<br>' +
                '<strong>失智照顧型床位 :</strong> ' + center.dem_num + '<br>' +
                '<strong>使用中床位 :</strong> ' + center.housing_num + '<br>' +
                '<strong>提供床位 :</strong> ' + center.providing_num + '<br>' +
                '</p>' +
                '</div>';

                // 建立資訊視窗
                let infoWindow = new google.maps.InfoWindow({
                    content: content
                });

                // 綁定點擊事件
                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });

                return marker;
            });

            console.log(data); // 打印出獲取的長照中心數據
        })
        .catch(error => console.log(error));
    });

    // 當按鈕被點擊時調用 geocodeAddress 函數
    let searchBtn = document.getElementById('searchBtn');
    searchBtn.addEventListener('click', geocodeAddress);
});

// 搜尋地址後將地圖中心設為該地址並顯示該區域的長照地點
function geocodeAddress() {
    let address = document.getElementById('address').value;
    let geocoder = new google.maps.Geocoder();

    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            let result = results[0];
            let position = result.geometry.location;
            map.setCenter(position);

            // 新增一個地圖標記
            let marker = new google.maps.Marker({
                position: position,
                map: map
            });

            // 獲取該地址的縣市和區縣
            let city, dist;
            for (let i = 0; i < results[0].address_components.length; i++) {
                let component = results[0].address_components[i];
                if (component.types.includes('administrative_area_level_1')) {
                    city = component.short_name;
                } else if (component.types.includes('administrative_area_level_3')) {
                    dist = component.short_name;
                }
            }

            // 打印縣市和鄉鎮市區信息
            console.log('縣市:', city);
            console.log('鄉鎮市區:', dist);

            // 根據選中的縣市和區縣獲取長照中心
            fetch(baseURL + '?action=getCenters&city=' + city + '&dist=' + dist)
            .then(response => response.json())
            .then(data => {
                // 清除舊的標記
                if (window.markers) {
                    for (let i = 0; i < window.markers.length; i++) {
                        window.markers[i].setMap(null);
                    }
                }
                // 建立新的標記並添加到地圖
                window.markers = data.map(center => {
                    let position = new google.maps.LatLng(center.latitude, center.longitude);
                    let marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        icon: 'images/redpin.png'
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
                    '<i class="fas fa-bed" style="font-size: 20px; margin-right: 10px;"></i>' +
                    '<h3 class="info-subtitle" style="font-size: 18px; color: orange;">床位數</h3>' +
                    '</div>' +
                    '<p class="info-details" style="font-size: 16px;">' +
                    '<strong>養護型床位 :</strong> ' + center.nurse_num + '<br>' +
                    '<strong>安養型床位 :</strong> ' + center.caring_num + '<br>' +
                    '<strong>長照型床位 :</strong> ' + center.long_caring_num + '<br>' +
                    '<strong>失智照顧型床位 :</strong> ' + center.dem_num + '<br>' +
                    '<strong>使用中床位 :</strong> ' + center.housing_num + '<br>' +
                    '<strong>提供床位 :</strong> ' + center.providing_num + '<br>' +
                    '</p>' +
                    '</div>';
    
                    // 建立資訊視窗
                    let infoWindow = new google.maps.InfoWindow({
                        content: content
                    });
    
                    // 綁定點擊事件
                    marker.addListener('click', function() {
                        infoWindow.open(map, marker);
                    });
                    // ...此處省略了為標記添加infoWindow的代碼...

                    return marker;
                });

                console.log(data); // 打印出獲取的長照中心數據
            })
            .catch(error => console.log(error));
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}
