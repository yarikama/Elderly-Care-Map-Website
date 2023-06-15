<?php
session_start();
$title = "長照地圖";
require_once "./template/header.php";
?>

<div style="width: 300px; padding: 20px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); border-radius: 8px; position: absolute; right: 40px; top: 50px; z-index: 100; background-color: gray;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <input type="address" id="address" spellcheck="false" placeholder="請輸入地址" autocomplete="off" style="width: 80%; padding: 10px; border: none; border-bottom: 1px solid #ccc; font-size: 16px;">
        <button id="searchBtn" style="background-color: #008CBA; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">搜索</button>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <select name="select-profession" id="select-county" style="width: 45%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px;">
            <option value="" disabled>縣市</option>
        </select>
        <select name="select-superpower" id="select-district" style="width: 45%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px;">
            <option value="" disabled>鄉鎮市區</option>
        </select>
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
  </style>

<!-- Google Maps Integration -->
<div id="map" style="height: 700px;"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVRZ4YS4Xzl2lAV6kv5tFdFWs7T3GAYiU&callback=initMap">
</script>
<script src="assets/js/main.js"></script>
<script src="assets/js/search.js"></script>
<!--script src="assets/js/style.js"></script-->
<!--script src="assets/js/districts.js"></script-->


<?php
	require_once "./template/footer.php";
?>
