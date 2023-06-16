<?php
session_start();
$title = "長照地圖";
require_once "./template/header.php";
?>

<div style="width: 350px; padding: 20px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); border-radius: 8px; position: fixed; right: 50px; top: 50px; z-index: 100; background-color: gray;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <input type="address" id="address" spellcheck="false" placeholder="請輸入地址" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 80%; padding: 10px; border: none; border-bottom: 1px solid #ccc; font-size: 16px;">
      <button id="searchBtn" style="background-color: #008CBA; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">搜索</button>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <select name="select-profession" id="select-county" style="width: 45%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px;">
            <option value="">縣市</option>
        </select>
        <select name="select-superpower" id="select-district" style="width: 45%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px;">
            <option value="">鄉鎮市區</option>
        </select>
    </div>
    <div style="margin-top: 20px;">
    <button class="radius-button" data-radius="0" style="background-color: black;">無</button>
    <button class="radius-button" data-radius="500" style="background-color: brown;" >0.5公里</button>
    <button class="radius-button" data-radius="1000"  style="background-color: green;">1公里</button>
    <button class="radius-button" data-radius="1500" >1.5公里</button>
    </div>
    <div style="margin-top: 10px; padding: 10px; background-color: 	#003060	; border-radius: 8px; border: 2px solid block; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); text-align: center;">
    <p>
        <span style="color: #7AFEC6;">綠燈</span>
        <span style="color: white;">代表空床位超過 10 個</span>
        <br>
        <span style="color: #FFED97;">黃燈</span>
        <span style="color: white;">代表空床位數在 10 個以下</span>
        <br>
        <span style="color: #FFB5B5;">紅燈</span>
        <span style="color: white;">代表沒有空床位數</span>
    </p>
</div>

</div>

<style>
    .info-title {
      color: #D4A30B;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .info-details {
      font-size: 14px;
      margin-bottom: 5px;
    }

    .info-details a {
      color: #008CBA;
      text-decoration: none;
    }

    .info-details a:hover {
      text-decoration: underline;
    }

    .info-window {
      max-width: 500px;
      background-color: #fff;
      padding: 10px;
      border-radius: 4px;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }
    .radius-button {
    /* 按鈕背景色 */
    background-color: #4CAF50;
    /* 文字顏色 */
    color: white;
    /* 文字大小 */
    font-size: 14px;
    /* 邊框寬度 */
    border: 30px;
    /* 邊框半徑 (圓角) */
    border-radius: 5px;
    /* 上下邊距 */
    padding: 8px 13px;
    /* 鼠標滑過時的樣式 */
    transition: all 0.5s;
    cursor: pointer;
    }

    .radius-button:hover {
        /* 鼠標滑過時的背景色 */
        background-color: #45a049;
    }

    .radius-button:active {
        /* 點擊按鈕時的背景色 */
        background-color: #3e8e41;
}
    body, html{
      position: relative;
      height: 100%;
      width: 100%;
    }

    #main{
      position: relative;
      height: 85%;
      width: 100%;
    }
  </style>

<!-- Google Maps Integration -->
<div id="map" style="height: 100%; width:100%"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVRZ4YS4Xzl2lAV6kv5tFdFWs7T3GAYiU&libraries=geometry&callback=initMap">
</script>
<script src="assets/js/main.js"></script>
<script src="assets/js/search.js"></script>
<!--script src="assets/js/style.js"></script-->
<!--script src="assets/js/districts.js"></script-->


<?php
	require_once "./template/footer.php";
?>
