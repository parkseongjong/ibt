<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/coupons.css" />
<script type="text/javascript" src="<?php echo $this->request->webroot ?>js/front2/document/priceinfo.js?id=<?php echo time(); ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<style type="text/css">
td { width: 50%; height: 40px; line-height: 40px; }
</style>
<div class="container">
    <?php echo $this->Form->create('',array('method'=>'post'));?>
    <?php echo $this->Form->end();?>
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
					<td>0.25 % (매수/매도)</td>
                    <td style="text-align: center!important;vertical-align: middle; margin-left:50%;">
					<?php
						if(!empty($settingList)){
							foreach($settingList as $l){
								echo $l->short_name .': '.number_format($l['amount']) .'개 (50,000원)<br>';
							}
						}
					?>
					</td>
				</tr>
			</table>
            <p style="font-size: 16px; font-weight: 300; line-height: 1.5; color: #4b4b4b; margin-left:16px; margin-top: 26px">
			</p>
            <div class="tp3-ctc-coupon-area">
                <ul class="nn_title2">
                    <li> COIN IBT에서 디지털 자산 거래 시 모든 디지털 자산에 동일한 수수료 율(%)이 적용됩니다. </li>
                    <li>출금수수료는 거래소 정책 및 시장 상황에 따라 변경될 수 있습니다. 사전 공지를 통해 안내하겠습니다. </li>
                </ul>
                <div class="coupon-purchase-area" id="lvl3d">
                    <input type="hidden" name="mainBalance" id="mainBalance" value="<?= $mainBalance; ?>"/>
					<?php
						if(!empty($settingList)){
							foreach($settingList as $l){
								$mainBalanceCoin = 0;
					?>
									<input type="hidden" name="<?=$l['short_name'];?>Coupon" id="<?=$l['short_name'];?>Coupon" value="<?=$l['amount'];?>"/>
					<?php 
								if($none != 1){
									$mainBalanceCoin = $this->CurrentPrice->getUserPricipalBalance($userId,$l['cryptocoin_id']);
					?>
									<input type="hidden" name="mainBalance<?=$l['short_name']?>" id="mainBalance<?=$l['short_name']?>" value="<?= empty($mainBalanceCoin) ? 0 : $mainBalanceCoin; ?>"/>
					<?php
								}
							}
						}
					?>
                    <table class="coupon-table" id="coupon_table" >
                        <thead>
                            <div id="ifDepositW" class="flex-child" style="transform: translateX(70%); display:none;">
                                <span style="color: red;"><?= __('Insufficient Balance'); ?></span>
                            </div>
							<?php
								if(!empty($settingList)){
									foreach($settingList as $l){
							?>
									<div id="ifDeposit<?=$l['short_name'];?>" class="flex-child" style="transform: translateX(70%);display:none;">
										<span style="color: red;"><?=$l['short_name'];?>가 부족합니다</span>
									</div>
							<?php 
									}
								}
							?>
                        </thead>
                        <tbody>
					<?php
						if(!empty($settingList)){
							foreach($settingList as $l){
								$short_name = strtolower($l['short_name']);
					?>
							<tr>
                                <td style="width: 25%;">
                                    <div><?=$l['short_name'];?>쿠폰 <?= number_format($l['amount']); ?></div>
                                    <div><?=number_format($l['krw']);?> KRW</div>
                                </td>
                                <td style="width: 25%;">
                                    <div>
                                        <span class="bold-red"><?= $l['short_name'];?></span>&nbsp;
                                        <span id="coupon-<?=$short_name;?>"> <?= $l['amount']; ?></span>
                                    </div>
                                    <div>
                                        <span class="bold-red">KRW</span>&nbsp;
                                        <span id="<?=$short_name;?>-krw"><?=$l['krw'];?></span>
                                    </div>
                                </td>
                                <td style="width: 15%;">
                                    <div class="coupon-count-area">
                                        <div class="coupon-count-number" id="<?=$short_name;?>-count">1</div>
                                        <div class="coupon-count-btns">
                                             <i class="fas fa-angle-up coupon-btn" id="<?=$short_name;?>-coupon-up"></i>
                                             <i class="fas fa-angle-down coupon-btn" id="<?=$short_name;?>-coupon-down"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="padding-0" style="width: 20%;">
                                    <?php 
										$btnText = __('Login');
										$btnOnclick = 'goLogin()';
										$btnId = 'coupon-purchase-'.$short_name;
										$disabled = '';
										if($none != 1){ //  로그인 되어 있을 경우
											if(empty($l['time_limit'])){$l['time_limit'] = 0;}
											$lastTime = $this->Custom->getLastCouponTime($l['cryptocoin_id'], $userId, $l['time_limit']);
											$btnText = '구매하기';
											$btnOnclick = '';
											$hourToMin = 60*$l['time_limit'];
											if($lastTime >= 0 && $lastTime <= $hourToMin){ // 최근 구매 시간 체크
												$lastTimeMin = $hourToMin-$lastTime;
												if($lastTimeMin <= 60){
													if($lastTimeMin == 0){
														$btnText = '잠시 후 오픈';
													} else {
														$btnText = $lastTimeMin.' 분 후 이용 가능';
													}
												} else {
													$m1 = gmdate('H',$lastTimeMin*60);
													$m2 = gmdate('i',$lastTimeMin*60);
													$btnText = $m1.'시간 '.$m2.' 분';
												}
												$disabled = 'disabled';
											}
										}
										echo '<button class="coupon-purchase-btn" onclick="'.$btnOnclick.'" id="'.$btnId.'" '.$disabled .'>'.$btnText.'</button>';
									?>
                                </td>
                            </tr>
					<?php
							}
						}
					?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="success-msg" style="width: 50%; text-align: center; alignment: center; position: center; margin-left: 25%; margin-bottom: 2%"></div>
            
			<ul class="afi">
				<!--<li class="title"><?/*=__('Annual Fee Information')*/?></li>-->
			</ul>

			<!--<div class="sub_title afi2 nn_title" >
				<?/*=__('Annual fee1')*/?>
			</div>

			<table class="fee_information_table" >
				<tr>
                    <td><span style="font-weight:bold"><?/*=__('Annual Fee')*/?></span> <span><br>신한은행:</span>
                        <span id="act_spn">100-034-330970</span> 주식회사 한스바이오텍<br>
                        <button id="cp_btn" type="button" class="copy" style="display: inline-block; width: auto; height: auto;padding: 10px;"><?/*=__('Copy bank account number') */?></button></td>
                    <td>200,000 (KRW) <br><span class="bold"> <?/*= $name */?><?/*= $phone*/?></span> <span style="color: red"> *[반드시 발급된 입금자명(회원명+숫자코드)으로 입금해주세요.] [예: 홍길동1234]</span></td>
				</tr>
			</table>-->
		</div>
        <div class="cls"></div>
	</div>
</div>


