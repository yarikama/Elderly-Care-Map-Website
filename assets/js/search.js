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
            window.markers = data.map(center => {
                let position = new google.maps.LatLng(center.latitude, center.longitude);
                let marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: 'images/redpin.png'
                });
                let content = '<div class="info-window">' +
                '<h2 class="info-title" style="border-bottom: 1px solid #ccc;">' + center.ins_name + '</h2>' +
                '<p class="info-details" style="font-size: 16px;">' +
                '<strong>地址 :</strong> ' + center.addr + '<br>' +
                '<strong>管理員 :</strong> ' + center.manager + '<br>' +
                '<strong>電話 :</strong> ' + center.phone + '<br>' +
                '<strong>護理人數 :</strong> ' + center.nurse_num + '<br>' +
                '<strong>提供人數 :</strong> ' + center.providing_num + '<br>' +
                '網站 : <a href="' + center.website + '">' + center.website + '</a>' +
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
