/* 다운로드시 로딩바 */
function LoadingWithMask() {
	//화면의 높이와 너비를 구합니다.
	var maskHeight = $(document).height();
	var maskWidth  = window.document.body.clientWidth;
	 
	//화면에 출력할 마스크를 설정해줍니다.
	var mask       ="<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'></div>";
	var loadingImg ='';
	  
	loadingImg +="<div id='loadingImg' style='position:absolute; z-index:9000; display:none; left:0; top:0;'>";
	loadingImg +="<img src='/Spinner.gif' style='position: relative; display: block; margin: 0px auto;top: 291px;'/>";
	loadingImg +="</div>"; 
  
	$('body')
		.append(mask)
		.append(loadingImg)
		
	$('#mask').css({
			'width' : maskWidth
			,'height': maskHeight
			,'opacity' :'0.3'
	});
	$('#loadingImg').css({
			'width' : maskWidth
			,'height': maskHeight
			,'opacity' :'1'
	});
  
	$('#mask').show();  
	$('#loadingImg').show();
}
/* 로딩바 숨기기 */
function closeLoadingWithMask() {
	$('#mask, #loadingImg').hide();
	$('#mask, #loadingImg').remove(); 
}
/* add comma */
function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
/* remove comma */
function removeComma(str) {
	var n = parseInt(str.replace(/,/g,""));
	return n;
}

/* 마스킹 해제 */
function unmasking(obj,type,id){
	$.ajax({
		type: 'post',
		url: "/tech/pages/unmasking",
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
		},
		data: {
			'id':id,
			'type':type
		},
		dataType : "json",
		success: function (resp) {
			if(type == 'P'){
				resp = resp.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
			}
			$(obj).html(resp);
			$(obj).attr('onclick','');
		}
	});
}
function countChars(str) {
  return str.split('').length;
}
/* 마스킹 처리 */
function masking(type,data){
	let originStr = data; 
	if(this.checkNull(originStr) == true){ 
		return originStr; 
	}
	let strLength = originStr.length; 
	let maskingValue = "";

	if(type == 'N'){ // Name
		switch(strLength){
			case 2:
				maskingValue = originStr.substr(0,1) + '*';
				break;
			case 3:
				maskingValue = originStr.substr(0,1) + '*' + originStr.substr(2,1);
				break;
			case 4:
				maskingValue = originStr.substr(0,1) + '**'+ originStr.substr(3,1);
				break;
			case 5:
				maskingValue = originStr.substr(0,1) + '***'+ originStr.substr(4,1);
				break;
			default:
				maskingValue = originStr.substr(0,1) + '****'+ originStr.substr(5,strLength);
				break;
		}
	} else if(type == 'P'){ // Phone
		switch(strLength){
			case 0:
				maskingValue = '';
				break;
			case 10:
				maskingValue = originStr.substr(0,3) + '***' + originStr.substr(6,4);
				break;
			case 11:
				maskingValue = originStr.substr(0,3) + '****'+ originStr.substr(7,4);
				break;
			default:
				maskingValue = originStr.substr(0,4) + '****'+ originStr.substr(8,strLength);
				break;
		}
	} else if(type == 'B') { // Bank Name
		switch(strLength){
			case 0:
				maskingValue = '';
				break;
			case 8:
				maskingValue = originStr.substr(0,3) + '***' + originStr.substr(6,2);
				break;
			case 9:
				maskingValue = originStr.substr(0,3) + '***' + originStr.substr(6,3);
				break;
			case 10:
				maskingValue = originStr.substr(0,3) + '***' + originStr.substr(6,4);
				break;
			case 11:
				maskingValue = originStr.substr(0,3) + '****' + originStr.substr(7,4);
				break;
			case 12:
				maskingValue = originStr.substr(0,4) + '****' + originStr.substr(8, 4);
				break;
			case 13:
				maskingValue = originStr.substr(0,4) + '****' + originStr.substr(8, 5);
				break;
			case 14:
				maskingValue = originStr.substr(0,4) + '****' + originStr.substr(9, 10);
				break;
			case 15:
				maskingValue = originStr.substr(0,5) + '*****' + originStr.substr(9, 10);
				break;
			case 16:
				maskingValue = originStr.substr(0,5) + '*****' + moriginStr.substr( 9, 10);
				break;
			default:
				maskingValue = originStr.substr(0,5) + '*****' + originStr.substr(9, $strlen);
				break;
		}
	} else if(type == 'E') { // Email
		let email = originStr.split('@')[0];
		let email_strlen = email.length;
		switch(email_strlen){
			case 0:
				maskingValue = '';
				break;
			case 2:
				maskingValue = email.substr(0,1) + '*';
				break;
			case 3:
				maskingValue = email.substr(0,1) + '**';
				break;
			case 4:
				maskingValue = email.substr(0,2) + '**';
				break;
			case 5:
				maskingValue = email.substr(0,2) + '***';
				break;
			case 6:
				maskingValue = email.substr(0,3) + '***';
				break;
			default:
				maskingValue = email.substr(0,3) + '****' + email.substr(7, email_strlen);
				break;
		}
		maskingValue = maskingValue + '@' + data.split('@')[1];
	}
	return maskingValue;
}
/* 이름 검색 시 마스킹 처리 */
function ajax_masking(data){
	if(data == null){
		return data;
	}
	if(data.indexOf('-') !== -1){
		let phone = data.split('-')[0];
		let name = data.split('-')[1];
		let return_value = masking('P',phone) + ' ' + masking('N', name);
		return return_value;
	}
	return data;
}
/* 유저 검색 시 검색 후 select 처리 */
function username_ajax_check(id){
	let user_id = $('#user_name_search').val();
	if(user_id == null || user_id == '' || typeof user_id == "undefined"){
		return false;
	}
	$.ajax({
		url: '/tech/pages/getuserinfobyid',
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
		},
		type:'POST',
		dataType: 'json',
		data: {
			"user_id" : user_id,
		},
		success : function(data){
			var user_name = $('#'+id);
			var option = new Option(ajax_masking(data.name), data.id, true, true);
			user_name.append(option).trigger('change');
			// manually trigger the `select2:select` event
			user_name.trigger({
				type: 'select2:select',
				params: {
					data: data
				}
			});
		}
	});
}
/* email 검색 시 검색 후 select 처리 */
function email_ajax_check(id){
	let user_id = $('#user_email_search').val();
	if(user_id == null || user_id == '' || typeof user_id == "undefined"){
		return false;
	}
	$.ajax({
		url: '/tech/pages/getuserinfobyid',
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
		},
		type:'POST',
		dataType: 'json',
		data: {
			"user_id" : user_id,
		},
		success : function(data){
			let user_name = $('#'+id);
			let option = new Option(masking('E',data.email), data.id, true, true);
			user_name.append(option).trigger('change');
			// manually trigger the `select2:select` event
			user_name.trigger({
				type: 'select2:select',
				params: {
					data: data
				}
			});
		}
	});
}
/* 유저 검색 select2 셋팅 */
function user_search_select2(id){
	let user_name = $('#'+id);
	user_name.select2({
		language: lang_type(getLanguage()),
		tags: [],
		pagination: {
			more: true
		},
		ajax: {
			url: '/tech/pages/getuserinfo',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type:'POST',
			dataType: 'json',
			delay: 250,
			cache: true,
			// 검색 쿼리를 만든다.
			data: function (params) {
				return {
					q: params.term,
					page: params.page || 1
				};
			},
			// 결과를 표현한다.
			processResults: function (data,params) {
				params.page = params.page || 1;
				return {
					results: $.map(data, function (item) {
						return {
							text: ajax_masking(item.name),
							id: item.id
						}
					}),
					pagination: {
					  more: (params.page * 30) < data.total_count
					}
				};
			}
		},
		minimumInputLength: 1
	});
}
/* 유저 이메일 검색 select2 셋팅 */
function user_email_select2(id){
	let user_name = $('#'+id);
	user_name.select2({
		language: lang_type(getLanguage()),
		tags: [],
		pagination: {
			more: true
		},
		ajax: {
			url: '/tech/pages/getuseremail',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type:'POST',
			dataType: 'json',
			delay: 250,
			cache: true,
			// 검색 쿼리를 만든다.
			data: function (params) {
				return {
					q: params.term,
					page: params.page || 1
				};
			},
			// 결과를 표현한다.
			processResults: function (data,params) {
				params.page = params.page || 1;
				return {
					results: $.map(data, function (item) {
						return {
							text: masking('E',item.email),
							id: item.id
						}
					}),
					pagination: {
					  more: (params.page * 30) < data.total_count
					}
				};
			}
		},
		minimumInputLength: 1
	});
}
/* 리스트번호 검색 select2 셋팅 */
function list_select2(id,type){
	let list_no = $('#'+id);
	list_no.select2({
		language: lang_type(getLanguage()),
		tags: [],
		pagination: {
			more: true
		},
		ajax: {
			url: '/tech/pages/getlistnumber',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type:'POST',
			dataType: 'json',
			delay: 250,
			cache: true,
			// 검색 쿼리를 만든다.
			data: function (params) {
				return {
					q: params.term,
					page: params.page || 1,
					type : type
				};
			},
			// 결과를 표현한다.
			processResults: function (data,params) {
				console.log(data);
				params.page = params.page || 1;
				return {
					results: $.map(data, function (item) {
						return {
							text: item.id,
							id: item.id
						}
					}),
					pagination: {
					  more: (params.page * 30) < data.total_count
					}
				};
			}
		},
		minimumInputLength: 1
	});
}
function checkNull(str){
	if(typeof str == "undefined" || str == null || str == ""){ 
		return true; 
	} else { 
		return false; 
	}
}
function getLanguage(){
	var cookie = getCookie('Language');
	return cookie;
}
function lang_type(lang){
	if(lang == 'ko_KR'){
		lang = 'ko';
	} else {
		lang = 'en';
	}
	return lang;
}
/* 권한 체크 */
function get_permission_level(type,value){
	$.ajax({
		url: '/tech/pages/getpermissionlevel',
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
		},
		type:'POST',
		data: {
			'type' : type
		},
		success : function(data){
			if(data == 'success'){
				if(type == 'download'){
					real_export(value);
				}
			} else {
				alert('다운로드 권한이 없습니다.');
				return ;
			}
		}
	});
	return ;
}
/* csv export */
function export_f(v) {
	get_permission_level('download',v);
}
/* csv export */
function real_export(v){
	$('#export').val(v);
	$("#frm").submit();
	$('#export').val('');
}
function datepicker_set(id){
	$('#'+id).datepicker({
		format: 'yyyy-mm-dd',
		maxDate: '0'
	});
}
/* coin type submit */
function form_submit(coin_id, id){
	let coin = $('#'+coin_id);
	if(coin.val() == '' || coin.val() != id){
		coin.val(id);
	} else {
		coin.val('');
	}
	$('#frm').submit();
}
function tokenButtonList() {
	const checkbox = document.querySelector('.token-button-list-checkbox');
	const tokenButtonListContainer = document.querySelector('.token-button-list-container');

	checkbox.addEventListener('change', function(event) {
		const checked = event.target.checked;
		if (checked) {
			tokenButtonListContainer.classList.add('scrolled');
			return;
		}
		tokenButtonListContainer.classList.remove('scrolled');
	});
}
