<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/coupons.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_customer2.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<style type="text/css">
td { width: 50%; height: 40px; line-height: 40px; }
</style>

<div class="container">

	<div class="custom_frame document">

		<?php echo $this->element('Front2/customer_left'); ?>
        <ul class="tab_menu">
            <li id="index" class="on"><a href="<?php echo $this->Url->build(['controller'=>'document','action'=>'priceinfo']) ?>"><?=__('Fee Information') ?></a></li>
	    <?php if ( isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '211.44.188.4' || $_SERVER['REMOTE_ADDR'] == '122.176.83.150') ) { ?>
            <li id="history"><a href="#" ><?=__('Commission Coupon') ?></a></li>
	    <?php } ?>
            <?php //echo $this->Url->build(['controller'=>'document','action'=>'commission']) ?>
        </ul>

		<div class="contents">
			<div class="sub_title nn_title" style="margin-top:30px; margin-bottom:10px; font-weight:400">
			 <?=__('PriceInfo Fee1')?>
			</div>

			<table class="fee_information_table" >
				<tr>
					<td style="font-weight:bold"><?=__('Transaction Fee')?></td>
					<td style="font-weight:bold; text-align:center!important"><?=__('Conversion Fee')?></td>
				</tr>
				<tr>
					<!--td>0 원 (<?=__('Free')?>)</td-->
					<td>0.25 %</td>
                    <td style="text-align: center!important;vertical-align: middle; margin-left:50%;">TP3: 5,000개 50,000원 <br/>CTC: 500 개 50,000원</td>
<!--					<td></td>  --><?//=__('Conversion fee1')?>
				</tr>
			</table>
            <p style="font-size: 16px; font-weight: 300; line-height: 1.5; color: #4b4b4b; margin-left:16px; margin-top: 26px">
			</p>
            <div class="tp3-ctc-coupon-area">
                <ul class="nn_title2">
                    <li> COIN IBT에서 디지털 자산 거래 시 모든 디지털 자산에 동일한 수수료 율(%)이 적용됩니다. </li>
                    <li>출금수수료는 거래소 정책에 따라 변경될 수 있습니다. 사전 공지를 통해 안내하겠습니다. </li>
                    <li>출금수수료는 시장상황에 따라 변경될 수 있습니다. </li>
                </ul>

                <div class="coupon-purchase-area" id="lvl3d">
                    <table class="coupon-table">
                        <thead>
                        <div id="ifDepositW" class="flex-child" style="transform: translateX(70%)">
                            <span style="color: red;"><?= __('Insufficient Balance'); ?></span>
                        </div>
                        <div id="ifDepositTp3" class="flex-child" style="transform: translateX(70%)">
                            <span style="color: red;"><?= __('Insufficient TP3 Balance'); ?></span>
                        </div>
                        <div id="ifDepositCtc" class="flex-child" style="transform: translateX(70%)">
                            <span style="color: red;"><?= __('Insufficient CTC Balance'); ?></span>
                        </div>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 25%;">
                                    <div>TP3 쿠폰 <?= $tp3Coupon; ?></div>
                                    <div>50,000 KRW</div>
                                </td>
                                <td style="width: 25%;">
                                    <div>
                                        <span class="bold-red">TP3</span>&nbsp;
                                        <span id="coupon-tp3"><?= $tp3Coupon; ?></span>
                                    </div>
                                    <div>
                                        <span class="bold-red">KRW</span>&nbsp;
                                        <span  id="tp3-krw">50000</span>
                                    </div>
                                </td>
                                <td style="width: 15%;">
                                    <div class="coupon-count-area">
                                        <div class="coupon-count-number" id="tp3-count">1</div>
                                        <div class="coupon-count-btns">
                                            <i class="fas fa-angle-up coupon-btn" id="tp3-coupon-up"></i>
                                            <i class="fas fa-angle-down coupon-btn" id="tp3-coupon-down"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="padding-0" style="width: 20%;">
                                    <?php if(!empty($name)){
                                        echo '<button class="coupon-purchase-btn" id="coupon-purchase-tp3" disabled>구매하기</button>';
                                    } else{
                                        echo '<button class="coupon-purchase-btn" onclick="goLogin()">'.__('Login').'</button>';
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>CTC 쿠폰 <?= $ctcCoupon; ?></div>
                                    <div>50,000 KRW</div>
                                </td>
                                <td>
                                    <div>
                                        <span class="bold-red">CTC</span>&nbsp;
                                        <span id="coupon-ctc"><?= $ctcCoupon; ?></span>
                                    </div>
                                    <div>
                                        <span class="bold-red">KRW</span>&nbsp;
                                        <span id="ctc-krw">50000</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="coupon-count-area">
                                        <div class="coupon-count-number" id="ctc-count">1</div>
                                        <div class="coupon-count-btns">
                                            <i class="fas fa-angle-up coupon-btn" id="ctc-coupon-up"></i>
                                            <i class="fas fa-angle-down coupon-btn" id="ctc-coupon-down"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="padding-0">
                                    <?php if(!empty($name)){
                                        echo '<button class="coupon-purchase-btn" id="coupon-purchase-ctc" disabled>구매하기</button>';
                                    } else{
                                        echo '<button class="coupon-purchase-btn" onclick="goLogin()">'.__('Login').'</button>';
                                    } ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="success-msg" style="width: 50%; text-align: center; alignment: center; position: center; margin-left: 25%; margin-bottom: 2%">  <?= $this->Flash->render() ?> </div>
			<ul class="afi">
				<li class="title"><?=__('Annual Fee Information')?></li>
			</ul>

			<div class="sub_title afi2 nn_title" >
				<?=__('Annual fee1')?>
			</div>

			<table class="fee_information_table" >
				<tr>
                    <td><span style="font-weight:bold"><?=__('Annual Fee')?></span> <span><br>신한은행:</span>
                        <span id="act_spn">140013236931</span><br>
                        <button id="cp_btn" type="button" class="copy" style="display: inline-block; width: auto; height: auto;padding: 10px;"><?=__('Copy bank account number') ?></button></td>
                    <td>50,000 (KRW) <br><span class="bold"> <?= $name ?><?= $phone?></span> <span style="color: red"> *[반드시 발급된 입금자명(회원명+숫자코드)으로 입금해주세요.] [예: 홍길동1234]</span></td>
				</tr>
			</table>

		</div>
<div class="cls"></div>
	</div>

</div>

<script>
    var mBalances = "<?= $mainBalance; ?>";
    var mBalancesTP3 = "<?= $mainBalanceTp3; ?>";
    var mBalancesCTC = "<?= $mainBalanceCtc; ?>";
    var mBalance = parseFloat(mBalances);
    var mBalanceTP3 = parseFloat(mBalancesTP3);
    var mBalanceCTC = parseFloat(mBalancesCTC);

    $(document).ready(function() {
       // $("#lvl3d *").prop('disabled', true).css({'pointer-events': 'none'});
        var mainBal = "<?= $mainBalance; ?>";
        var mainBalTp3 = "<?= $mainBalanceTp3; ?>";
        var mainBalCtc = "<?= $mainBalanceCtc; ?>";
        var ctcVal = "<?= $ctcCoupon; ?>";
        var tp3Val = "<?= $tp3Coupon; ?>";
        var ctcValue = parseFloat(ctcVal);
        var tp3Value = parseFloat(tp3Val);
        var mainBalance = parseFloat(mainBal);
        var mainBalanceTp3 = parseFloat(mainBalTp3);
        var mainBalanceCtc = parseFloat(mainBalCtc);
        if(mainBalance < 50000){
            $("#ifDepositW").show();
            $("#ifDepositCtc").hide();
            $("#ifDepositTp3").hide();
            // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
        } else {
            $("#ifDepositW").hide();
            if(mainBalanceTp3 < tp3Value){
                $("#ifDepositTp3").show();
                // $("#ifDepositCtc").hide();
                // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            } else {
                $("#ifDepositTp3").hide();
                // $("#ifDepositCtc").hide();
                // $("#coupon-purchase-tp3").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            }

            if(mainBalanceCtc < ctcValue){
                $("#ifDepositCtc").show();
                // $("#ifDepositTp3").hide();
                // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            } else {
                //$("#ifDepositTp3").hide();
                $("#ifDepositCtc").hide();
              //  $("#coupon-purchase-ctc").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            }
        }
    });

    document.getElementById("cp_btn").addEventListener("click", copy_account);
    function copy_account() {
        var copyText = document.getElementById("act_spn");
        var textArea = document.createElement("textarea");
        textArea.value = copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
    }

    function openCoupon(){
        document.location.href = "/front2/document/commission";
    }


    document.addEventListener('DOMContentLoaded', function() {
        const couponArea = document.querySelector('.tp3-ctc-coupon-area');
        const tp3CountUp = couponArea.querySelector('#tp3-coupon-up');
        couponArea.querySelector('#tp3-coupon-up').addEventListener('click', function() { couponCounter('tp3', 'up'); });
        couponArea.querySelector('#tp3-coupon-down').addEventListener('click', function() { couponCounter('tp3', 'down'); });
        couponArea.querySelector('#ctc-coupon-up').addEventListener('click', function() { couponCounter('ctc', 'up'); });
        couponArea.querySelector('#ctc-coupon-down').addEventListener('click', function() { couponCounter('ctc', 'down'); });

        var ctcVal = "<?= $ctcCoupon; ?>";
        var tp3Val = "<?= $tp3Coupon; ?>";
        var ctcValue = parseFloat(ctcVal);
        var tp3Value = parseFloat(tp3Val);
        const tokenAmount = {
            'tp3': tp3Value,
            'ctc': ctcValue,
        };
        const krwAmount = 50000;

        // TP3 쿠폰, CTC 쿠폰 갯수 변경 이벤트
        function couponCounter(type, upDown) {
            console.log('coupon Counter ', type, upDown);
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

            var krwBalance = parseFloat(foundKrwAmount.innerText);
            var tp3Balance = 0;
            var ctcBalance = 0;
            if(type === "tp3")
                tp3Balance = parseFloat(foundTokenAmount.innerText);
            else {
                ctcBalance = parseFloat(foundTokenAmount.innerText);
            }
            if(mBalance < krwBalance){
                $("#ifDepositW").show();
                $("#ifDepositCtc").hide();
                $("#ifDepositTp3").hide();
                alert("잔액이 부족합니다.");
                // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            } else {
                $("#ifDepositW").hide();
                if(mBalanceTP3 < tp3Balance){
                    $("#ifDepositTp3").show();
                    alert("TP3 잔액이 부족합니다.");
                    // $("#ifDepositCtc").hide();
                    // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                } else {
                    $("#ifDepositTp3").hide();
                    // $("#ifDepositCtc").hide();
                    // $("#coupon-purchase-tp3").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                }

                if(mBalanceCTC < ctcBalance){
                    $("#ifDepositCtc").show();
                    alert("CTC 잔액이 부족합니다.");
                    // $("#ifDepositTp3").hide();
                    // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                } else {
                    //$("#ifDepositTp3").hide();
                    $("#ifDepositCtc").hide();
                    // $("#coupon-purchase-ctc").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                }
            }

        }


        couponArea.querySelector('#coupon-purchase-tp3').addEventListener('click', function() { onClickPurchaseCoupon('tp3'); });
        couponArea.querySelector('#coupon-purchase-ctc').addEventListener('click', function() { onClickPurchaseCoupon('ctc'); });
        // 구매하기 버튼 이벤트 함수
        function onClickPurchaseCoupon(type) {
            const foundCountNumber = couponArea.querySelector('#' + type + '-count');
            console.log('구매하기 클릭 ', type, foundCountNumber.innerText,couponArea.querySelector('#coupon-' + type ).innerHTML,couponArea.querySelector('#' + type + '-krw' ).innerHTML);
            var coinAmount = couponArea.querySelector('#coupon-' + type ).innerHTML;
            var krwAmount = couponArea.querySelector('#' + type + '-krw' ).innerHTML;

            if(mBalance < 50000){
                alert('잔액이 부족합니다.');
            } else {
                if(type === "tp3"){
                    if(coinAmount > mBalanceTP3){
                        alert('TP3 잔액이 부족합니다.');
                    }else{
                        if (confirm('쿠폰을 이용하여 메인계정의 TP3를 트레이닝계정으로 옮기겠습니까? 완료 후 취소할 수 없습니다')) {
                            buyCoupon(type, coinAmount, krwAmount);
                        } else {
                            // console.log('Thing was not saved to the database.');
                        }
                    }

                } else {
                    if(coinAmount > mBalanceCTC){
                        alert('CTC 잔액이 부족합니다.');
                    }else {
                        if (confirm('쿠폰을 이용하여 메인계정의 CTC를 트레이닝계정으로 옮기겠습니까? 완료 후 취소할 수 없습니다')) {
                            buyCoupon(type, coinAmount, krwAmount);
                        } else {
                            //console.log('Thing was not saved to the database.');
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
        var mainBal = "<?= $mainBalance; ?>";
        var mainBalTp3 = "<?= $mainBalanceTp3; ?>";
        var mainBalCtc = "<?= $mainBalanceCtc; ?>";
        var mainBalanceTp3 = parseFloat(mainBalTp3);
        var mainBalanceCtc = parseFloat(mainBalCtc);
        var coinPrice = parseFloat(coinAmount);
        var krwPrice = parseFloat(krwAmount);

        var availableAmount = parseFloat(mainBal);

        if(availableAmount < 50000 ){
            toastr.error('<?= __('You need to have at least 50,000 KRW in your main account') ?>');
            $("#ifDepositW").show();
            alert("잔액이 부족합니다.");
            // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
            return false;
        }else {
            if(types === "ctc"){
                if(mainBalanceCtc < coinPrice ){
                    toastr.error('<?= __('CTC 잔액이 부족합니다.') ?>');
                    $("#ifDepositCtc").show();
                    return false;
                } else {
                    $("#ifDepositCtc").hide();
                }
            }

            if(types === "tp3"){
                if(mainBalanceTp3 < coinPrice ){
                    toastr.error('<?= __('TP3 잔액이 부족합니다.') ?>');
                    $("#ifDepositTp3").show();
                    return false;
                } else {
                    $("#ifDepositTp3").hide();
                }
            }
        }

        $.ajax({
            url: '<?php echo $this->Url->build(["controller" => "Document", "action" => "buycoupon"]) ?>',
            type: 'post',
            data: {
                coin_price: coinPrice,
                krw_price: krwPrice,
                type: types
            },
            success: function(resp) {
                var resps = JSON.parse(resp);
                if (resps.success === "false") {
                    toastr.error(resps.message);
                    return false;
                } else {
                    toastr.success(resps.message);
                    couponArea.querySelector('#' + types + '-count').value = 1;
                    var respVal = resp.data.balanceList;
                    var mainBalnc = parseFloat(respVal.mainBalance);
                    var mainBalncCtc = parseFloat(respVal.mainBalanceCtc);
                    var mainBalncTp3 = parseFloat(respVal.mainBalanceTp3);
                    setValues(mainBalnc,mainBalncTp3,mainBalncCtc);
                    if(mainBalnc < 50000){
                        $("#ifDepositW").show();
                        // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                        // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    } else {
                        $("#ifDepositW").hide();
                        if(types === "ctc"){
                            if(mainBalncCtc <= coinPrice){
                                //$("#ifDepositTp3").hide();
                                $("#ifDepositCtc").show();
                                // $("#coupon-purchase-ctc").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                            } else {
                                $("#ifDepositCtc").hide();
                                // $("#ifDepositTp3").hide();
                                // $("#coupon-purchase-ctc").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                            }
                        }
                        if(types === "tp3"){
                            if(mainBalncTp3 <= coinPrice){
                                //$("#ifDepositCtc").hide();
                                $("#ifDepositTp3").show();
                                // $("#coupon-purchase-tp3").prop('disabled',true).attr('disabled','disabled').css({'pointer-events': 'none'});
                            } else {
                                //$("#ifDepositCtc").hide();
                                $("#ifDepositTp3").hide();
                                // $("#coupon-purchase-tp3").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                            }
                        }
                    }
                }
            }
        });
    }

    function goLogin() {
        //confirm_alert2();
        document.location.href = "/front2/Users/login";
    }

</script>

