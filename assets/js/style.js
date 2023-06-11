window.onload= setCountyOption();

function setCountyOption(){
	var countiesBar= document.getElementById("select-county");
	counties.forEach((ele)=>{
		var county= document.createElement("option");
		county.value= ele;
		county.innerHTML= ele;
		countiesBar.appendChild(county);
	});

	$('#countySelect').each(setOption);
	$('#districtSelect').each(setOption);

	// Toggling the `.active` state on the `.sel`.
	$('.sel').click(function() {
		$(this).toggleClass('active');
	});

	// Toggling the `.selected` state on the options.
	$('.sel__box__options').click(selectOption);
}

/* ===== Logic for creating fake Select Boxes ===== */
function setOption(){
	$(this).children('select').css('display', 'none');

	var $current = $(this);

	$(this).find('option').each(function(i) {
		if (i == 0) {
			$current.prepend($('<div>', {
				class: $current.attr('class').replace(/sel/g, 'sel__box')
			}));

			if($current.children("span").length==0){
				var placeholder = $(this).text();
				var idName= 'targetCounty';
				if(placeholder==='鄉鎮市區')
					idName= 'targetDistrick';
				
				$current.prepend($('<span>', {
					class: $current.attr('class').replace(/sel/g, 'sel__placeholder'),
					text: placeholder,
					id: idName,
					'data-placeholder': placeholder
				}));
			}
			return;
		}

		$current.children('div').append($('<span>', {
			class: $current.attr('class').replace(/sel/g, 'sel__box__options'),
			text: $(this).text()
		}));
	});
}
  
function selectOption(){
	var txt = $(this).text();
	var index = $(this).index();

	$(this).siblings('.sel__box__options').removeClass('selected');
	$(this).addClass('selected');

	var $currentSel = $(this).closest('.sel');
	$currentSel.children('.sel__placeholder').text(txt);
	$currentSel.children('select').prop('selectedIndex', index + 1);

	if($('#districtSelect .sel__box').children().length>0){
		$('#districtSelect .sel__box').empty();
		$('#select-district').empty();
		$('#select-district').append('<option value="" disabled="">鄉鎮市區</option>')
	}
	var districkBar= document.getElementById("select-district");
	Districts[txt].forEach((ele)=>{
		var districk= document.createElement("option");
		districk.value= ele;
		districk.innerHTML= ele;
		districkBar.appendChild(districk);
	});
	$('#districtSelect').each(setOption);

	// Toggling the `.selected` state on the options.
	$('#districtSelect .sel__box__options').click(setDistrictOption);
}

function setDistrictOption(){
	var txt = $(this).text();
	var index = $(this).index();

	$(this).siblings('.sel__box__options').removeClass('selected');
	$(this).addClass('selected');

	var $currentSel = $(this).closest('.sel');
	$currentSel.children('.sel__placeholder').text(txt);
	$currentSel.children('select').prop('selectedIndex', index + 1);

	
	//send address to find
	var address= $('#targetCounty').text()+$('#targetDistrick').text();
	sendAddress('selectArea', address);
}

function inputOption(divID){
	var itemBar= document.getElementById(divID);
	counties.forEach((ele)=>{
		var item= document.createElement("option");
		item.value= ele;
		item.innerHTML= ele;
		countiesBar.appendChild(county);
	});
}
