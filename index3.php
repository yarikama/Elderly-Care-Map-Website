<!DOCTYPE HTML>
<html>

<head>
	<title>Long term care</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="assets/css/main.css" />
	<noscript>
		<link rel="stylesheet" href="assets/css/noscript.css" />
	</noscript>
</head>

<body>
	<header class="searchField">
		<div class="searchBar">
			<input type="address" id="address" spellcheck="false" placeholder="請輸入地址" autocomplete="off">
			<i class="fa-solid fa-magnifying-glass fas fa-file" id="searchBtn"></i>
		</div>
		<div class="selectField">
			<div class="sel sel--black-panther" id="countySelect">
				<select name="select-profession" id="select-county">
					<option value="" disabled>縣市</option>
				</select>
			</div>
			<div class="sel sel--superman" id="districtSelect">
				<select name="select-superpower" id="select-district">
					<option value="" disabled>鄉鎮市區</option>
				</select>
			</div>
		</div>
	</header>
	<div id="map" class="map"></div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=yourkey&callback=initMap">
		</script>
	<script src="assets/js/districts.js"></script>
	<script src="assets/js/main.js"></script>
	<script src="assets/js/style.js"></script>
</body>

</html>