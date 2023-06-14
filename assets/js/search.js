// 設定 API URL
let baseURL = './function/search_addr.php';

document.addEventListener("DOMContentLoaded", function() {
    let countySelect = document.getElementById('select-county');
    let districtSelect = document.getElementById('select-district');

    // 從資料庫獲取縣市列表並添加到選單
    fetch(baseURL + '?action=getCounties').then(response => response.json()).then(data => {
        data.forEach(county => {
            let option = document.createElement('option');
            option.text = county;
            option.value = county;
            countySelect.add(option);
        });
    }).catch(error => console.log(error));

    // 當選擇縣市時，清除並更新鄉鎮市區選單
    countySelect.addEventListener('change', function() {
        // 清除原有的鄉鎮市區選項
        while (districtSelect.options.length > 1) {
            districtSelect.remove(1);
        }

        // 根據選擇的縣市更新鄉鎮市區列表
        let selectedCounty = this.value;
        fetch(baseURL + '?action=getDistricts&county=' + selectedCounty).then(response => response.json()).then(data => {
            data.forEach(district => {
                let option = document.createElement('option');
                option.text = district;
                option.value = district;
                districtSelect.add(option);
            });
        }).catch(error => console.log(error));
    });

    // 當選擇區縣時，搜尋該地區的長照中心
    districtSelect.addEventListener('change', function() {
        // 根據選中的縣市和區縣獲取長照中心
        let selectedCounty = countySelect.value;
        let selectedDistrict = this.value;
        fetch(baseURL + '?action=getCenters&county=' + selectedCounty + '&district=' + selectedDistrict)
        .then(response => response.json())
        .then(data => {
            console.log(data); // 打印出獲取的長照中心數據
        }).catch(error => console.log(error));
    });
});
