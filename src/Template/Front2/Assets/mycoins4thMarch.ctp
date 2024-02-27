<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/assets.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<style>
    .assets_box .tab_menu li {
        float: left;
        width: 25%;
        height: 60px;
        line-height: 60px;
        text-align: center;
        margin-top: 20px;
        border-top: solid 1px #b5b5b5;
        border-bottom: solid 1px #b5b5b5;
        border-right: solid 1px #b5b5b5;
        background: #ffffff;
    }
    .assets_box .tab_menu li:nth-child(1) {
        width: 24%;
        border-left: solid 1px #b5b5b5;
    }

    .assets_box .tab_menu li a {
        font-size: 15px;
        font-weight: bold;
        color: #000000;
    }

    .assets_box .tab_menu li.on a {
        color: #6738ff;
    }

    .assets_box .tab_menu_gen li a {
        font-size: 15px;
        font-weight: bold;
        color: #000000;
    }

    .assets_box .tab_menu_gen li.on a {
        color: #6738ff;
    }

    .assets_box .tab_menu_statement {
        list-style-type: none;
        margin-top: 40px;
        overflow: hidden;
    }
    .assets_box .tab_menu_statement li {
        float: left;
        line-height: 1;
        margin-right: 20px;
        border-bottom: solid 2px #ffffff;
    }
    .assets_box .tab_menu_statement li.on {
        border-bottom: solid 2px #6738ff;
    }
    .assets_box .tab_menu_statement li a {
        font-size: 16px;
        font-weight: bold;
        color: #b8b8b8;
    }
    .assets_box .tab_menu_statement li.on a {
        color: #6738ff;
    }
    .common_tab table.list {
        width: 100%;
        height: 100%;
        overflow: auto;
    }
    .common_tab table.list tr {
        border-bottom: 2px solid #f5f5f5;
        background: #fff;
    }
    .common_tab table.list tr > td {
        width: 19%;
        line-height: 14px;
        padding: 8px 13px;
        text-align: center;
        font-size: 13px;
        font-weight: normal;
        color: #808080;
    }
    .common_tab table.list tbody tr > td {
        font-size: 13px;
        line-height: 15px;
        padding: 16px 13px;
    }
    .common_tab table.list tr > td:last-child {
        min-width: 100px;
    }
    #myDepositlist tr td:last-child, #myWithdrawlist tr td:last-child {
        min-width: 82px;
    }

    .common_tab table.list tbody tr.on {
        background: #f3f2ff;
    }

    .common_tab table.list tr > td .bold {
        font-weight: 500;
        color: #000000;
    }
    .common_tab table.list tr > td .red {
        color: #d80000;
    }
    .common_tab table.list tr > td .blue {
        color: #0c45d5;
    }

.containers3 {    max-width: 1170px;    margin: 30px auto;}
#coin_name_id_data{
	font-size:24px;
	line-height:50px;
}

</style>
<style>
input[type=number] {
    height: 30px;
    line-height: 30px;
    font-size: 16px;
    padding: 0 8px;
}
input[type=number]::-webkit-inner-spin-button:not(.krw-info-area .krw-info-top .krw-input-area .input-group input) { 
    -webkit-appearance: none;
    cursor:pointer;
    display:block;
    width:8px;
    color: #333;
    text-align:center;
    position:relative;
}    
input[type=number]:hover::-webkit-inner-spin-button:not(.krw-info-area .krw-info-top .krw-input-area .input-group input) { 
    background: #eee url('http://i.stack.imgur.com/YYySO.png') no-repeat 50% 50%;  
    width: 14px;
    height: 14px;
    padding: 4px;
    position: relative;
    right: 4px;
    border-radius: 28px;
}
.krw-info-area .krw-info-top .krw-input-area .input-group input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}
</style>

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div class="containers containers3" >
 <div class="">
<?php echo $this->element('Front2/profile_menu'); ?>

	<div class="assets_box" style="width:auto;">
		<div class="left mycoinleft">
            <div class="my_assets">
			    <ul class="total_assets">
				    <li class="title"><?=__('Total assets held (KRW)') ?></li>
				    <li class="pricetotal12233 price" id="total_balance_val">0</li>
			    </ul>

                <input type="text" id="search_coin" name="search_coin" class="search_coin" placeholder="<?=__('Coin search') ?>" />

			<div class="options">
				<label><input type="checkbox" name="" id="radioclick" value="" /><?=__('View only retained coins') ?></label>
			</div>
		</div>

		<div class="my_coins">
			<table>
				<thead>
					<tr>
						<td><b><span><?=__('Coin Name')?></span></b></td>
						<td style="width:21%"><b><span><?=__('Retained quantity')?></span></b></td>
						<td style="width:24%"><b><span>KRW</span></b></td>
					</tr>
				</thead>
				<tbody id="mycoinlist">
					<?php
						$total_value="";
						$principalBalanceTotal=""; 
						$reserveBalance ="";
						$tradingBalance = "";
						if(!empty($mainRespArr)){
							foreach($mainRespArr as $key=> $value){
							if(!empty($value['principalBalance'])){ ?>
					<tr>
						<td>
					
					        <span class="setvalue"  data-id="coin_name_<?php echo $key;?>" style="cursor:pointer" data-coin-id="<?php echo $value['coinId']; ?>"
                                  data-coin-title="<?php echo $value['coinName']."(".$value['coinShortName'].")"; ?>">
                                <?php if(!empty($value['icon'])){ ?> <img src="/uploads/cryptoicon/<?php echo $value['icon'];?> " width="40px" max-height="40px">
					            <?php } ?><?php echo $value['coinShortName']; ?></span>
                        </td>
						<td>
                            <span id="retained_quantity_<?php echo $value['coinId']; ?>"><?php echo number_format((float)$value['principalBalance'],2); ?></span>
                        </td>
						<td>
                            <span  id="krw_quantity_<?php echo $value['coinId']; ?>">
                            <?php $getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($value['coinId'],20);
							$getMyCustomPrice = str_replace(',', '', $getMyCustomPrice); ?>
						    <?php $krw_value=$value['principalBalance'] * $getMyCustomPrice;
							$newVal = $value['principalBalance'] * $getMyCustomPrice;
							echo number_format((float)$newVal,2);
							$total_value+= $value['principalBalance'] * $getMyCustomPrice;
							$principalBalanceTotal+=$value['principalBalance'];
							$reserveBalance+=$value['reserveBalance'];
                            $tradingBalance+=$value['tradingBalance']; ?>
                            </span>
                        </td>
                        <input type="hidden" value=<?php echo $value['principalBalance'];?> id="quantity_<?php echo $key ;?>" name="quantity_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $krw_value;?> id="krw_<?php echo $key ;?>" name="krw_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $value['coinShortName'];?> id="coin_name_<?php echo $key ;?>" name="coin_name_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $getMyCustomPrice;?> id="coin_id_<?php echo  $value['coinShortName'] ;?>" name="coin_id_<?php echo  $value['coinShortName'] ;?>"/>
						
						<input type="hidden" value=<?php echo $value['tradingBalance'];?> id="tradingBalance_<?php echo  $key ;?>" name="tradingBalance_<?php echo  $key ;?>"/>
						<input type="hidden" value=<?php echo $value['coinAddress'];?> id="coinAddress_<?php echo $key ;?>" name="coinAddress_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $value['reserveBalance'];?> id="reserveBalance_<?php echo $key ;?>" name="reserveBalance_<?php echo $key ;?>"/>
						
					</tr>
					<?php }else{ ?>
                    <tr class="hide_currency">
                        <td>
                            <span class="setvalue"  data-id="coin_name_<?php echo $key;?>" style="cursor:pointer" data-coin-id="<?php echo $value['coinId']; ?>"
                                  data-coin-title="<?php echo $value['coinName']."(".$value['coinShortName'].")"; ?>">
                                <?php if(!empty($value['icon'])){ ?><img src="/uploads/cryptoicon/<?php echo $value['icon'];?> " width="40px" max-height="40px">
					        <?php } ?>
					        <?php echo $value['coinShortName']; ?>
                            </span>
                        </td>
						<td>
                            <span><?php	echo number_format((float)$value['principalBalance'],2); ?></span>
                        </td>
						<td>
                            <span><?php $getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($value['coinId'],20);
							    $getMyCustomPrice  = $getMyCustomPrice;
							    $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
							    $total_value+= $value['principalBalance'] * $getMyCustomPrice;
							    $principalBalanceTotal+=$value['principalBalance'];
							    $reserveBalance+=$value['reserveBalance'];
                                $tradingBalance+=$value['tradingBalance']; ?>
                                <?php $krw_value=$value['principalBalance'] * $getMyCustomPrice;
							    $getNewVal =  $value['principalBalance'] * $getMyCustomPrice;
							    echo $getNewVal = number_format((float)$getNewVal,2); ?>
                            </span>
                        </td>
                        <input type="hidden" value=<?php echo $value['principalBalance'];?> id="quantity_<?php echo $key ;?>" name="quantity_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $krw_value;?> id="krw_<?php echo $key ;?>" name="krw_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $value['coinShortName'];?> id="coin_name_<?php echo $key ;?>" name="coin_name_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $getMyCustomPrice;?> id="coin_id_<?php echo  $value['coinShortName'] ;?>" name="coin_id_<?php echo  $value['coinShortName'] ;?>"/>
						
						<input type="hidden" value=<?php echo $value['tradingBalance'];?> id="tradingBalance_<?php echo  $key ;?>" name="tradingBalance_<?php echo  $key ;?>"/>
						<input type="hidden" value=<?php echo $value['coinAddress'];?> id="coinAddress_<?php echo $key ;?>" name="coinAddress_<?php echo $key ;?>"/>
						<input type="hidden" value=<?php echo $value['reserveBalance'];?> id="reserveBalance_<?php echo $key ;?>" name="reserveBalance_<?php echo $key ;?>"/>
						
					</tr>
                    <?php       }
							}
					    } ?>
                    <input type="hidden" class="totalkrw" value="<?php echo $total_value;?>"/>
                    <input type="hidden" class="principalBalanceTotal" value="<?php echo $principalBalanceTotal;?>"/>
                    <input type="hidden" class="reserveBalance" value="<?php echo $reserveBalance;?>"/>
                    <input type="hidden" class="tradingBalance" value="<?php echo $tradingBalance;?>"/>
				</tbody>
			</table>
		</div>
    </div>
    <div class="mycoinrigth">
        <div class="mycoinrigth_pp">
            <div class="rbtc_box">
                <span style="margin-right:15px;display: inline-block" id="coin_name_id_data"></span><?= __('Main Balance: ') ?><span style="font-weight:bold" class="main_Balance1122" id="main_total_balance">0</span>
                <span class="reblock"><?= __('Trading Balance: ') ?><span style="font-weight:bold" class="TradingAccounts" id="trading_total_balance">0</span></span>
                <span class="reblock"><?= __('Reserved Balance: ') ?><span style="font-weight:bold" class="TradingAccounts" id="resserve_total_balance">0</span></span>
            </div>
            <ul class="tab_menu_gen">
                <li class="depositkrw" id="depositkrw_on" onClick="tabClick('depositkrw')">
                    <a href="javascript:void(0);"> <?= __('Deposit in Korean Won') ?> </a>
                </li>
                <li class="krwwithdrawal" id="krwwithdrawal_on" onClick="tabClick('krwwithdrawal')">
                    <a href="javascript:void(0);"> <?= __('KRW Withdrawal') ?> </a>
                </li>
                <li class="statement" id="statement_on" onClick="tabClick('statement')">
                    <a href="javascript:void(0);"> <?= __('Statement') ?> </a>
                </li>
            </ul>

            <ul class="tab_menu">
                <li class="deposit" id="deposit_on" onClick="tabClick('deposit')">
                    <a href="javascript:void(0);"> <?= __('Deposit') ?> </a>
                </li>
                <li class="withdrawal" id="withdrawal_on" onClick="tabClick('withdrawal')">
                    <a href="javascript:void(0);"> <?= __('Withdrawal') ?> </a>
                </li>
                <li class="details" id="breakdown_on" onClick="tabClick('breakdown')">
                    <a href="javascript:void(0);"> <?= __('Breakdown') ?> </a>
                </li>
                <li class="address" id="withdrawal_addr_on" onClick="tabClick('withdrawal_addr')">
                    <a href="javascript:void(0);"> <?= __('Withdrawal Address Management') ?> </a>
                </li>
            </ul>
            <div id="mesg"><span><h3>출금하실 코인을 선택하시면 입, 출금 메뉴가 보입니다.</h3></span></div>
				<div style="text-align: center;" class="common_tab" id="default_content">
                    <div class="krw-info-area">
                        <div class="krw-info-top">
                            <div class="krw-account-info-area">
                                <div class="title-area">
                                    <h2>입출금 계좌정보</h2>
                                    <span>(입출금시 꼭 아래 은행 계좌에서 입금해주세요. 타계좌 입금시 반환됩니다.)</span>
                                </div>
                                <div class="krw-info-grid">
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            계좌번호
                                        </div>
                                        <div class="grid-col grid-col-10">
                                            <?= $account;?>
                                        </div>
                                    </div>
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            은행명
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            <?= $bank; ?>
                                        </div>
                                        <div class="grid-col grid-col-2 grid-title">
                                            예금주
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            <?php echo ucfirst($_SESSION['Auth']['User']['name']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="krw-account-memo-area">
                                <div class="memo-desc">
                                    <div>받는 통장 메모</div>
                                    <div class="text-color-blue">(받는 분에게 표기)</div>
                                </div>
                                <div class="memo-username">
                                    <?php $phone_number = $userDetail['phone_number'];
                                    $masked_phone_number = substr($phone_number, -4);
                                    echo ucfirst($_SESSION['Auth']['User']['name']) . $masked_phone_number; ?>
                                </div>
                                <div class="memo-notice">
                                    *반드시 발급된 입금자명(회원명+숫자코드)으로 입금해주세요. [ex: 홍길동1234]
                                </div>
                            </div>

                            <div class="krw-push-info-area">
                                <div class="title-area">
                                    <h2>원화 송금 계좌 안내</h2>
                                </div>
                                <div class="krw-info-grid">
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            계좌번호
                                        </div>
                                        <div class="grid-col grid-col-10">
                                            100-034-688436
                                        </div>
                                    </div>
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            은행명
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            신한은행
                                        </div>
                                        <div class="grid-col grid-col-2 grid-title">
                                            예금주
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            주)한마음스마트<br/>
                                            (코인아이비티(COIN IBT))
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ifpendingD"> <span style="color: red;">주문 처리중 입니다</span></div>
                            <div id="notauth"><span style="color: red;"><a id="elink" href="/front2/users/id-verification"><?=__('Go to auth') ?></a></span></div>
                            <div class="krw-input-area">
                                <div class="input-group">
                                    <input type="number" id="amount_deposited" name="amount_deposited" placeholder="입금요청금액을 입력하세요">
                                    <div class="input-postfix">KRW</div>
                                </div>
                                <button class="krw-input-btn" id="bank_deposit_btn" onclick="amountSubmitted();">일반입금</button>
                            </div>
                        </div>

                        <div class="krw-info-bottom">
                            <div class="krw-guide">
                                <div class="guide-title">입금을 하기전에 유의사항을 확인해주세요!</div>
                                <div>- 원화 출금은 KRW 첫 입금 후 120 시간, 디지털 자산 출금은 KRW 마지막 입금 후120시간 동안 출금이 제한됩니다.</div>
                                <div>- 첫 입금 후 디지털 자산 거래를 5만 KRW 이상 "구매, 판매" 거래를 하셔야 출금할 수 있습니다.</div>
                                <div>- 최소 충전 금액은 50,000원 이상입니다.</div>
                                <div>
                                    <span class="warn">- 첫 입금과 연회비는 동일하지 않으니  참고 하여 주시기 바랍니다.</span>
                                </div>
                                <div>
                                    - 충전요청 후에 COIN IBT 입금계좌로 요청금액과 실제 입금액을 동일하게 입금 바랍니다.
                                    <br/>&nbsp;&nbsp;예) 100만원 신청 후 99만원 입금 내용확인 불가 (입금지연 15일이상)
                                </div>

                                <div>
                                    - 100만원 신청 후 50만원+50만원 나누어서 입금  확인 불가 (입금지연 15일이상)
                                    <br/>&nbsp;&nbsp;반드시 ‘받는분통장표시’란에 ‘이름+숫자코드’를 입력해 주시기 바랍니다.
                                    <br/>&nbsp;&nbsp;예) 홍길동6070
                                </div>

                                <div style="margin-bottom: 30px;">
                                    <span class="warn">※ 주의</span>
                                    <br/>타인명의 계좌, 미등록 본인 계좌에서 입금  확인 불가 (입금지연 15일이상)
                                    <br/>충전요청 후 24시간이내 미 입금 시 승인거절이 됩니다.
                                </div>

                                <div>
                                    <strong>원화 송금 계좌정보</strong>
                                    <br/>계좌번호  100-034-688436
                                    <br/>은행명  신한은행
                                    <br/>예금주 (주)한마음스마트(코인아이비티(COIN IBT)
                                </div>
                            </div>

                            <div class="krw-guide">
                                <div class="guide-title">출금을 하기전에 유의사항을 확인해주세요!</div>
                                <div>- 첫 입금 후 디지털 자산 거래를 5만 KRW 이상 "구매,판매" 거래를 하셔야 출금할 수 있습니다.</div>
                                <div>- 최소 입금 금액은 50,000원 이상입니다.</div>
                                <div>- 최소 출금 금액은 50,000원 이상입니다.</div>
                                <div>- 원화 출금은 KRW 첫 입금 후 120시간, 디지털 자산 출금은 KRW 마지막 입금 후120시간 동안 출금이 제한됩니다. (추후 시간은 조정 됩니다.)</div>
                                <div>- 출금요청 완료 시 등록하신 은행계좌로 출금이 되며, 등록하지 않은 계좌로의 출금은 불가능 합니다.</div>
                                <div>- 업무시간 내에만 출금 가능합니다. (업무시간 오전10:00-오후05:00)</div>
                                <div>- 부정 거래가 의심되는 경우 출금이 제한될 수 있습니다.</div>
                                <div>- 출금 수수료는 1,000원이며 50,000원 이상부터 출금이 가능합니다.</div>
                            </div>
                        </div>
                    </div>
				</div>
				
				<div style="display:none;" class="common_tab qr_code_man"  id="deposit_tab_content" >
                    <span  id="qr_code_image"></span>
					<ul class="copy_address">
						<li style="float:left; width:100%; color:#000;">
							<input type="text" name="" value="" readonly id="wallet_addr_input" class="text" />
							<div class="address_li" onClick="copyToClipboard();" >
							    <?=__('Copy Address') ?>
						    </div>
						</li>
					</ul>
					<div id="copy_msg" class="alert alert-success" style="display:none;"></div>
				</div>

				<div style="display:none;" class="common_tab"  id="withdrawal_tab_content" >
				    <div class="exting">
                        <b><?= __('Total Buy Amount: '); ?></b> <span id="totalBuyAmount"><?= number_format($totalBuy,2); ?> KRW</span>, <b><?= __('Total Sell Amount: '); ?></b>
                        <span id="totalSellAmount"> <?= number_format($totalSell,2); ?> KRW</span>, <b><?= __('Total Deposit Amount: '); ?></b>
                        <span id="totalDepositAmount"> <?= number_format($totalDeposit,2); ?> KRW</span>, <b><?= __('Total Old Deposit Amount: '); ?></b>
                        <span id="totalOldDepositAmount"> <?= number_format($totalOldDeposit,2); ?> KRW</span><br /><br />
                        <div class="flex-container">
                            <div id="ifNotAuth" class="flex-child">
                                <span style="color: red;"><a style="color: red" id="elink" href="/front2/users/id-verification"><?= __('User Level: 1, Please authenticate yourself to be able to withdraw'); ?></a></span>
                            </div>
                            <div id="ifLess" class="flex-child">
                                <span style="color: red;"><?= __('Withdrawal conditions do not match'); ?></span>
                            </div>
                            <div id="ifDeposit" class="flex-child" style="position: relative;">
                                <img id="depositImg" src="<?php echo $this->request->webroot ?>assets/html/images/cross.png" style="position: absolute;
                        top: -40px; left: 50%; transform: translateX(-50%);" />
                                <span style="color: red;"><?= __('Withdrawal conditions do not match'); ?></span>
                            </div>
                        </div>
						<label><input type="radio" name="withdrawal_type"  id="withdrawal_type_out" value="OUT" checked /> <?=__('External withdrawal')?> </label>
						<label><input type="radio" name="withdrawal_type" id="withdrawal_type_in" value="IN" /> <?=__('Internal withdrawal')?> </label>
                        <br />
				    </div>
				
                    <span id="table_withdrowl1">
					    <div class="table_withdrowl">
						    <table class="withdrawal">
							    <tr>
								    <td class="title">
									    <?=__('Withdrawable amount')?>
								    </td>
								    <td colspan="3" class="right">
									    <span class="amount" id="amount_data"></span><span class="unit" id="unit_data"></span>
								    </td>
							    </tr>
							    <tr>
								    <td class="title height-100">
									    <?=__('Withdrawal request amount')?>
								    </td>
								    <td class="no-border right height-100">
								        <div class="price5">
								            <?php $min="0.00";
								                $step ="1";
								                $secondCoin="";
									            if($secondCoin=="TP3"){
										            $min='0.1';
										            $step="0.1";
										
									            }
									            if($secondCoin=="BTC"){
										            $min='0.0';
										            $step="5000";
										
									            }
									            if($secondCoin=="CTC"){
										            $min='0.0';
										            $step="1";
										
									            }
									            if($secondCoin=="ETH"){
										            $min='0.0';
										            $step="500";
										
									            }
									            if($secondCoin=="USDT"){
										            $min='0.0';
										            $step="0.01";
										
									            }
									            if($secondCoin=="XRP"){
										            $min='0.0';
										            $step="0.1";
										
									            } ?>
									        <input type="text" step="<?php echo $step;?>" min="<?php echo $min;?>" name="req_amount"  id="req_amount" class="req_amount"
                                                   placeholder="<?=__('Enter withdrawal request amount')?>" /><span class="unit"></span>
									        <span class="up1" id="buy_price_up" onclick="increment('<?php echo $step;?>')"><i class="fa fa-caret-up"></i></span>
                                            <span class="up2" id="buy_price_down" onclick="decrement('<?php echo $step;?>')"><i class="fa fa-caret-down"></i></span>
								        </div>
								    </td>
								    <td class="title no-border height-100">
                                        <img src="/wb/imgs/equal2.png" />
                                    </td>
                                    <td class="right height-100" style="width: 230px">
									    <span class="amount" id="amountkrw">0</span><span class="unit">KRW</span>
								    </td>
							    </tr>
							    <tr>
								    <td class="title">
									    <?=__('Withdrawal fee')?> <span id="withdraw_fee_percent"></span>
								    </td>
								    <td colspan="3" class="right">
									    <span id="withdrawalfee" class="amount">0</span><span class="unit unit_data"></span>
								    </td>
							    </tr>
							    <tr>
								    <td class="title blue">
									    <?=__('Total withdrawal digital assets')?>
								    </td>
								    <td colspan="3" class="right gray_back">
									    <span class="amount blue" id="totalValuekrw">0</span><span class="unit unit_data"></span>
								    </td>
							    </tr>
						    </table>
					    </div>

					<!-- <input type="text" id="wallet_address" name="wallet_address" value="" class="wallet_address" placeholder="<?=__('No registered wallet address.') ?> <?=__('Please register your wallet address.') ?>" /> -->
                        <select name="wallet_address"  class="form-control" id="wallet_address" style="margin-top: 15px; height: 48px;">
                            <option value=""><?=__('Please select wallet address') ?></option>
                        </select>
					
					    <div class="otp_number">
						    <input type="text" id="otp_number" name="otp_number" value="" placeholder="<?=__('Please enter the OTP number.') ?>" />
<!--						    <input type="button"   id="button_value" name="button_value" value="--><?//=__('Get OTP') ?><!--"/>-->
					    </div><br/>
                        <div id="otp_success" class="alert alert-success" style="    display: none;">
					    </div>
                        <div id="notauthw"><span style="color: red;"><a id="elink" href="/front2/users/id-verification"><?=__('Go to auth') ?></a></span></div>
					    <br/>
					    <div >
						    <button name=""  id="withdrawrequestdata"class="middle"><?=__('Withdrawal request') ?></button>
					    </div>
				    </span>
					<span id="table_withdrowl2">
					<table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="color:#000"><?=__('Coin Name') ?></th>
                                <th style="color:#000"><?=__('Main Account') ?></th>
                                <th style="color:#000"><?=__('Transfer') ?></th>
                                <th style="color:#000"><?=__('Trading Account') ?></th>
                                <th style="color:#000"><?=__('Reserved') ?></th>
                            </tr>
                        </thead>
                        <tbody id="internal_withdrawlist">
                            <tr>
                                <td><span id="unitdatatable_withdrowl2"></span>
                                    <strong></strong>
                                </td>
                                <td>
                                    <span id="amount_datatable_withdrowl2"></span>
                                </td>
                                <td>
                                    <span id="transfer_amount"></span>
                                </td>
                                <td>
                                    <span id="tradingBalancetable_withdrowl2"></span>
                                </td>
                                <td>
                                    <span id="radingBalanceReserved"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

					<div class="desc_title">
						<?=__('Notes withdrawal')?>
					</div>

					<div class="desc">
						<p>- <?=__('Notes withdrawal1')?></p>

						<p>- <?=__('Notes withdrawal2')?></p>

						<p>- <?=__('Notes withdrawal3')?></p>

						<p>- <?=__('Notes withdrawal4')?></p>

						<p>- <?=__('Notes withdrawal5')?></p>

						<p>- <?=__('Notes withdrawal6')?>
						  <?=__('Notes withdrawal7')?></p>

						<p class="red">- <?=__('Notifications for deposit requests6')?></p>
					</div>
				</div>

            <!--KRW Withdrawal -->

            <div style="display:none;" class="common_tab" id="krwwithdrawal_tab_content">
                <div class="exting">
                    <b><?= __('Total Buy Amount: '); ?></b> <span id="totalBuyAmountW"><?= number_format($totalBuy,2); ?> KRW</span>, <b><?= __('Total Sell Amount: '); ?></b>
                    <span id="totalSellAmountW"> <?= number_format($totalSell,2); ?> KRW</span>, <b><?= __('Total Deposit Amount: '); ?></b>
                    <span id="totalDepositAmount"> <?= number_format($totalDeposit,2); ?> KRW</span>, <b><?= __('Total Old Deposit Amount: '); ?></b>
                    <span id="totalOldDepositAmount"> <?= number_format($totalOldDeposit,2); ?> KRW</span><br /><br />
                    <br />
                    <div class="flex-container">
                        <div id="ifNotAuthW" class="flex-child">
                            <span style="color: red;"><a style="color: red" id="elink" href="/front2/users/id-verification"><?= __('User Level: 1, Please authenticate yourself to be able to withdraw'); ?></a></span>
                        </div>
                        <div id="ifLessW" class="flex-child">
                            <span style="color: red;"><?= __('Withdrawal conditions do not match.'); ?></span>
                        </div>
                        <div id="ifDepositW" class="flex-child" style="position: relative;">
                            <img id="depositImg" src="<?php echo $this->request->webroot ?>assets/html/images/cross.png" style="position: absolute;
                        top: -40px; left: 50%; transform: translateX(-50%);" />
                            <span style="color: red;"><?= __('Withdrawal conditions do not match'); ?></span>
                        </div>
                        <div id="ifDepositPending" class="flex-child" style="margin-left: margin-left: 5px;">
                            <span style="color: red;"><?= __('입금신청중'); ?></span>
                        </div>
                        <div id="ifWithdrawPending" class="flex-child" style="margin-left: margin-left: 5px;">
                            <span style="color: red;"><?= __('출금신청중'); ?></span>
                        </div>
                    </div>
                </div>
                <span id="table_withdrowl1">
            <div class="table_withdrowl" style="position: relative;">
              <table class="withdrawal">
                <tr>
                  <td class="title">
                    <?= __('Available Amount') ?>
                  </td>
                  <td colspan="3" class="right">
                    <span class="amount" id="krw_amount_data" name="krw_amount_data"> <?= number_format((float)$main,2);?></span><span class="unit">KRW</span>
                  </td>
                </tr>
                <tr>
                  <td class="title">
                    <?= __('Requested Amount') ?>
                  </td>
                  <td>
                      <input type="text" name="req_amount_krw" id="req_amount_krw" class="req_amount" placeholder="<?= __('Enter Amount') ?>" onkeypress="return isNumberKey(this, event);"/><span class="unit"> KRW</span>
                  </td>
                  <td class="title">
                    <?= __('Withdrawal fee') ?>
                  </td>
                  <td class="right">
                    <span class="amount" id="krwWithdrawalfee">1,000</span><span class="unit">KRW</span>
                  </td>
                </tr>
              </table>
              <div class="cls">
                <br />
                <br />
              </div>
              <table class="withdrawal">
                <tr>
                  <td class="title" style="width: max-content;color: blue;">
                    <?= __('Total Amount') ?>
                  </td>
                  <td style="width: 80%;" class="right">
                    <span name="totalAmountkrw" class="amount blue" id="totalAmountkrw">0</span><span style="color:blue;"> &nbsp;&nbsp;KRW</span>
                  </td>
                </tr>
              </table>
              <div class="cls">
                  <br/>
                  <br/>
              </div>
              <span><?= __('Details about withdrawal account'); ?></span>
              <table class="withdrawal" style="margin-top: 10px;">
                <tr>
                  <td class="title" style="width: 23%;">
                    <?= __('Bank Account Number') ?>
                  </td>
                  <td colspan="3" style="text-align: start;">
                    <span id="bank_account" style="padding-left: 10px;"> <?= $account; ?></span>
                  </td>
                </tr>
                <tr>
                  <td class="title" style="width: max-content;">
                    <?= __('Bank Name') ?>
                  </td>
                  <td style="text-align: start;">
                    <span id="bank_name" style="padding-left: 10px;"><?= $bank; ?></span>
                  </td>
                  <td class="title" style="width: 20%;">
                    <?= __('Account Holder') ?>
                  </td>
                  <td style="text-align: start;">
                    <span id="account_holder" style="padding-left: 10px;"><?php echo ucfirst($_SESSION['Auth']['User']['name']); ?></span>
                  </td>
                </tr>
              </table>
              <div class="cls">
                <br />
                <br />
              </div>
              <table class="withdrawal">
                <tr>
                  <td style="border: 1px #1b0552 solid;">
                    <input type="text" maxlength="6" id="otp_number_krw" name="otp_number" class="otp_number_krw" value="" placeholder="<?= __('Please enter the OTP number.') ?>" />

                  </td>
                </tr>
              </table>

              <div id="otp_success_krw" class="alert alert-success" style="display: none;">
              </div>
                <div id="ifpendingW"> <span style="color: red;">주문 처리중 입니다</span></div>
                <div id="notauthw"><span style="color: red;"><a style="color: red;" id="elink" href="/front2/users/id-verification"><?=__('Go to auth') ?></a></span></div>
              <br />
              <div style="position:absolute; left:35%; margin-top: 5%; transform: translateX(-50%) padding: 10px;">
                <button name="" id="withdraw_btn" class="middle"><?= __('Withdrawal request') ?></button>
              </div>
              <div class="cls">
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
              </div>
            </div>

          </span>

            </div>

            <!-- KRW Withdrawal end -->

            <!-- Statement Start-->

            <div style="display:none;" class="common_tab" id="statement_tab_content">
                <ul class="tab_menu_statement">
                    <li id="depositListStatement_on" class="depositListState" onClick="tabClick('depositListState')"><a href="javascript:void(0);"><?=__('Deposit Statement') ?></a></li>
                    <li id="withdrawalListStatement_on" class="withdrawListState" onClick="tabClick('withdrawListState')"><a href="javascript:void(0);"><?=__('Withdrawal Statement') ?></a></li>
                </ul>
                <div class="order_tab_system" id="deposit_li_div" style="margin-top: 5%;">

                    <table class="list tablewidth" id="depositTable" style="background:#fff;">
                        <thead>
                        <tr>
                            <td><?=__('Transaction') ?></td>
                            <td><?=__('Amount') ?></td>
                            <td><?=__('Date & Time') ?></td>
                            <td><?=__('Status') ?></td>
                        </tr>
                        </thead>
                        <tbody id="myDepositlist">

                        </tbody>
                    </table>

                </div>
                <div class="order_tab_system" id="withdraw_li_div" style="display:none">
                    <table class="list tablewidth" id="withdrawTable" style="background:#fff;">
                        <thead>
                        <tr>
                            <td><?=__('Transaction') ?></td>
                            <td><?=__('Amount') ?></td>
                            <td><?=__('Fees') ?></td>
                            <td><?=__('Date & Time') ?></td>
                            <td><?=__('Status') ?></td>
                        </tr>
                        </thead>
                        <tbody id="myWithdrawlist">

                        </tbody>
                    </table>


                </div>


            </div>

            <!-- Statement End -->

				
				<div style="display:none;" class="common_tab common_tab22 asset_list"  id="breakdown_tab_content" >
					<table>
							<thead>
								<tr>
									<td><?=__('Division')?></td>
									<td><?=__('Request amount')?>(RBTC)</td>
									<td><?=__('Fee')?>(RBTC)</td>
									<td><?=__('Amount')?>(RBTC)</td>
									<td><?=__('Date')?></td>
									<td><?=__('State')?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6" class="blank">
										<?=__('No transaction details') ?>
									</td>
								</tr>
							</tbody>
						</table>
				</div>
				<form   class="rwwd_modal_form1"  onSubmit="return false;">
					<div style="display:none;" class="common_tab"  id="withdrawal_addr_tab_content" >
						<div class="asset_list common_tab22">
							<table>
								<thead>
									<tr>
										<td></td>
										<td><?=__('Wallet Name')?></td>
										<td><?=__('Wallet Address')?></td>
										<td><?=__('Date and time of registration')?></td>
									</tr>
								</thead>
								<tbody id="withdrawal_addr">
								
									
									
								</tbody>
								
							</table>
						</div>

						<table style="width:100%">
							<tr>
								<td style="text-align:left">
								<button type="submit"  name="" id="deleteAddress" class="white"><?=__('Delete')?></button>
								</td>
								<td style="text-align:right">
									<a name="" onclick="openAddWithdrawalWalletAddrModel()" >+ <?=__('Register withdrawal address')?></a>
								</td>
							</tr>
						</table>
						
						
						<div class="desc dest_mt70" style="margin-top:70px">
							<p>- <?=__('Notes Address Text1')?></p>

							<p>- <?=__('Notes Address Text2')?></p>
						</div>


						<div id="add_address">
							<div style='margin:40px 0 30px; color:#000000; font-size: 22px;'><?=__('Register withdrawal address')?></div>
							<table>
								<tr>
									<td class="title">
										<?=__('Name2')?>
									</td>
									<td>
										<input type="text" name="" value="" />
									</td>
								</tr>
								<tr>
									<td class="title">
										<?=__('Wallet Address')?>
									</td>
									<td>
										<input type="text" name="" value="" />
									</td>
								</tr>
								<tr>
									<td class="title">
										<?=__('Enter OTP Number')?>
									</td>
									<td>
										<input type="text" name="" value="" />
									</td>
								</tr>
							</table>
							<div>
								<button class='white' onclick='hideMsgWindow()'><?=__('Cancel') ?></button>
								<button><?=__('Registration')?></button>
							</div>
						</div>
					</div>
				</form>
				

			</td>
		</tr>
		</table>

	
	</div>
	</div>
	<div class="cls"></div>
	</div>

</div>
</div>
<div id="myModalDeposit" class="modal fade" role="dialog" >
  <div class="modal-dialog" style='color:#000;' >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="modal_coin_name"></span> <?= __('Transfer to ')?> <span id="wallet_name"> </span> <?= __('')?></h4>
      </div>
      <div class="modal-body" style="text-align:center;">
		<form action="#" autocomplete="off" id="deposit_modal_form" enctype="multipart/form-data">
		<input type="hidden" class="form-control" id="coin_id" name="coin_id">
		<input type="hidden" class="form-control" id="transfer_to" name="transfer_to">
		
		
		<div class="form-group">
		  <label for="email"><?=  __('Amount: ')?></label>
		  <input type="text" class="form-control" required placeholder="<?= __('Enter Amount')?>" name="amount">
		</div>
		

		<button type="submit" class="btn btn-default" id="btnSubmitNew">Submit</button>
		<img id="model_qr_code_flat" style="display:none;" src="/ajax-loader.gif" />
		 <div id="get_resp" style="display:none;"></div>
		
	  </form>
      </div>
      
    </div>

  </div>
</div>
<input  type="hidden" id="selected_coind_id" />




  <!-- Modal -->
<div id="myModalAddWithdrawalWalletAddr" class="modal fade" role="dialog" >
  <div class="modal-dialog" style='color:#000;' >
  	<?php echo $this->Form->create('',['url'=>['controller'=>'Assets','action'=>'registerWithdrawalWalletAddrAjax']]);?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= __('Register Withdrawal Wallet Address')?></h4>
      </div>
      <div class="modal-body" style="text-align:center;">
		<!-- <form action="#" autocomplete="off" id="rwwd_modal_form"  onSubmit="return false;" enctype="multipart/form-data"> -->
		<input type="hidden" class="form-control" id="rwwd_coin_id" name="coin_id" >
	
		
		
		<div class="form-group">
		  <label for="email"><?= __('Wallet Name:')?></label>
		  <input type="text" class="form-control" style="background-color:#fff!important;;" required placeholder="<?= __('Wallet Name')?>" readonly id="rwwd_wallet_name" name="rwwd_wallet_name">
		</div>
		
		<div class="form-group">
		  <label for="email"><?= __('Wallet Address:')?></label>
		  <input type="text" class="form-control" style="background-color:#fff!important;" required placeholder="<?= __('Enter Wallet Address')?>"  id="rwwd_wallet_addr" name="rwwd_wallet_addr">
		</div>
		

		<button type="button" class="btn btn-default" id="btnSubmit"><?= __('Submit')?></button>
		<img id="rwwd_model_ajax" style="display:none;" src="/ajax-loader.gif" />
		 <div id="rwwd_get_resp" class="alert" style="display:none;"></div>
		
	  <!-- </form> -->
      </div>
      
    </div>
    <?php echo $this->Form->end();?>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    // 21.01.27, YMJ
    $(function(){
        $("#rwwd_wallet_addr").on('input', function() {
            ctcWalletgetUser();
        });
    });
    function ctcWalletgetUser() {
        var walletaddr = $("#rwwd_wallet_addr").val();
        $("#ajax_coin_tr").show();
        $.ajax({
            url: "<?php echo $this->Url->build(['controller' => 'Assets', 'action' => 'ctcwalletgetuserajax']); ?>/"+walletaddr,
            dataType: 'JSON',
            success: function(resp) {
                if ( resp.success == "true") {
                    var name = resp.name;
                    //var phone = resp.phone;
                    $("#rwwd_wallet_name").val(name);
                    $("#rwwd_get_resp").hide();
                } else {
                    $("#rwwd_wallet_name").val('');
                    $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html("<?=__('Member information not found')?>").show();
                }
            },
            error: function(e) {
                $("#rwwd_wallet_name").val('');
                $("#ajax_coin_tr").hide();
                //$("#model_qr_code_flat").hide();
            }
        });
    }

    var getMyCoinId ="";
 function getInternalTransaction(){
		$("#ajax_coin_tr").show();
		$.ajax({
			url: "<?php echo $this->Url->build(['controller'=>'Assets','action'=>'internaltransactionajax']); ?>/"+getMyCoinId,
			dataType: 'JSON',
			success: function(resp) {
				
				//$("#model_qr_code_flat").hide();
				var respVal = resp.data.coinlist;
				
				 var html = '';
				
					var rightArrowClick = "transferAmount('"+respVal.coinShortName+"','trading')";
					var leftArrowClick = "transferAmount('"+respVal.coinShortName+"','main')";
					html = html + '<tr>';
					html = html + '<td><strong>'+respVal.coinShortName+'</strong> '+respVal.coinName+'</td>';
					html = html + '<td>'+respVal.principalBalance+'</td>';
					html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightArrowClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left"  onClick="'+leftArrowClick+'"></span></td>';
					html = html + '<td>'+respVal.tradingBalance+'</td>';
					html = html + '<td>'+respVal.reserveBalance+'</td>';
					html = html + '</tr>';
				
				$('#internal_withdrawlist').html(html);
				
			},
			error: function (e) {
				$("#ajax_coin_tr").hide();
				//$("#model_qr_code_flat").hide();
			}
		});
  }
  function mainAndTradingBalanceTotal(){
		$("#ajax_coin_tr").show();
		$.ajax({
			url: "<?php echo $this->Url->build(['controller'=>'Assets','action'=>'mainAndTradingBalanceTotal']); ?>/",
			dataType: 'JSON',
			success: function(resp) {
				var total_val = resp.data.total.total_value;
				var principalTotalBalance = resp.data.total.principalTotalBalance;
				var reserveTotalBalance = resp.data.total.reserveTotalBalance;
                var tradingTotalBalance = resp.data.total.tradingTotalBalance;
				$("#total_balance_val").html(total_val);
               // $("#total_coins_val").html(principalTotalBalance);
				$("#resserve_total_balance").html(reserveTotalBalance);
				$("#main_total_balance").html(principalTotalBalance);
                $("#trading_total_balance").html(tradingTotalBalance);
			},
			error: function (e) {
				$("#ajax_coin_tr").hide();
				//$("#model_qr_code_flat").hide();
			}
		});
  }
  
  function currentCoinBalance(){
			$("#ajax_coin_tr").show();
		$.ajax({
			url: "<?php echo $this->Url->build(['controller'=>'Assets','action'=>'selectedCoinAmountAjax']); ?>/"+getMyCoinId,
			dataType: 'JSON',
			success: function(resp) {
				var currentCoinTotalVal = resp.data.current_coin.currentCoinTotalVal;
				var principalBalance = resp.data.current_coin.principalBalance;
				var reserveBalance = resp.data.current_coin.reserveBalance;
                var tradingBalance = resp.data.current_coin.tradingBalance;
				$("#retained_quantity_"+getMyCoinId).html(principalBalance);
				$("#krw_quantity_"+getMyCoinId).html(currentCoinTotalVal);
				$("#amount_data").html(principalBalance);
				$("#resserve_total_balance").html(reserveBalance);
				$("#main_total_balance").html(principalBalance);
                $("#trading_total_balance").html(tradingBalance);
			},
			error: function (e) {
				$("#ajax_coin_tr").hide();
				//$("#model_qr_code_flat").hide();
			}
		});
  }  
  
  
$(document).ready(function(){
    var pendingVal = "<?= $pendingVal; ?>";
    var pendingValw = "<?= $pendingValw; ?>";
    var bankAuth = "<?= $bankAuth; ?>";
    var emailAuth = "<?= $emailAuth; ?>";
    var otpAuth = "<?= $otpAuth; ?>";
    var totalBuy = "<?= $totalBuy; ?>";
    var totalSell = "<?= $totalSell; ?>";
    var totalDeposit = "<?= $totalDeposit; ?>";
    var totalOldDeposit = "<?= $totalOldDeposit; ?>";
    var totalReward = "<?= $totalReward; ?>";
    var totalBuyAmount = parseFloat(totalBuy);
    var totalSellAmount = parseFloat(totalSell);
    var totalDepositAmount = parseFloat(totalDeposit);
    var totalOldDepositAmount = parseFloat(totalOldDeposit);
    var totalRewardAmount = parseFloat(totalReward);
    var totalDepAmount = parseFloat(totalOldDepositAmount + totalDepositAmount);
    var deposit = "<?= $deposit; ?>";
    if(totalBuyAmount < 50000 || totalSellAmount < 50000){
        $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#req_amount").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdrawrequestdata").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#ifLess").show();
        $("#ifLessW").show();
        $("#ifDeposit").hide();
        $("#ifDepositW").hide();
    } else if(deposit === "Y"){
        $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#req_amount").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdrawrequestdata").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#ifDeposit").show();
        $("#ifDepositW").show();
        $("#ifLess").hide();
        $("#ifLessW").hide();
    }else {
        $("#ifLess").hide();
        $("#ifLessW").hide();
        $("#ifDeposit").hide();
        $("#ifDepositW").hide();
        if(bankAuth === "Y" && emailAuth === "Y" && otpAuth === "Y" && pendingVal === "N" && pendingValw === "N"){
            $("#req_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#req_amount_krw").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#otp_number_krw").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#withdraw_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
        } else {
            $("#amount_deposited").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#bank_deposit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#req_amount").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#otp_number").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#withdrawrequestdata").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#notauthw").show();
            $("#notauth").show();
            $("#ifNotAuth").show();
            $("#ifNotAuthW").show();
            $("#ifpendingD").hide();
            $("#ifpendingW").hide();
            $("#ifLess").hide();
            $("#ifLessW").hide();
            $("#ifDeposit").hide();
            $("#ifDepositW").hide();

        }
    }

    if(bankAuth === "N" || emailAuth === "N" || otpAuth === "N"){
        $("#amount_deposited").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#bank_deposit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#req_amount").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#withdrawrequestdata").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#notauthw").show();
        $("#notauth").show();
        $("#ifNotAuth").show();
        $("#ifNotAuthW").show();
        $("#ifpendingD").hide();
        $("#ifpendingW").hide();
        $("#ifLess").hide();
        $("#ifLessW").hide();
        $("#ifDeposit").hide();
        $("#ifDepositW").hide();
    } else {
        if(pendingVal === "Y" || pendingValw === "Y"){
            $("#amount_deposited").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#bank_deposit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});


            if(pendingVal === "Y"){
                $("#ifDepositPending").show();
                $("#ifpendingD").show();
            }
            if (pendingValw === "Y"){
                $("#ifWithdrawPending").show();
                $("#ifpendingW").show();
            }

            $("#notauthw").hide();
            $("#notauth").hide();
            $("#ifNotAuth").hide();
            $("#ifNotAuthW").hide();
        } else {
            $("#amount_deposited").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#bank_deposit_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            // $("#req_amount_krw").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            // $("#otp_number_krw").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            $("#withdraw_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            // $("#req_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            // $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
            // $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');


            if(pendingVal === "N"){
                $("#ifDepositPending").hide();
                $("#ifpendingD").hide();
            }
            if (pendingValw === "N"){
                $("#ifWithdrawPending").hide();
                $("#ifpendingW").hide();
            }
            $("#notauthw").hide();
            $("#notauth").hide();
            $("#ifNotAuth").hide();
            $("#ifNotAuthW").hide();
        }
    }

    $("#depositkrw_on").addClass("deposit on");
    localStorage.removeItem("value");
	mainAndTradingBalanceTotal();
var price=$(".totalkrw").val();
var principalBalanceTotal=$(".principalBalanceTotal").val();
var reserveBalance=$(".reserveBalance").val();
$(".main_Balance11").html(principalBalanceTotal);
$(".TradingAccount").html(reserveBalance);
$(".pricetotal1223").html(price);
	
	$("#table_withdrowl2").hide();
	coinList();
	$("#rwwd_modal_form").submit(function(){
		$("#rwwd_model_ajax").show();
		$.ajax({
			url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"registerWithdrawalWalletAddrAjax"]) ?>',
			type:'POST',
			data:$(this).serialize(),
			dataType:'JSON',
			success:function(resp){
				if(resp.success=="false"){
					$("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(resp.message).show();
					
				}
				else if(resp.success=="true"){
					$("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
					$("#rwwd_modal_form").trigger("reset");
				}
				setTimeout(function(){ 
					$("#rwwd_get_resp").html("").hide();
				},6000);
				$("#rwwd_model_ajax").hide();
				
			}
		});
	});

	// tab_menu hide
	// if clicked coin list item then tab_menu show, find $(".tab_menu").show();
	$('.tab_menu').hide();
    // add 'on' class to KRW (in mycoinlist)
    const setValue = $('.setvalue');
    for(let i=0; i<setValue.length; i++) {
        const item = setValue[i];
        if (item.innerText.trim() === "KRW") {
            // const itemParent = $(item).parent().parent();
            // itemParent.addClass('on');
            item.click();
        }
    }
})
	

function openAddWithdrawalWalletAddrModel(){
	var coin_id = $("#selected_coind_id").val();
	$("#rwwd_coin_id").val(coin_id);
	$("#myModalAddWithdrawalWalletAddr").modal('show');
}	
var coin="BTC";
function sideBarCoinClick(coin){
	$(".common_tab").hide();
	$("#mesg").hide();
    $('.tab_menu').show();
	$("#coin_name").html(coin);
	$("#default_content").hide();
	var btcAddr = '<?php echo $userDetail["btc_address"] ?>';
	var ethAddr = '<?php echo $userDetail["eth_address"] ?>';
	var walletAddr = (coin=="BTC") ? btcAddr : ethAddr;
	
	var qrCodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl="+walletAddr;
	$("#qr_code_image").attr('src',qrCodeUrl);
	$("#wallet_addr_input").val(walletAddr);
	$("#selected_coind_id").val(coin);
	$("#deposit_tab_content").show();
	
	
}

function tabClick(tab_name) {
    $(".common_tab").hide();
    var selectedCoinId = $("#selected_coind_id").val();
    if (tab_name === "deposit") {
        localStorage.setItem("value", "deposit");
        $("#deposit_tab_content").show();
        $("#deposit_on").addClass("deposit on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#statement_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    } else if (tab_name === "withdrawal") {
        localStorage.setItem("value", "withdrawal");
        $("#withdrawal_tab_content").show();
        $("#table_withdrowl1").show();
        $("#table_withdrowl2").hide();
        $("#deposit_on").removeClass("on");
        $("#statement_on").removeClass("on");
        $("#withdrawal_on").addClass("deposit on");
        $("#breakdown_on").removeClass("on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
        $("#withdrawal_type_out").prop("checked", true);
        $("#withdrawal_type_in").prop('checked', false);
    } else if (tab_name === "breakdown") {
        localStorage.setItem("value", "breakdown");
        $("#breakdown_tab_content").show()
        $("#breakdown_on").addClass("deposit on");
        $("#statement_on").removeClass("on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");

        $("#withdrawal_addr_on").removeClass("on");
    } else if (tab_name === "withdrawal_addr") {
        localStorage.setItem("value", "withdrawal_addr");
        $("#withdrawal_addr_tab_content").show();
        $("#statement_on").removeClass("on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_addr_on").addClass("deposit on");

    } else if (tab_name === "depositkrw") {
        localStorage.setItem("value", "depositkrw");
        $("#default_content").show();
        $("#depositkrw_on").addClass("deposit on");
        $("#deposit_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#statement_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    } else if (tab_name === "krwwithdrawal") {
        localStorage.setItem("value", "krwwithdrawal");
        $("#krwwithdrawal_tab_content").show();
        $("#krwwithdrawal_on").addClass("deposit on");
        $("#depositkrw_on").removeClass("on");
        $("#statement_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    } else if (tab_name === "statement") {
        localStorage.setItem("value", "statement");
        $("#statement_tab_content").show();
        $("#depositListStatement_on").addClass("deposit on");
        $("#withdrawalListStatement_on").removeClass("on");
        myDepositListAjax();
        $("#statement_on").addClass("deposit on");
        $("#withdraw_li_div").hide();
        $("#deposit_li_div").show();
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    }
    else if (tab_name === "depositListState") {
        localStorage.setItem("value", "depositListState");
        $("#statement_tab_content").show();
        $("#withdraw_li_div").hide();
        $("#deposit_li_div").show();
        myDepositListAjax();
        $("#depositListStatement_on").addClass("deposit on");
        $("#withdrawalListStatement_on").removeClass("on");
        $("#statement_on").addClass("deposit on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    }
    else if (tab_name === "withdrawListState") {
        localStorage.setItem("value", "withdrawListState");
        $("#statement_tab_content").show();
        $("#withdraw_li_div").show();
        $("#deposit_li_div").hide();
        myWithdrawListAjax();
        $("#depositListStatement_on").removeClass("on");
        $("#withdrawalListStatement_on").addClass("deposit on");
        $("#statement_on").addClass("deposit on");
        $("#depositkrw_on").removeClass("on");
        $("#krwwithdrawal_on").removeClass("on");
        $("#deposit_on").removeClass("on");
        $("#breakdown_on").removeClass("on");
        $("#withdrawal_on").removeClass("on");
        $("#withdrawal_addr_on").removeClass("on");
    }
}
function createWallet() {
	document.location.href = "<?php echo $this->Url->build(['controller'=>'assets','action'=>'deposit2']) ?>";
}

function copyToClipboard(){
    if($("#wallet_addr_input").val() !== '') {
        $("#wallet_addr_input").select();
        document.execCommand("copy");
        $("#copy_msg").html("Wallet Address Copied").show();
        setTimeout(function () {
            $("#copy_msg").html("").hide();
        }, 5000);
    }
}


function coinList(){
	
}

</script>

<script>
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

function isNumberKey(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode === 46) {
        //Check if the text already contains the . character
        return txt.value.indexOf('.') === -1;
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}

function callUserFeeAjaxCallBack(getResp){
	withdrawFee = getResp;
	$("#withdraw_fee_percent").html("("+withdrawFee+" %)");
}

function callUserFeeAjax(getCoinId){
	$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"userFeeSetting"]) ?>/'+getCoinId,
		type:'GET',
		dataType:'JSON',
		success:function(resp){
			
			callUserFeeAjaxCallBack(resp.data.user_fee);
			
		}

	})
}
$( ".setvalue" ).click(function() {
	$(".tab_menu").show();
    $("#mesg").hide();
	var id = $(this).attr("data-id");
	getMyCoinId = $(this).attr("data-coin-id");
	var getMyCoinTitle = $(this).attr("data-coin-title");
	$("tbody#mycoinlist tr").removeClass("on");
	$(this).parent().parent().addClass("on");
	
	let new_value=id.split("_");
	$("#withdrawal_type_out").prop("checked", true);
	$("#withdrawal_type_in").prop('checked', false);
	$("#otp_number").val('');
	//$("#otp_number").prop('disabled', true);
	
	if(new_value!=undefined && new_value!=null && new_value!=''){
		var quantityValue = $("#quantity_"+new_value[2]).val();
		var krwValue = $("#krw_"+new_value[2]).val();
		var coinNameValue = $("#coin_name_"+new_value[2]).val();
		$("#coin_name_id_data").html(getMyCoinTitle);
		var tradingBalance = $("#tradingBalance_"+new_value[2]).val();
		var coinAddress = $("#coinAddress_"+new_value[2]).val();
		var reserveBalance = $("#reserveBalance_"+new_value[2]).val();
		$("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='+coinAddress+'" style="width:170px; height:170px;" />')
		$("#wallet_addr_input").val(coinAddress);
		$("#amount_data").html(quantityValue);
		$("#unit_data").html(coinNameValue);
		$(".unit_data").html(coinNameValue);
		localStorage.setItem("amount_data", quantityValue);
		localStorage.setItem("unit_data", coinNameValue);
		localStorage.setItem("tradingBalance", tradingBalance);
		localStorage.setItem("reserveBalance", reserveBalance);

		var value = localStorage.getItem("value");
		if(value==null){
			$( "#depositkrw_on" ).trigger( "click" );
			$( "#depositkrw_on" ).addClass("deposit on");
		}else{
			$( "#"+value+"_on" ).trigger( "click" );
			$("#"+value+"_on"  ).addClass(value+"on");
		}
		

		
		$("#amountkrw").html(0);
		//$("#req_amount").val(0);
		$("#totalValuekrw").html(0);
	}
	$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
		type:'post',
		data:{coinName:coinNameValue},
		success:function(resp){
			
			var resp =JSON.parse(resp);
			if(resp.success=="true"){
				var getHtml="";
				var getHtml1 ="";
				$.each(resp.data,function(key,value){
					//console.log(value);
					getHtml = getHtml+'<tr id=td_data_'+value.id+'>';
					getHtml = getHtml+'	<td><input type="checkbox"type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
					getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
					getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
					getHtml = getHtml+'	<td><span>'+moment(value.modified).format('MM-DD-YYYY h:mm A');+'</span></td>';
					getHtml = getHtml+'</tr>';
					getHtml1 = getHtml1+'<option value='+value.wallet_address+'> '+value.wallet_address+' </option>';
					
				});
				
			}else{
					getHtml = getHtml+'<tr>';
					getHtml = getHtml+'	<td colspan="4"><?= __('No registered wallet address.') ?></td>';
					getHtml = getHtml+'</tr>';
			}
			
			$("#withdrawal_addr").html(getHtml);
			$("#wallet_address").html(getHtml1);
			
		}

	})
	
	currentCoinBalance();
	callUserFeeAjax(getMyCoinId);
});

function display_data(id,short_name,principalBalance,krw_value,short_name,getMyCustomPrice,reserveBalance,coinAddress,coinName){

	// let new_value=id.split("_");
	$("#withdrawal_type_out").prop("checked", true);
	$("#withdrawal_type_in").prop('checked', false);
	
		var quantityValue = principalBalance;
		var krwValue = krw_value;
		var coinNameValue = short_name;
		var tradingBalance =getMyCustomPrice;
		var coinAddress = coinAddress;
		var reserveBalance = reserveBalance;
		
		$("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='+coinAddress+'" style="width:170px; height:170px;" />')
		$("#wallet_addr_input").val(coinAddress);

		$("#amount_data").html(quantityValue);
		$("#unit_data").html(coinNameValue);
		$(".unit_data").html(coinNameValue);
		localStorage.setItem("amount_data", quantityValue);
		localStorage.setItem("unit_data", coinNameValue);
		localStorage.setItem("tradingBalance", tradingBalance);
		localStorage.setItem("reserveBalance", reserveBalance);

		
		// $( "#deposit_on" ).trigger( "click" );
		// $( "#deposit_on" ).addClass("deposit on");
		var value = localStorage.getItem("value");
		if(value==null){
			$( "#deposit_on" ).trigger( "click" );
			$( "#deposit_on" ).addClass("deposit on");
		}else{
			$( "#"+value+"_on" ).trigger( "click" );
			$("#"+value+"_on"  ).addClass(value+"on");
		}
		
		
		$("#amountkrw").html(0);
		//$("#req_amount").val(0);
		$("#totalValuekrw").html(0);
	
	$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
		type:'post',
		data:{coinName:coinNameValue},
		success:function(resp){
			
			var resp =JSON.parse(resp);
			var getHtml1 ="";
			if(resp.success=="true"){
				var getHtml="";
				$.each(resp.data,function(key,value){
					//console.log(value);
					getHtml = getHtml+'<tr  id=td_data_'+value.id+'>';
					getHtml = getHtml+'	<td><input type="checkbox" type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
					getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
					getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
					getHtml = getHtml+'	<td><span>'+moment(value.modified).format('MM-DD-YYYY h:mm A');+'</span></td>';
					getHtml = getHtml+'</tr>';
					getHtml1 = getHtml1+'<option value='+value.wallet_address+'> '+value.wallet_address+' </option>';

				});
				
			}else{
					getHtml = getHtml+'<tr>';
					getHtml = getHtml+'	<td colspan="4"><?= __('No registered wallet address.') ?></td>';
					getHtml = getHtml+'</tr>';
			}
			
			$("#withdrawal_addr").html(getHtml);
			$("#wallet_address").html(getHtml1);	
		}

	})
	
	getMyCoinId = id;
	$("#coin_name_id_data").html(coinName+"("+coinNameValue+")")
	currentCoinBalance();
}


$( document ).ready(function() {
    var mainBal = "<?= $main; ?>";
    var mainAccount = parseFloat(mainBal);

    $( "#withdrawal_type_in" ).click(function() {
        getInternalTransaction();
        /* var tradingBalance=localStorage.getItem("tradingBalance");
        var unit_data=localStorage.getItem("unit_data");
        var amount_data=localStorage.getItem("amount_data");
        var reserveBalance=localStorage.getItem("reserveBalance");


        localStorage.setItem("tradingBalanceid", 1);
        localStorage.setItem("amount_dataid", 0);
        $("#unitdatatable_withdrowl2").html(unit_data);
        $("#amount_datatable_withdrowl2").html(amount_data);
        $("#tradingBalancetable_withdrowl2").html(tradingBalance);
        $("#radingBalanceReserved").html(reserveBalance);
        var rightArrowClick = "transferAmount('"+unit_data+"','trading')";
        var leftArrowClick = "transferAmount('"+unit_data+"','main')";

        var html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightArrowClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left"  onClick="'+leftArrowClick+'"></span></td>';
        $("#transfer_amount").html(html) */

        $("#table_withdrowl1").hide();
        $("#table_withdrowl2").show();

    });

    $( "#withdrawal_type_out" ).click(function() {
        var amount_data=localStorage.getItem("amount_data");
        var unit_data=localStorage.getItem("unit_data");
        localStorage.setItem("tradingBalanceid", 0);
        localStorage.setItem("amount_dataid", 1);
        $("#table_withdrowl1").show();
        $("#table_withdrowl2").hide();
        $("#amount_data").html(amount_data);
        $("#unit_data").html(unit_data);
        currentCoinBalance();
    });



    $('#req_amount').keyup(function(){
        var req_amount=$("#req_amount").val();
        var krwamount=$('#amount_data').text();
        var unit_data=$('#unit_data').text();
        var krw_value=$('#coin_id_'+unit_data).val();

        /* 	if(parseFloat(req_amount)<=parseFloat(krwamount)){
                $("#totalValuekrw").html(req_amount);
            }else{
                toastr.error('<?= __('Please enter valid amount')?>')

	} */
        var new_value=parseFloat(req_amount)*parseFloat(krw_value);
        var fee_charges=req_amount*<?php echo $adminWithdrawalFeePercent; ?>/100;

        $("#amountkrw").html(new_value);
        $("#withdrawalfee").html(fee_charges);

        reamainingBalance = req_amount - fee_charges;
        $("#totalValuekrw").html(reamainingBalance);

    });

    $('#req_amount_krw').keyup(function(e) {
        var req_amount = $("#req_amount_krw").val();
        var total_amount = $("#totalAmountkrw");
        var totalAmount = +req_amount + +1000;
        var available_amount = document.getElementById("krw_amount_data").innerText;
        total_amount.html(numberWithCommas(parseFloat(totalAmount).toFixed(2)));
        var availAmount = available_amount.replace(/\,/g,'');
        if(totalAmount > availAmount){
            toastr.error('<?= __('Please enter correct amount') ?>');
            e.preventDefault();
            $(this).val('');
            total_amount.html("0");
            return false;
        }
        if(req_amount === ""){
            total_amount.html("0");
        }
    });

    $('#req_amount_krw').change(function(e){
        var req_amount = $("#req_amount_krw").val();
        var total_amount = $("#totalAmountkrw");
        var reqAmount = parseFloat(req_amount);
        if(reqAmount < 50000){
            toastr.error('<?= __('You can only withdraw at least 50,000 KRW or more') ?>');
            e.preventDefault();
            $(this).val('');
            total_amount.html("0");
            return false;
        }
    });

    $( "#btnSubmit" ).click(function() {
        var rwwd_wallet_name=$("#rwwd_wallet_name").val();
        var rwwd_wallet_addr= $("#rwwd_wallet_addr").val();
        if(rwwd_wallet_name===undefined || rwwd_wallet_name===null || rwwd_wallet_name===''){
            toastr.error('<?= __('Please enter wallet name')?>');
            return false;
        }
        if(rwwd_wallet_addr===undefined || rwwd_wallet_addr===null || rwwd_wallet_addr===''){
            toastr.error('<?= __('Please enter wallet address')?>');
            return false;
        }
        var coinName=$("#unit_data").text();
        if(coinName===undefined || coinName===null || coinName===''){
            toastr.error('<?= __('Please enter coin')?>');
            return false;
        }
        if(coinName.toLowerCase()!="btc" && validateInputAddresses(rwwd_wallet_addr)==false){
            toastr.error('<?= __('Please enter valid wallet address')?>');
            return false;
        }
        $("#btnSubmit").prop("disabled",true)
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"insertWalletAddress"]) ?>',
            type:'post',
            data:{rwwd_wallet_name:rwwd_wallet_name,rwwd_wallet_addr:rwwd_wallet_addr,coinName:coinName},
            success:function(resp){

                var result=JSON.parse(resp);
                var getHtml1="";
                if(result.success=="true"){
                    toastr.success("Sucess");
                    $.ajax({
                        url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
                        type:'post',
                        data:{coinName:coinName},
                        success:function(resp){

                            var resp =JSON.parse(resp);
                            if(resp.success=="true"){
                                var getHtml='';

                                $.each(resp.data,function(key,value){
                                    //console.log(value);
                                    getHtml = getHtml+'<tr id=td_data_'+value.id+'>';
                                    getHtml = getHtml+'	<td><input type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
                                    getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
                                    getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
                                    getHtml = getHtml+'	<td><span>'+value.modified+'</span></td>';
                                    getHtml = getHtml+'</tr>';

                                    getHtml1 = getHtml1+'<option value='+value.wallet_address+'> '+value.wallet_address+' </option>';
                                });

                            }else{
                                getHtml = getHtml+'<tr>';
                                getHtml = getHtml+'	<td colspan="4"><?= __('No registered wallet address.') ?></td>';
                                getHtml = getHtml+'</tr>';
                            }

                            $("#withdrawal_addr").html(getHtml);
                            $("#wallet_address").html(getHtml1);

                        }

                    })
                    $("#btnSubmit").prop("disabled",false);
                    $('#myModalAddWithdrawalWalletAddr').modal('hide');

                    return false;
                }else{
                    toastr.error(result.message);
                    $("#btnSubmit").prop("disabled",false);
                    //$('#myModalAddWithdrawalWalletAddr').modal('hide');
                    return false;

                }

                $("#btnSubmit").prop("disabled",false);


            }

        })

        return false;

    });

$( ".rwwd_modal_form1" ).submit(function() {
	var id=$(".rwwd_modal_form1").serialize();
	
	if(id==undefined || id==null || id==''){
		toastr.error('<?= __('Please select an address to delete')?>');
	}else{
	
		$.confirm({
    title: '<?= __('Confirm')?>',
    content: 'Are you sure that, you want to delete this?',
    buttons: {
        confirm: function () {
            $.ajax({
			url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"deleteWalletAddress"]) ?>',
			type:'post',
			data:{id:id},
			success:function(resp){
				var resp =JSON.parse(resp);
				toastr.success('success');
				$.each(resp.data,function(key,value){
					$("#td_data_"+value).remove();
				})
			}
			})
        },
        cancel: function () {
            
        },
        
    }
	
});

	}
});

    $("#withdrawrequestdata").on('click', function() {
        var unitdata = $("#unit_data").text();
        var req_amount = $("#req_amount").val();
        var wallet_address = $("#wallet_address").val();
        var amountkrw = $("#amount_data").text();
        var otp_number = $("#otp_number").val();
        var krwAmount = $("#amountkrw").text();
        var krw = parseFloat(krwAmount);
        var totBlnc = $("#total_balance_val").text();
        var totBalance = totBlnc.replace(/\,/g,'');
        var totalBalance = parseFloat(totBalance);

        // alert("unit data: " + unitdata + ", req amount: " + typeof req_amount + ", amountkrw: " + typeof amountkrw);


        if (unitdata === undefined || unitdata === null || unitdata === '') {
            toastr.error('<?= __('Please select coin') ?>');
            return false;
        }
        if (req_amount === undefined || req_amount === null || req_amount === '') {
            toastr.error('<?= __('Please enter amount you want to withdraw') ?>');
            return false;
        }
        if (wallet_address === undefined || wallet_address === null || wallet_address === '') {
            toastr.error('<?= __('Please enter wallet address') ?>');
            return false;
        }
        if (+req_amount > +amountkrw) {
            toastr.error('<?= __('Please enter valid withdrawal amount') ?>');
            return false;
        }
        if (otp_number === undefined || otp_number === null || otp_number === '') {
            toastr.error('<?= __('Please enter OTP') ?>');
            return false;
        }
        var tradingBalanceid = localStorage.getItem("tradingBalanceid");
        var amount_dataid = localStorage.getItem("amount_dataid");

        var value = "external";
        if (tradingBalanceid !== undefined && tradingBalanceid !== null && tradingBalanceid !== '') {
            if (tradingBalanceid === 1) {
                value = "internal"
            }
        }
        if (amount_dataid !== undefined && amount_dataid !== null && amount_dataid !== '') {
            if (amount_dataid == 1) {
                if (tradingBalanceid == 1) {
                    value = "external"
                }
            }
        }
        // alert("reqAmount: " + reqAmount + ", amountdata: " + amount_data);
        //$("#button_value").prop("disabled", true);
        $.ajax({
            url: '<?php echo $this->Url->build(["controller" => "Assets", "action" => "rquestWithdrawWalletAddress"]) ?>',
            type: 'post',
            data: {
                wallet_address: wallet_address,
                req_amount: req_amount,
                coinName: unitdata,
                otp_number: otp_number,
                value: value
            },
            complete:function(){
                $("#withdrawrequestdata").prop('disabled',true);
            },
            success: function(resp) {
                var resp = JSON.parse(resp);
                if (resp.success === "false") {
                    toastr.error(resp.message);
                    $("#button_value").prop("disabled", false);
                    return false;
                } else {
                    toastr.success('success');
                    var remAmount = resp.data.mylist.mainBalance;
                    var price = resp.data.mylist.currentPrice;
                    var finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                    var prices = parseFloat(remAmount).toFixed(2)*parseFloat(price).toFixed(2);
                    $("#req_amount").val('');
                    $("#wallet_address").val('');
                    $("#otp_number").val('');
                    $("#amountkrw").text('');
                    $("#withdrawalfee").text('');
                    $("#totalValuekrw").text('');
                    $("span#amount_data").text(""+finalAmount);
                    $("span#main_total_balance").text(""+finalAmount);
                    $("span#retained_quantity_"+getMyCoinId).text(""+finalAmount);
                    $("span#krw_quantity_"+getMyCoinId).text(""+numberWithCommas(prices));
                    var totalBal = totalBalance - krw;
                    var totBal = numberWithCommas(parseFloat(totalBal).toFixed(2));
                    //alert("totalBalance: "+ totalBalance + ", totalBal: " + totalBal + ", totBal: " + totBal);
                    $("#total_balance_val").html(""+ totBal);
                    //$("#button_value").prop("disabled", false);

                }
                //toastr.success('success');

            }
        });

    });




    $("#otp_number_krw").on('keypress', function (event) {
        var regex = new RegExp("^[0-9]$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#otp_number").on('keypress', function (event) {
        var regex = new RegExp("^[0-9]$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });


    $("#withdraw_btn").on('click',function() {

        var req_amount = $("#req_amount_krw").val();
        var amountkrw = document.getElementById("krw_amount_data").innerText;
        var total_amount = document.getElementById("totalAmountkrw").innerText;
        var otp_number = $("#otp_number_krw").val();
        var account = document.getElementById("bank_account").innerText;
        var bank = document.getElementById("bank_name").innerText;
        var totBlnc = document.getElementById("total_balance_val").innerText;
        var fees = 1000;
        var totBalance = totBlnc.replace(/\,/g,'');
        var totalBalance = parseFloat(totBalance);
        var reqAmount = parseFloat(req_amount);
        var amountTotal = amountkrw.replace(/\,/g,'');
        var totalamount = total_amount.replace(/\,/g,'');

        var availableAmount = parseFloat(amountTotal);
        var totalAmount = parseFloat(totalamount);
        var totalDeposit = "<?= $totalDeposit; ?>";
        var totalDepositAmount = parseFloat(totalDeposit);
        var totalOldDeposit = "<?= $totalOldDeposit; ?>";
        var totalOldDepositAmount = parseFloat(totalOldDeposit);
        // alert(reqAmount + ", " + amountTotal + ", " + availableAmount);
        // alert("reqAmount: " + typeof reqAmount + ", amounTotal: " + typeof amountTotal + ", availableAmount: " + typeof availableAmount + ", totalamount: " + typeof totalamount
        // + ", totalAmount: " + typeof totalAmount);

        //if(totalDepositAmount === undefined || totalDepositAmount === null && (totalDepositAmount < 50000 || totalOldDepositAmount < 50000)){
        //    toastr.error('<?//= __('Sorry! You cannot withdraw if you have not deposited at least 50,000 KRW') ?>//');
        //    return false;
        //}

        if (req_amount === undefined || req_amount === null || req_amount === '' ) {
            toastr.error('<?= __('Please enter amount you want to withdraw') ?>');
            return false;
        }

        if(mainAccount < 50000 ){
            toastr.error('<?= __('Insufficient Balance') ?>');
            return false;
        }

        if(reqAmount < 50000 ){
            toastr.error('<?= __('You need to withdraw at least 50,000 KRW') ?>');
            return false;
        }

        if(availableAmount < 50000){
            toastr.error('<?= __('Insufficient Balance') ?>');
            return false;
        }

        if (availableAmount > amountTotal || totalAmount > amountTotal) {
            toastr.error('<?= __('Please enter valid withdrawal amount') ?>');
            return false;
        }
        if (otp_number === undefined || otp_number === null || otp_number === '') {
            toastr.error('<?= __('Please enter OTP') ?>');
            return false;
        }

        if (account === undefined || account === null || account === '') {
            toastr.error('<?= __('Please verify your bank account') ?>');
            return false;
        }
        if (bank === undefined || bank === null || bank === '') {
            toastr.error('<?= __('Please verify your bank account') ?>');
            return false;
        }

        $.ajax({
            url: '<?php echo $this->Url->build(["controller" => "Assets", "action" => "bnkwithdraw"]) ?>',
            type: 'post',
            data: {
                total_amount: totalAmount,
                req_amount: reqAmount,
                fees: fees,
                otp_number: otp_number
            },
            success: function(resp) {
                var resp = JSON.parse(resp);
                if (resp.success == "false") {
                    toastr.error(resp.message);

                    return false;
                } else {
                    var remAmount = resp.data;
                    var finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                    toastr.success('success');
                    $("#req_amount_krw").val("");
                    $("#totalAmountkrw").html("");
                    $("#otp_number_krw").val("");
                    localStorage.setItem("value", "withdrawListState");
                    $("#krwwithdrawal_tab_content").hide();
                    $("#statement_tab_content").show();
                    $("#withdraw_li_div").show();
                    $("#deposit_li_div").hide();
                    $("#default_content").hide();
                    myWithdrawListAjax();
                    $("#depositListStatement_on").removeClass("on");
                    $("#withdrawalListStatement_on").addClass("deposit on");
                    $("#statement_on").addClass("deposit on");
                    $("#depositkrw_on").removeClass("on");
                    $("#krwwithdrawal_on").removeClass("on");
                    $("#deposit_on").removeClass("on");
                    $("#breakdown_on").removeClass("on");
                    $("#withdrawal_on").removeClass("on");
                    $("#withdrawal_addr_on").removeClass("on");
                    $("span#krw_amount_data").text(""+finalAmount);
                    $("span#amount_data").text(""+finalAmount);
                    $("span#main_total_balance").text(""+finalAmount);
                    $("span#retained_quantity_20").text(""+finalAmount);
                    $("span#krw_quantity_20").text(""+finalAmount);
                    var totalBal = totalBalance - totalAmount;
                    var totBal = numberWithCommas(parseFloat(totalBal).toFixed(2));
                    //alert("totalBalance: "+ totalBalance + ", totalBal: " + totalBal + ", totBal: " + totBal);
                    $("#total_balance_val").html(""+ totBal);
                    $("#amount_deposited").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    $("#bank_deposit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                    //$("#total_balance_val").html(finalAmount);
                }
                //toastr.success('success');

            }
        });

    });
});



function amountSubmitted() {
    var amount = $("#amount_deposited").val();

    if (amount === undefined || amount === null || amount === '' || amount < 50000) {
        toastr.error('<?= __('Please deposit minimum 50,000 KRW') ?>');
        return false;
    }

    // $("#bank_deposit_btn").html("Deposited");
    $.ajax({
        url: '<?php echo $this->Url->build(["controller" => "Assets", "action" => "bnkdeposit"]) ?>',
        type: 'post',
        data: {
            amount_deposited: amount
        },
        success: function(resp) {
            var resp = JSON.parse(resp);
            if (resp.success == "false") {
                toastr.error(resp.message);
                //$("#bank_deposit_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                return false;
            } else {
                toastr.success('success');
                $("#amount_deposited").val("");
                localStorage.setItem("value", "depositListState");
                $("#default_content").hide();
                $("#statement_tab_content").show();
                myDepositListAjax();
                $("#statement_on").addClass("deposit on");
                $("#withdrawalListStatement_on").removeClass("on");
                $("#depositListStatement_on").addClass("deposit on");
                $("#depositkrw_on").removeClass("on");
                $("#krwwithdrawal_on").removeClass("on");
                $("#deposit_on").removeClass("on");
                $("#breakdown_on").removeClass("on");
                $("#withdrawal_on").removeClass("on");
                $("#withdrawal_addr_on").removeClass("on");
                $("#withdraw_li_div").hide();
                $("#deposit_li_div").show();
                $("#amount_deposited").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                $("#bank_deposit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                $("#req_amount_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                $("#otp_number_krw").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                $("#withdraw_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                // $("#bank_deposit_btn").prop("disabled",true).css({'pointer-events': 'none'}).attr('disabled','disabled');

            }
            //toastr.success('success');

        }
    });
}




function transferAmount(coinName,transferTo){
	
	$("#modal_coin_name").html(coinName);
	$("#wallet_name").html(transferTo);
	$("#coin_id").val(coinName);
	$("#transfer_to").val(transferTo);
	$('#myModalDeposit').modal('show'); 
}

			$("#deposit_modal_form").submit(function(event){
				
			    //stop submit the form, we will post it manually.
			event.preventDefault();
			$("#btnSubmit").prop("disabled", true);
			$("#model_qr_code_flat").show();
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $this->Url->build(['controller'=>'Wallet','action'=>'transgetToAccountNew']); ?>",
				data: $("#deposit_modal_form").serialize(),
				dataType: 'JSON',
				success: function (resp) {
					$("#model_qr_code_flat").hide();
					if(resp.status=='true'){
						getInternalTransaction();
						mainAndTradingBalanceTotal();
						currentCoinBalance();
						/* var transfer_to=jQuery('input[name="transfer_to"]').val();
						var amountData=jQuery('input[name="amount"]').val();
						
						if(transfer_to=="main"){
							var data1=$("#amount_datatable_withdrowl2").text();
							data1=parseFloat(data1)+parseFloat(amountData);
							$("#amount_datatable_withdrowl2").html(data1);
							var data2=$("#tradingBalancetable_withdrowl2").text();
							data2=data2.replace(',', '');
							data2=parseFloat(data2)-parseFloat(amountData);
							$("#tradingBalancetable_withdrowl2").html(data2);
							$("#get_resp").html(resp.message).addClass('alert alert-success').show();
							setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-success').hide(); },2500)
						}else{
							var data1=$("#amount_datatable_withdrowl2").text();
							data1=parseFloat(data1)-parseFloat(amountData);
							$("#amount_datatable_withdrowl2").html(data1);
							var data2=$("#tradingBalancetable_withdrowl2").text();
							data2=data2.replace(',', '');
							data2=parseFloat(data2)+parseFloat(amountData);
							$("#tradingBalancetable_withdrowl2").html(data2);
							$("#get_resp").html(resp.message).addClass('alert alert-success').show();
						
						} */
						$("#get_resp").html(resp.message).addClass('alert alert-success').show();
						setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-success').hide(); },2500)
					}
					else if(resp.status=='false'){
						$("#get_resp").html(resp.message).addClass('alert alert-danger').show();
						setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-danger').hide(); },2500)
					}
					$("#deposit_modal_form")[0].reset();
					$("#btnSubmit").prop("disabled", false);
					//getCoinList();
				},
				error: function (e) {
				
					$("#model_qr_code_flat").hide();
					$("#btnSubmit").prop("disabled", false);

				}
			});
		});
		
		
		$('#search_coin').keyup(function(){
		var coin_name=$("#search_coin").val();
			$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"getusercoinlistajax"]) ?>',
		type:'post',
		data:{coin_name:coin_name},
		dataType:'JSON',
		success:function(resp){
			var getHtml='';
			$("#mycoinlist").html('mycoinlist')
			$.each(resp,function(key,value){
				
				console.log();
				var str=parseFloat(value.krw_value).toLocaleString('en');
				var icon="";
				
				if(value.icon!=undefined && value.icon!=null && value.icon!='' ){
					var icon='<img src="/uploads/cryptoicon/'+value.icon+'" width="40px" max-height="40px"></td>';
				}
				

				var principalBalance = value.principalBalance;
				var getMyCustomPrice= principalBalance * str
				var firstLetter = value.short_name.charAt(0);
				var name1=value.coinName;
				name1=name1.replace(/\s/g, "");
				var callFuntion = "display_data('"+value.coin_id+"','"+value.short_name+"','"+principalBalance+"','"+str+"','"+value.short_name+"','"+parseFloat(getMyCustomPrice).toLocaleString('en')+"','"+value.reserveBalance+"','"+value.coinAddress+"','"+name1+"')";
				if( principalBalance!=0){
                        getHtml = getHtml+'<tr class="setvalue" onclick='+callFuntion+' data-id=coin_name_'+key+'  style="cursor:pointer;">';
						getHtml = getHtml+'	<td><span  style="cursor:pointer">'+value.short_name+" "+icon+'</span>';
                    }else{
                        getHtml = getHtml+'<tr class="setvalue hide_currency" onclick='+callFuntion+' data-id=coin_name_'+key+'  style="cursor:pointer;">';getHtml = getHtml+'	<td><span  style="cursor:pointer">'+value.short_name+" "+icon+'</span>'; 
                    }				
				getHtml = getHtml+'	<td><span>'+principalBalance+'</span></td>';
				getHtml = getHtml+'	<td><span>'+parseFloat(getMyCustomPrice).toLocaleString('en')+'</span></td>';
				getHtml = getHtml+'</tr>'

				
			});
			$("#mycoinlist").html(getHtml);
		}
	});
		})

</script>
<script>
$( "#radioclick" ).click(function() {
	var isChecked = $('#radioclick').is(':checked');
	if(isChecked==true){
		$(".hide_currency").hide()
	}else{
		$(".hide_currency").show()
	}
	// console.log(isChecked);


});
function increment(value) {
	 
	 var valuecheck = localStorage.getItem("unit_data");
		 
		 var min=0;
		 var step=1;
									 if(valuecheck=="TP3"){
										 min='0.1';
										 step="0.1";
										 
									 }
									 if(valuecheck=="BTC"){
										 min='0.0';
										 step="5000";
										 
									 }
									 if(valuecheck=="CTC"){
										 min='0.0';
										 step="1";
										 
									 }
									 if(valuecheck=="ETH"){
										 min='0.0';
										 step="500";
										 
									 }
									 if(valuecheck=="USDT"){
										 min='0.0';
										 step="0.01";
										 
									 }
									 if(valuecheck=="XRP"){
										 min='0.0';
										 step="0.1";
										 
									 }
		 var value1=$("#req_amount").val();
		 if(value1!=undefined && value1!=null && value1!=''){
			 var new_value=parseFloat(value1)+parseFloat(step);
			 
				 if(valuecheck=="USDT"){
					 $("#req_amount").val(parseFloat(new_value).toFixed(2)).trigger('input');
				 }else{
					 $("#req_amount").val(parseFloat(new_value).toFixed(1)).trigger('input');
				 }
				
			 
		 }else{
			 var new_value=value;
			 
			 if(valuecheck=="USDT"){
					 $("#req_amount").val(parseFloat(new_value).toFixed(2)).trigger('input');
				 }else{
					 $("#req_amount").val(parseFloat(new_value).toFixed(1)).trigger('input');
				 }
		 }
		 
	 var req_amount=$("#req_amount").val();
	 var krwamount=$('#amount_data').text();
	 var unit_data=$('#unit_data').text();
	 var krw_value=$('#coin_id_'+unit_data).val();
	 
	 
	 
	 
	  var new_value=parseFloat(req_amount)*parseFloat(krw_value);
	  var fee_charges=req_amount*<?php echo $adminWithdrawalFeePercent; ?>/100;
	  
	  $("#amountkrw").html(new_value);
	  $("#withdrawalfee").html(fee_charges);
	  reamainingBalance = req_amount - fee_charges;
	  $("#totalValuekrw").html(reamainingBalance);
 
	 }
	 function decrement(value) {
		 var valuecheck = localStorage.getItem("unit_data");
		 var value1=$("#req_amount").val();

		 var min=0;
		 var step=1;
									 if(valuecheck=="TP3"){
										 min='0.1';
										 step="0.1";
										 
									 }
									 if(valuecheck=="BTC"){
										 min='0.0';
										 step="5000";
										 
									 }
									 if(valuecheck=="CTC"){
										 min='0.0';
										 step="1";
										 
									 }
									 if(valuecheck=="ETH"){
										 min='0.0';
										 step="500";
										 
									 }
									 if(valuecheck=="USDT"){
										 min='0.0';
										 step="0.01";
										 
									 }
									 if(valuecheck=="XRP"){
										 min='0.0';
										 step="0.1";
										 
									 }

		 if(value1!=undefined && value1!=null && value1!=''){
			 if(value1>0){
				 var new_value=parseFloat(value1)-parseFloat(step);
				 if(Math.sign(new_value)==-1){
					 new_value=0;
				 }
				 
				 if(valuecheck=="USDT"){
					 $("#req_amount").val(parseFloat(new_value).toFixed(2)).trigger('input');
				 }else{
					 $("#req_amount").val(parseFloat(new_value).toFixed(1)).trigger('input');
				 }
			 }
			 
		 }else{
			 var new_value=value
			 
			 if(valuecheck=="USDT"){
					 $("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				 }else{
					 $("#buy_per_price").val(parseFloat(new_value).toFixed(1)).trigger('input');
				 }
		 }
 
		 var req_amount=$("#req_amount").val();
	 var krwamount=$('#amount_data').text();
	 var unit_data=$('#unit_data').text();
	 var krw_value=$('#coin_id_'+unit_data).val();
	 
	 
	 
	 
	  var new_value=parseFloat(req_amount)*parseFloat(krw_value);
	  var fee_charges=req_amount*<?php echo $adminWithdrawalFeePercent; ?>/100;
	  
	  $("#amountkrw").html(new_value);
	  $("#withdrawalfee").html(fee_charges);
	  reamainingBalance = req_amount - fee_charges;
	  $("#totalValuekrw").html(reamainingBalance);
	 }

function ucfirst(str){
    if (str != null){
        var str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
    }
    else {
        var str='';
    }
    return str;
}
	
 function validateInputAddresses(address) {
		 return (/^(0x){1}[0-9a-fA-F]{40}$/i.test(address));
 }

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function myDepositListAjax(){

    // ajax for myOrder list
    $.ajax({
        url : '<?php echo $this->Url->Build(['controller'=>'assets','action'=>'myDepositListAjax']); ?>',
        type : 'get',
        dataType : 'json',
        success : function(resp) {

            // my buyOrderList data
            var html = '';
            if ($.isEmptyObject(resp.myDepositList)) {
                html = html + '<tr>';
                html = html + "<td colspan=5><?= __('There is no transaction history.')?></td>";
                html = html + '</tr>';
            } else {
                $.each(resp.myDepositList, function (key, value) {

                    var showAmount = numberWithCommas(parseFloat(value.amount).toFixed(2));
                    var splitDateTime = value.created_at;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T", " ");
                    var status = ucfirst(value.status);

                    html = html + '<tr>';
                    html = html + '<td>' + '<?= __('Deposit');?>' + '</td>';
                    html = html + '<td>' + showAmount + '</td>';
                    html = html + '<td>' + getdateTime + '</td>';
                    if (status === 'Completed') {
                        html = html + '<td style="color:blue;">' + '<?= __('Completed')?>' + '</td>';
                    } else if (status === 'Pending') {
                        html = html + '<td style="color:orange;">' + '<?= __('Pending')?>' + '</td>';
                    } else if (status === 'Deleted') {
                        //html = html + '<td style="color:red;">' + '' + '</td>';
                    } else {
                        html = html + '<td>&nbsp;</td>'
                    }
                    html = html + '</tr>';
                });

            }

            //$.fn.dataTable.ext.errMode = 'none';
            $("#depositTable").dataTable().fnDestroy();
            $("#myDepositlist").html(html);
            $('#depositTable').DataTable({
                bSort: false,
                pageLength: 15,
                scrollY: "300px",
                scrollX: false,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },
                language: {
                    "url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
                }
            });
        }
    });

}

function myWithdrawListAjax(){
    $.ajax({
        url: '<?php echo $this->Url->Build(['controller' => 'assets', 'action' => 'myWithdrawListAjax']); ?>',
        type: 'get',
        dataType: 'json',
        success: function (resp) {
            var html = '';
            if ($.isEmptyObject(resp.myWithdrawList)) {
                html = html + '<tr>';
                html = html + "<td colspan=5><?= __('Transaction history is not available')?></td>";
                html = html + '</tr>';
            } else {
                $.each(resp.myWithdrawList, function (key, value) {
                    var showAmount = 0.0;
                    if(value.coin_amount !== null && value.coin_amount !== undefined && value.coin_amount !== '')
                    {
                        showAmount = numberWithCommas(parseFloat(value.coin_amount).toFixed(2));
                    } else {
                        showAmount = 0.0;
                    }
                    var splitDateTime = value.created_at;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T", " ");
                    var status = ucfirst(value.status);
                    html = html + '<tr>';
                    html = html + '<td>' + '<?= __('Withdrawal')?>' + '</td>';
                    html = html + '<td>' + showAmount + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(value.fees).toFixed(2)) + '</td>';
                    html = html + '<td>' + getdateTime + '</td>';
                    if (status === 'Completed') {
                        html = html + '<td style="color:blue;">' + '<?= __('Completed')?>' + '</td>';
                    } else if (status === 'Pending') {
                        html = html + '<td style="color:orange;">' + '<?= __('Pending')?>' + '</td>';
                    } else if (status === 'Deleted') {
                        // html = html + '<td style="color:red;">' + '' + '</td>';
                    } else {
                        html = html + '<td>&nbsp;</td>'
                    }

                    html = html + '</tr>';
                });
            }
            var checkDisplay = $("#withdraw_li_div").css("display");
            if (checkDisplay != "none") {

                $("#withdrawTable").dataTable().fnDestroy();
                $("#myWithdrawlist").html(html);
                $('#withdrawTable').DataTable({
                    bSort: false,
                    pageLength: 15,
                    scrollY: "300px",
                    scrollX: false,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    },
                    language: {
                        "url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
                    }
                });
            } else {
                $("#myWithdrawlist").html(html);
            }
        }
    });
}


</script>