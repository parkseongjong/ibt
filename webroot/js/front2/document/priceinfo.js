let settingList;
let mainBalanceValue = 0;
$(document).ready(function() {
	mainBalanceValue = parseFloat($("#mainBalance").val());
	settingList = getSettingList();

	for (let i = 0; i < settingList.length; i++) { 	
		//console.log(settingList[i]);
		eval(settingList[i]['short_name']+'Value'+'='+settingList[i]['amount']);
		eval('mainBalance'+settingList[i]['short_name']+'='+$("#mainBalance"+settingList[i]['short_name']).val());
		if(mainBalanceValue < 50000){
			$("#ifDepositW").show();
			$("#ifDeposit"+settingList[i]['short_name']).hide();
		} else {
			if(eval('mainBalance'+settingList[i]['short_name']) < parseFloat(eval(settingList[i]['short_name']+'Value'))){
				$("#ifDeposit"+settingList[i]['short_name']).show();
			} else {
				$("#ifDeposit"+settingList[i]['short_name']).hide();
			}
		}
	}
    //document.getElementById("cp_btn").addEventListener("click", copy_account);
});

function getSettingList(){
	let result;
	$.ajax({
        url: '/front2/document/priceinfo',
        type: 'post',
		async: false,
        data: {
        },
		dataType : "json",
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function (resp) {
			result = resp;
        }
    });
	return result;
}

function copy_account() {
    let copyText = document.getElementById("act_spn");
    let textArea = document.createElement("textarea");
    textArea.value = copyText.textContent;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("Copy");
    textArea.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const couponArea = document.querySelector('.tp3-ctc-coupon-area');
    const tp3CountUp = couponArea.querySelector('#tp3-coupon-up');
    let tokenAmount = {};
	for (let j = 0; j < settingList.length; j++) {
		let coin_short_name = settingList[j]['short_name'].toLowerCase();

        //추가 up down 작동 변경 
		couponArea.querySelector('#'+coin_short_name+'-coupon-up').addEventListener('click', function() { couponCounter(coin_short_name, 'up'); });
		couponArea.querySelector('#'+coin_short_name+'-coupon-down').addEventListener('click', function() { couponCounter(coin_short_name, 'down'); });

		tokenAmount[coin_short_name] = eval(settingList[j]['short_name']+'Value');
		couponArea.querySelector('#coupon-purchase-'+coin_short_name).addEventListener('click', function() { onClickPurchaseCoupon(coin_short_name); });
	}
	
    const krwAmount = 50000;

    // TP3 쿠폰, CTC 쿠폰 갯수 변경 이벤트
    function couponCounter(type, upDown) {
       // console.log('coupon Counter ', type, upDown);
        const addedValue = upDown === 'up' ? 1 : -1;

        // Count Number Increase / Decrease
        // ID: tp3-count / ctc-count
        const foundCountNumber = couponArea.querySelector('#' + type + '-count');
        const foundCountNumberValue = parseInt(foundCountNumber.innerText) + addedValue;
        // 1 이하로 내려갈 수 없음
        if (foundCountNumberValue <= 0) {
            return;
        }
        foundCountNumber.innerText = foundCountNumberValue;

        // TP3 / CTC value Increase / Decrease
        // ID: coupon-tp3 / coupon-ctc
        const foundTokenAmount = couponArea.querySelector('#coupon-' + type);
        foundTokenAmount.innerText = tokenAmount[type] * foundCountNumberValue;

        // KRW value Increase / Decrease
        // ID: tp3-krw / ctc-krw
        const foundKrwAmount = couponArea.querySelector('#' + type + '-krw');
        foundKrwAmount.innerText = krwAmount * foundCountNumberValue;

        let krwBalance = parseFloat(foundKrwAmount.innerText);
        if(mainBalanceValue < krwBalance){
            $("#ifDepositW").show();
            toastr.error("잔액이 부족합니다");
			return;
        }
        $("#ifDepositW").hide();
        
    }

    // 구매하기 버튼 이벤트 함수
    function onClickPurchaseCoupon(type) {
        const foundCountNumber = couponArea.querySelector('#' + type + '-count');
        let coinAmount = couponArea.querySelector('#coupon-' + type ).innerHTML;
        let krwAmount = couponArea.querySelector('#' + type + '-krw' ).innerHTML;
		
        if(mainBalanceValue < krwAmount){
            toastr.error('잔액이 부족합니다');
			return;
        } else {
			for (let k = 0; k < settingList.length; k++) {
				let coin_short_name_upper = settingList[k]['short_name'];
				if(type == coin_short_name_upper.toLowerCase()){
					if(parseFloat(coinAmount) > parseFloat(eval('mainBalance'+coin_short_name_upper))) {
						toastr.error(coin_short_name_upper+' 잔액이 부족합니다');
						return;
					}
					if (confirm('쿠폰을 이용하여 메인계정의 '+coin_short_name_upper+'를 트레이닝계정으로 옮기겠습니까? 완료 후 취소할 수 없습니다')) {
						buyCoupon(type, coinAmount, krwAmount);
					}
				}
            }            
        }
    }
});

function setValues(mBal, mBalTp3, mBalCtc){
    mBalance = parseFloat(mBal);
    mBalanceTP3 = parseFloat(mBalTp3);
    mBalanceCTC = parseFloat(mBalCtc);
}

function buyCoupon(types, coinAmount, krwAmount) {
    const couponArea = document.querySelector('.tp3-ctc-coupon-area');
    let coinPrice = parseFloat(coinAmount);
    let krwPrice = parseFloat(krwAmount);

    if(mainBalanceValue < krwAmount ){
        $("#ifDepositW").show();
        toastr.error("잔액이 부족합니다.");
        return false;
    } else {
		for (let k = 0; k < settingList.length; k++) {
			let coin_short_name_upper = settingList[k]['short_name'];
			if(types == coin_short_name_upper.toLowerCase()){
				if(parseFloat(coinPrice) > parseFloat(eval('mainBalance'+coin_short_name_upper))) {
					toastr.error(coin_short_name_upper+' 잔액이 부족합니다.');
					return;
				}
				$('#coupon-purchase-'+coin_short_name_upper.toLowerCase()).hide();
			}
		} 
    }
    $.ajax({
        url: '/front2/document/buycoupon',
        type: 'post',
        data: {
            "coin_price": coinPrice,
            "krw_price": krwPrice,
            "type": types
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function(resp) {
            var resp = JSON.parse(resp);
            if (resp.success === "false") {
                toastr.error(resp.message);
                return false;
            } else {
                toastr.success(resp.message);
                setTimeout(function() {location.reload();}, 1000);
            }
        }
    });
}
