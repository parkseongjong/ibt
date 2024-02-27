<link rel="stylesheet" href="<?= $this->request->webroot ?>assets/html/css/assets.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $this->request->webroot ?>js/front2/assets/mycoin2.js?v=<?php echo time(); ?>"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
    .header2{
        display: none !important;
    }
</style>

<div style="width: 100%; height: auto;background: white;">
    <div>
        <img src="https://cybertronchain.com/wallet2/images/icons/menu.png" style="width: 10%;margin-top: 3%" onclick="menu_action()">
    </div>
    <hr>
    <div id="setmenu" style="display: none">
        <ul>
            <li style="margin: 6px 0px ;">
                <a href="/front2/pages/mywallet2">내지갑</a>
            </li>
            <li style="margin: 6px 0px ;">
                <a href="/front2/assets/mycoins2">출금</a>
            </li>
            <!--
            /front2/investment/application2
            -->
            <li style="margin: 6px 0px " >
                <a href="/front2/investment/history2" >스테이킹 출금</a>
            </li>
            <li style="margin: 6px 0px " >
                <a href="/front2/investment/applicationdev" >스테이킹 신청</a>
            </li>
<!--            <li style="margin:  6px 0px ;  ">
                <a href="/front2/document/priceinfo2">쿠폰</a>
            </li>-->
            <li style="margin:  6px 0px ; ">
                <a href="https://cybertronchain.com/wallet2/">CTC Wallet 가기</a>
            </li>
        </ul>
    </div>
</div>
<script>
    function waitcheck(){
        alert("준비 중 입니다.");
        return;
    }
    function menu_action(){
        $("#setmenu").toggle();
    }
</script>
<div class="containers containers3" >
    <?php
    if(!empty($_COOKIE["mycoins_type"]) && $_COOKIE["mycoins_type"] == 'profile'){
        echo $this->element('Front2/profile_menu');
    }
    ?>
    <div class="assets_box" style="width:auto;">
        <div class="left mycoinleft">
            <div class="my_assets">
                <ul class="total_assets">
                    <li class="title"><?=__('Total assets held (KRW)') ?></li>
                    <li class="pricetotal12233 price" id="total_balance_val">0</li>
                </ul>
                <input type="text" id="search_coin" name="search_coin" class="search_coin" placeholder="<?=__('Coin search') ?>" />
                <div class="options">
                    <label>
                        <input type="checkbox" name="" id="radioclick" value="" /><?=__('View only retained coins') ?>
                    </label>
                </div>
            </div>
            <div class="my_coins">
                <table id="myCoinsLists">
                    <thead>
                    <tr>
                        <td><b><span><?=__('Coin Name')?></span></b></td>
                        <td style="width:21%"><b><span><?=__('Retained quantity')?></span></b></td>
                        <td style="width:24%"><b><span>USD</span></b></td>
                    </tr>
                    </thead>
                    <tbody id="mycoinlist">
                    <?php $total_value="";
                    $principalBalanceTotal="";
                    $reserveBalance ="";
                    $tradingBalance = "";
                    if (!empty($mainRespArr)) {
                        foreach($mainRespArr as $key=> $value){
                            if(!empty($value['tradingBalance'])){ ?>
                                <tr class="setvalue"  data-id="coin_name_<?= $key;?>" style="cursor:pointer" data-coin-id="<?= $value['coinId']; ?>"
                                    data-coin-title="<?= $value['coinName']."(".$value['coinShortName'].")"; ?>">
                                    <td>
                                    <span>
                                        <?php if (!empty($value['icon'])) { ?>
                                            <img src="/uploads/cryptoicon/<?= $value['icon'];?> " width="40px" max-height="40px">
                                        <?php }
                                        echo $value['coinShortName']; ?>
                                    </span>
                                    </td>
                                    <td>
                                        <span id="retained_quantity_<?= $value['coinId']; ?>"><?= number_format((float)$value['tradingBalance'], 2); ?></span>
                                        <!--                                    <span id="retained_quantity_--><?php //echo $value['coinId']; ?><!--">--><?//= number_format((float)$value['principalBalance'],2); ?><!--</span>-->
                                    </td>
                                    <td>
                                    <span  id="krw_quantity_<?= $value['coinId']; ?>">
                                         <?php $customPriceTrading = number_format((float)$value['customPriceTrading'],2);
                                         $total_value += (float)$value['tradingBalance'] * (float)$customPriceTrading;
                                         $principalBalanceTotal += (float)$value['tradingBalance'];
                                         $reserveBalance += (float)$value['reserveBalance'];
                                         $tradingBalance += (float)$value['tradingBalance'];
                                         echo $customPriceTrading; ?>
                                    </span>
                                    </td>
                                    <input type="hidden" value=<?= $value['principalBalance'];?> id="quantity_<?= $key ;?>" name="quantity_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $customPriceTrading;?> id="krw_<?= $key ;?>" name="krw_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['coinShortName'];?> id="coin_name_<?= $key ;?>" name="coin_name_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['krwValue'];?> id="coin_id_<?=  $value['coinShortName'] ;?>" name="coin_id_<?=  $value['coinShortName'] ;?>"/>
                                    <input type="hidden" value=<?= $value['tradingBalance'];?> id="tradingBalance_<?=  $key ;?>" name="tradingBalance_<?=  $key ;?>"/>
                                    <input type="hidden" value=<?= $value['coinAddress'];?> id="coinAddress_<?= $key ;?>" name="coinAddress_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['reserveBalance'];?> id="reserveBalance_<?= $key ;?>" name="reserveBalance_<?= $key ;?>"/>
                                </tr>
                            <?php } else { ?>
                                <tr class="setvalue hide_currency" data-id="coin_name_<?= $key;?>" style="cursor:pointer" data-coin-id="<?= $value['coinId']; ?>"
                                    data-coin-title="<?= $value['coinName']."(".$value['coinShortName'].")"; ?>">
                                    <td>
                                    <span>
                                        <?php if(!empty($value['icon'])){ ?><img src="/uploads/cryptoicon/<?= $value['icon'];?> " width="40px" max-height="40px">
                                        <?php }
                                        echo $value['coinShortName']; ?>
                                    </span>
                                    </td>
                                    <td>
                                        <span><?= number_format((float)$value['tradingBalance'],2); ?></span>
                                        <!-- <span>--><?php //echo number_format((float)$value['principalBalance'], 2); ?><!--</span>-->
                                    </td>
                                    <td>
                                    <span><?php $customPriceTrading = number_format((float)$value['customPriceTrading'],2);
                                        $principalBalanceTotal += (float)$value['tradingBalance'];
                                        $reserveBalance += (float)$value['reserveBalance'];
                                        $tradingBalance += (float)$value['tradingBalance'];
                                        echo $customPriceTrading; ?>
                                    </span>
                                    </td>
                                    <input type="hidden" value=<?= $value['principalBalance'];?> id="quantity_<?= $key ;?>" name="quantity_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $customPriceTrading;?> id="krw_<?= $key ;?>" name="krw_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['coinShortName'];?> id="coin_name_<?= $key ;?>" name="coin_name_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['krwValue'];?> id="coin_id_<?=  $value['coinShortName'] ;?>" name="coin_id_<?=  $value['coinShortName'] ;?>"/>
                                    <input type="hidden" value=<?= $value['tradingBalance'];?> id="tradingBalance_<?=  $key ;?>" name="tradingBalance_<?=  $key ;?>"/>
                                    <input type="hidden" value=<?= $value['coinAddress'];?> id="coinAddress_<?= $key ;?>" name="coinAddress_<?= $key ;?>"/>
                                    <input type="hidden" value=<?= $value['reserveBalance'];?> id="reserveBalance_<?= $key ;?>" name="reserveBalance_<?= $key ;?>"/>
                                </tr>
                            <?php       }
                        }
                    } ?>
                    <input type="hidden" class="totalkrw" value="<?= $total_value;?>"/>
                    <input type="hidden" class="principalBalanceTotal" value="<?= $principalBalanceTotal;?>"/>
                    <input type="hidden" class="reserveBalance" value="<?= $reserveBalance;?>"/>
                    <input type="hidden" class="tradingBalance" value="<?= $tradingBalance;?>"/>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mycoinrigth">
            <input type="hidden" id="pendingVal" value="<?= $pendingVal; ?>"/>
            <input type="hidden" id="pendingValw" value="<?= $pendingValw; ?>"/>
            <input type="hidden" id="bankAuth" value="<?= $users['bank_verify']; ?>"/>
            <input type="hidden" id="emailAuth" value="<?= $users['email_auth']; ?>"/>
            <input type="hidden" id="otpAuth" value="<?= $users['g_verify']; ?>"/>
            <input type="hidden" id="totalBuy" value="<?= $totalBuy; ?>"/>
            <input type="hidden" id="totalSell" value="<?= $totalSell; ?>"/>
            <input type="hidden" id="totalDeposit" value="<?= $totalDeposit; ?>"/>
            <input type="hidden" id="totalReward" value="<?= $totalReward; ?>"/>
            <input type="hidden" id="deposit" value="<?= $users['deposit']; ?>"/>
            <input type="hidden" id="main" value="<?= $main; ?>"/>
            <input type="hidden" id="halfBal" value="<?= $halfBal; ?>"/>
            <input type="hidden" id="trading" value="<?= $trading; ?>"/>
            <input type="hidden" id="inoutprice" value="<?= $inoutprice; ?>"/>
            <input type="hidden" id="halfBalTrading" value="<?= $halfBalTrading; ?>"/>
            <input type="hidden" id="totalOldDeposit" value="<?= $totalOldDeposit; ?>"/>
            <input type="hidden" id="btcAddr" value="<?= $users["btc_address"]; ?>"/>
            <input type="hidden" id="ethAddr" value="<?= $users["eth_address"]; ?>"/>
            <!-- 하루거래량 -->
            <input type="hidden" id="day_coin" value="<?= abs($day_total)?>">
            <!--<input type="hidden" id="day_coin" value="10000">-->
            <!-- 한달거래량 -->
            <input type="hidden" id="month_coin" value="<?= abs($month_total)?>">
            <!--<input type="hidden" id="month_coin" value="980000">-->
            <div class="mycoinrigth_pp">
                <div class="rbtc_box">
                    <span style="margin-right:15px;display: inline-block" id="coin_name_id_data"></span>
                    <div id="div_main">
                        <?= __('Main Balance: ') ?><span style="font-weight:bold" class="main_Balance1122" id="main_total_balance" hidden>0</span>
                    </div>
                    <div id="div_trading" >
                        <span class="reblock"><?= __('Trading Balance: ') ?><span style="font-weight:bold" class="TradingAccount" id="trading_total_balance">0</span></span>
                    </div>
                    <div id="div_reserved" style="display: inline-block">
                        <span class="reblock"><?= __('Reserved Balance: ') ?><span style="font-weight:bold" class="TradingAccounts" id="resserve_total_balance">0</span></span>
                    </div>
                </div>
                <div class="cls">
                </div>
                <div id="btns_transfer" class="transfer_btn" hidden>
                    <button type="button" class="btn btn-outline-primary white" id="btn_trading_withdraw" name="btn_trading_withdraw"><?= __('Withdraw from Trading Account');?></button>
                    <button type="button" class="btn btn-outline-primary white" id="btn_main_withdraw" name="btn_main_withdraw"><?= __('Withdraw from Main Account');?></button>
                </div>
                <ul class="tab_menu_gen">
                    <li class="tab_menu_item" id="depositkrw_on" onClick="tabClick('depositkrw')">
                        <a href="javascript:void(0);"><?= __('Deposit in Korean Won') ?></a>
                    </li>
                    <li class="tab_menu_item" id="krwwithdrawal_on" onClick="tabClick('krwwithdrawal')">
                        <a href="javascript:void(0);"><?= __('KRW Withdrawal') ?></a>
                    </li>
                    <li class="tab_menu_item" id="statement_on" onClick="tabClick('statement')">
                        <a href="javascript:void(0);"><?= __('Statement') ?></a>
                    </li>
                </ul>
                <ul class="tab_menu" id="tab_menu_coins">
                    <li class="tab_menu_item" id="deposit_on" onClick="tabClick('deposit')">
                        <a href="javascript:void(0);"><?= __('Deposit') ?></a>
                    </li>
                    <li class="tab_menu_item" id="withdrawal_on" onClick="tabClick('withdrawal')">
                        <a href="javascript:void(0);"><?= __('Withdrawal') ?></a>
                    </li>
                    <li class="tab_menu_item" id="breakdown_on" onClick="tabClick('breakdown')">
                        <a href="javascript:void(0);"><?= __('Breakdown') ?></a>
                    </li>
                    <li class="tab_menu_item" id="withdrawal_addr_on" onClick="tabClick('withdrawal_addr')">
                        <a href="javascript:void(0);"><?= __('Withdrawal Address Management') ?></a>
                    </li>
                </ul>
                <div id="mesg">
                    <!-- <span><h3>출금하실 코인을 선택하시면 입, 출금 메뉴가 보입니다.</h3></span> -->
                    <span><h3>When you select the coin you want to withdraw, you will see the deposit and withdrawal menus.</h3></span>
                </div>
                <div style="text-align: center;" class="common_tab" id="default_content">
                    <div class="krw-info-area">
                        <div class="krw-info-top">
                            <div class="krw-account-info-area">
                                <div class="title-area">
                                    <h2>Deposit and withdrawal account information</h2>
                                    <span>(When depositing and withdrawing money, please make a deposit from the bank account below. Refunds will be made when depositing to another account.)</span>
                                </div>
                                <div class="krw-info-grid">
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            Account Number
                                        </div>
                                        <div class="grid-col grid-col-10">
                                            <span><?= $this->Decrypt($users['account_number']); ?></span>
                                        </div>
                                    </div>
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            name of bank
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            <span><?= __($users['bank']); ?></span>
                                        </div>
                                        <div class="grid-col grid-col-2 grid-title">
                                            Account Holder
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            <?= ucfirst($users['name']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="krw-account-memo-area">
                                <div class="memo-desc">
                                    <div>incoming passbook notes</div>
                                    <div class="text-color-blue">(Mark the recipient)</div>
                                </div>
                                <div class="memo-username">
                                    <?php $masked_phone_number = substr($users['phone_number'], -4);
                                    echo ucfirst($users['name']) . $masked_phone_number; ?>
                                </div>
                                <div class="memo-notice">
                                    *Please make a deposit using the issued name of the depositor (member name + number code). [ex: Jack 1234]
                                </div>
                            </div>

                            <div class="krw-push-info-area">
                                <div class="title-area">
                                    <h2>Korean Won remittance account information</h2>
                                </div>
                                <div class="krw-info-grid">
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            Account Number
                                        </div>
                                        <div class="grid-col grid-col-10">
                                            140-013-414016
                                        </div>
                                    </div>
                                    <div class="grid-row">
                                        <div class="grid-col grid-col-2 grid-title">
                                            name of bank
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            Shinhan Bank
                                        </div>
                                        <div class="grid-col grid-col-2 grid-title">
                                            Account Holder
                                        </div>
                                        <div class="grid-col grid-col-4">
                                            Jack
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ifpendingD"><span style="color: red;">Order is being processed</span></div>
                            <div id="notauth">
                                <span style="color: red;"><a id="elink" href="/front2/users/id-verification"><?= __('Go to auth') ?></a></span>
                            </div>
                            <div class="krw-input-area">
                                <div class="input-group">
                                    <input type="number" id="amount_deposited" name="amount_deposited" placeholder="Enter the deposit request amount">
                                    <div class="input-postfix">KRW</div>
                                </div>
                                <button class="krw-input-btn" id="bank_deposit_btn" onclick="amountSubmitted();">general deposit</button>
                                <!-- 									<button class="krw-input-btn" id="bank_deposit_btn" onclick="confirm_alert1();">일반입금</button> -->
                            </div>
                        </div>
                        <div class="krw-info-bottom">
                            <div class="krw-guide">
                                <div class="guide-title">Please check the notes before making a deposit!</div>
                                <div>- If the deposit conditions are satisfied, deposit confirmation processing is possible only from 10:00 am to 5:00 pm on weekdays.</div>
                                <div>- The minimum deposit amount is 50,000 won or more.</div>
                                <div>- Please note that transaction deposit and annual fee deposit are not the same.</div>
                                <div>- When depositing, if you deposit abnormally as in the case below, transaction delay will occur for more than 15 days.</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;Ex) If the requested amount and the actual deposit amount are not deposited to the deposit account after the charge request is made</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    1) Unable to confirm when depositing 990,000 won into a bank account after applying for 1 million won</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    2) After applying for 1 million won, it is impossible to confirm the deposit by dividing 500,000 won + 500,000 won</div><br/>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;Ex) If you do not enter ‘member name or number code’ in ‘Show recipient’s bankbook’ when depositing</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    1) Hong Gil-dong 1234 No abnormality</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    2) If you enter only the name of Gil-dong Hong and there is no numeric code</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    3) If you enter Hong Gil-dong 123 and the numeric code is not entered normally</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    4) If the phone number is 1234, but is entered as Hong Gil-dong 1233 as another number</div><br/>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;Example) When depositing money from someone else's account or unregistered own account</div>
                                <div>- If you do not make a deposit within 24 hours of requesting a deposit, your approval will be automatically rejected.</div><br/>
                                <div>
                                    <strong>KRW remittance account information</strong>
                                    <br />Account number 140-013-414016
                                    <br />Bank Name Shinhan Bank
                                    <br />Account Holder SMBIT., Ltd.
                                </div>
                            </div>
                            <div class="krw-guide">
                                <div class="guide-title">Please check the notes before making a withdrawal!</div>
                                <div>- After the withdrawal request, the withdrawal will be made within 24 hours on weekdays (business days).</div>
                                <div>- If the deposit/withdrawal conditions are satisfied, you can only withdraw from 10 am to 5 pm on weekdays.</div>
                                <div>- You must have a deposit transaction record of 20,000 won or more.</div>
                                <div>- The minimum withdrawal amount is KRW 50,000 or more.</div>
                                <div>- When a withdrawal request is made, the withdrawal will be made to the registered bank account, and withdrawals from unregistered accounts are not allowed.</div>
                                <div>- Withdrawal is possible only during business hours (business hours 10:00 am - 17:00 pm).</div>
                                <div>- Withdrawals may be restricted if fraudulent transactions are suspected.</div>
                                <div>- The withdrawal fee is KRW 1,000 and can be adjusted.</div>
                            </div>
                        </div>
                        <!-- <div class="krw-info-bottom">
                            <div class="krw-guide">
                                <div class="guide-title">입금을 하기전에 유의사항을 확인해주세요!</div>
                                <div>- 입금 조건이 만족하면 평일 오전 10시부터 오후 5시까지만 입금확인 처리가 가능합니다.</div>
                                <div>- 최소 입금금액은 50,000원 이상입니다.</div>
                                <div>- 거래입금과 연회비 입금은 같지 않으니 참고하여 주시기 바랍니다.</div>
                                <div>- 입금 시 아래 경우와 같이 비정상적으로 입금할 경우 거래 지연이 15일 이상 발생합니다.</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;예) 충전요청 후에 입금계좌로 요청금액과 실제 입금금액을 같게 입금하지 않을 경우</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    1) 100만원 신청 후 99만원 통장 입금시 확인 불가</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    2) 100만원 신청 후 50만원+50만원 나누어서 입금 확인 불가</div><br/>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;예) 입금 시 ‘받는 분 통장표시’란에 ‘회원 이름 또는 숫자 코드’를 입력 안 하는 경우</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    1) 홍길동1234 이상없음</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    2) 홍길동 이름만 입력하고 숫자 코드 없는 경우</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    3) 홍길동123 입력하고 숫자 코드가 정상 입력이 안된 경우</div>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    4) 전화번호 1234 인데 홍길동1233 으로 다른 번호로 입력된 경우</div><br/>
                                <div>  &nbsp;&nbsp;&nbsp;&nbsp;예) 타인 명의 계좌, 미등록 본인 계좌에서 입금할 경우</div>
                                <div>- 입금 충전요청 후 24시간 이내 입금하지 않으면 자동 승인거절이 됩니다.</div><br/>
                                <div>
                                    <strong>원화 송금 계좌정보</strong>
                                    <br />계좌번호 140-013-414016
                                    <br />은행명 신한은행
                                    <br />예금주 주식회사 한스바이오텍
                                </div>
                            </div>
                            <div class="krw-guide">
                                <div class="guide-title">출금을 하기전에 유의사항을 확인해주세요!</div>
                                <div>- 출금 신청 후 평일(영업일) 기준 24시간 후 출금됩니다.</div>
                                <div>- 입금/출금 조건이 만족하면 평일 오전 10시부터 오후 5시까지만 출금이 가능합니다.</div>
                                <div>- 20만원 이상 입금 거래내역이 있어야 합니다.</div>
                                <div>- 최소 출금금액은 50,000원 이상입니다.</div>
                                <div>- 출금 요청 시 등록한 은행 계좌로 출금이 되며, 미등록 계좌는 출금이 불가합니다.</div>
                                <div>- 업무시간 내에만 출금 가능합니다.(업무시간 오전 10:00- 오후 17:00).</div>
                                <div>- 부정 거래가 의심되는 경우 출금이 제한될 수 있습니다.</div>
                                <div>- 출금 수수료는 1,000원이며, 조정될 수 있습니다.</div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div style="display:none;" class="common_tab qr_code_man" id="deposit_tab_content">
                    <span id="qr_code_image"></span>
                    <ul class="copy_address">
                        <li style="float:left; width:100%; color:#000;">
                            <input type="text" name="" value="" readonly id="wallet_addr_input" class="text"/>
                            <div class="address_li" onClick="copyToClipboard();">
                                <?= __('Copy Address') ?>
                            </div>
                        </li>
                    </ul>
                    <div id="copy_msg" class="alert alert-success" style="display:none;"></div>
                    <div class="cls">
                        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                    </div>
                </div>

                <div style="display:none;" class="common_tab" id="withdrawal_tab_content">
                    <div class="exting">
                        <p>  <strong>Testnet Wallet Address</strong> : <?php echo $userTestWalletAddr; ?></p>
                        <!--<b><?/*= __('Total Buy Amount: '); */?></b> <span id="totalBuyAmount"><?/*= number_format($totalBuy, 2); */?> KRW</span>,
                            <b><?/*= __('Total Sell Amount: '); */?></b> <span id="totalSellAmount"> <?/*= number_format($totalSell, 2); */?> KRW</span>,
                            <b><?/*= __('Total Deposit Amount: '); */?></b> <span id="totalDepositAmount"> <?/*= number_format($totalDeposit + $totalOldDeposit, 2); */?> KRW</span>-->

                        <b><?= __('Total Buy Amount: '); ?></b> <span id="totalBuyAmount"><?= number_format($totalBuy, 2); ?> USDT</span>,
                        <b><?= __('Total Sell Amount: '); ?></b> <span id="totalSellAmount"> <?= number_format($totalSell, 2); ?> USDT</span>,
                        <b><?= __('Total Deposit Amount: '); ?></b> <span id="totalDepositAmount"> <?= number_format($totalDeposit + $totalOldDeposit, 2); ?> USDT</span>

                        <div class="flex-container">
                            <div id="ifNotAuth" class="flex-child">
                                <span style="color: red;"><a style="color: red" id="elink" href="/front2/users/id-verification"><?= __('User Level: 1, Please authenticate yourself to be able to withdraw'); ?></a></span>
                            </div>
                            <div id="ifLess" class="flex-child">
                                <span style="color: red;"><?= __('Withdrawal conditions do not match.'); ?></span>
                            </div>
                            <div id="ifDeposit" class="flex-child" style="position: relative;">
                                <img id="depositImg" src="<?= $this->request->webroot ?>assets/html/images/cross.png" style="position: absolute;
                        top: -40px; left: 50%; transform: translateX(-50%);"/>
                                <span style="color: red;"><?= __('Withdrawal conditions do not match'); ?></span>
                            </div>
                            <div id="ifDepositPending" class="flex-child" style="margin-left: margin-left: 5px;">
                                <!-- <span style="color: red;"><?= __('입금신청중'); ?></span> -->
                                <span style="color: red;"><?= __('Requesting for deposit'); ?></span>
                            </div>
                            <div id="ifWithdrawPending" class="flex-child" style="margin-left: margin-left: 5px;">
                                <!-- <span style="color: red;"><?= __('출금신청중'); ?></span> -->
                                <span style="color: red;"><?= __('Requesting for withdrawal'); ?></span>
                            </div>
                        </div>
                        <label><input type="radio" name="withdrawal_type" id="withdrawal_type_out" value="OUT" checked/> <?= __('External withdrawal') ?> </label>
                        <label><input type="radio" name="withdrawal_type" id="withdrawal_type_in" value="IN"/> <?= __('Internal withdrawal') ?> </label>
                        <br/>
                    </div>
                    <span id="table_withdrowl1">
                            <div class="table_withdrowl" style="position: relative;">
                                <table class="withdrawal" style="width: 100% !important;>
                                    <tr>
                                        <td class="title">
                                            <?= __('Withdrawable amount') ?>
                                        </td>
                                    </tr>
                                        <tr>
                                        <td colspan="3" class="right">
                                            <span class="amount" id="amount_data"></span>
                                            <span class="unit" id="unit_data"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title height-100">
                                            <?= __('Withdrawal request amount') ?>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td class="no-border right height-100 ">
											<?php
                                            $onclick_function = '';
                                            $readonly = '';
                                            /*if($this->check_ip() == 'fail') {
                                                $onclick_function = 'confirm_alert1()';
                                                $readonly = 'readonly';
                                            }*/
                                            ?>
                                            <div class="price5">
                                                <input type="number" name="req_amount" id="req_amount" class="req_amount" onclick="<?= $onclick_function; ?>" <?= $readonly; ?> placeholder="<?= __('Enter withdrawal request amount') ?>"/><span class="unit"></span>
                                                <span class="up1" id="buy_price_up" onclick="increment()"><i class="fa fa-caret-up"></i></span>
                                                <span class="up2" id="buy_price_down" onclick="decrement()"><i class="fa fa-caret-down"></i></span>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="title">
                                            <?= __('Withdrawal fee') ?> <span id="withdraw_fee_percent"></span>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td colspan="3" class="right">
                                            <span id="withdrawalfee" class="amount">0</span><span class="unit unit_data"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title blue">
                                            <?= __('Total withdrawal digital assets') ?>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td colspan="3" class="right gray_back">
                                            <span class="amount blue" id="totalValuekrw">0</span><span class="unit unit_data"></span>
                                        </td>
                                    </tr>
                                </table>
                                <select name="wallet_address" class="form-control" id="wallet_address" style="margin-top: 15px; height: 48px;">
                                    <option value=""><?= __('Please register a wallet address first') ?></option>
                                </select>
                                <div class="otp_number" style="display: none">
                                    <input id="pass" name="pass" required value="" type="password" class="otp_number" data-type="password" maxlength="20"
                                           placeholder="<?= __('Please enter password') ?>" />
                                </div>
                                <br/>
                                <div id="otp_success" class="alert alert-success" style="display: none;"></div>
                                <br/>
                                <div style="position:absolute; left:15%; margin-top: 1%; transform: translateX(-50%) padding: 10px;">
                                     <button type="button" name="" id="withdrawrequestdata_pw" onclick="openOTPW()" class="middle"><?= __('Withdrawal request') ?></button>
                                </div>

                                <div class="cls">
                                    <br/><br/><br/><br/><br/><br/>
                                </div>
                            </div>
                        </span>
                    <span id="table_withdrowl2">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="color:#000"><?= __('Coin Name') ?></th>
                                    <th style="color:#000"><?= __('Main Account') ?></th>
                                    <th style="color:#000"><?= __('Transfer') ?></th>
                                    <th style="color:#000"><?= __('Trading Account') ?></th>
                                    <th style="color:#000"><?= __('Reserved') ?></th>
                                </tr>
                            </thead>
                            <tbody id="internal_withdrawlist">
                                <tr>
                                    <td>
                                        <span id="unitdatatable_withdrowl2"></span>
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
                            <?= __('Notes withdrawal') ?>
                        </div>

                        <div class="desc">
                            <p>- <?= __('Notes withdrawal1') ?></p>
                            <p>- <?= __('Notes withdrawal2') ?></p>
                            <p>- <?= __('Notes withdrawal3') ?></p>
                            <p>- <?= __('Notes withdrawal4') ?></p>
                            <p>- <?= __('Notes withdrawal5') ?></p>
                            <p>- <?= __('Notes withdrawal6') ?><?= __('Notes withdrawal7') ?></p>
                            <p>
								<span class="red">
									- <?= __('Notifications for deposit requests6') ?>
								</span>
								<span>
									<?= __('Notifications for deposit requests6-2') ?>
								</span>
							</p>
                        </div>
                </div>

                <!--KRW Withdrawal -->
                <div style="display:none;" class="common_tab" id="krwwithdrawal_tab_content">
                    <div class="exting">
                        <!--                            <b><?/*= __('Total Buy Amount: '); */?></b> <span id="totalBuyAmountW"><?/*= number_format($totalBuy, 2); */?> KRW</span>,<b><?/*= __('Total Sell Amount: '); */?></b>
                            <span id="totalSellAmountW"> <?/*= number_format($totalSell, 2); */?> KRW</span>,<b><?/*= __('Total Deposit Amount: '); */?></b>
                            <span id="totalDepositAmount"> <?/*= number_format($totalDeposit + $totalOldDeposit, 2); */?> KRW</span><br/><br/>-->

                        <!-- <b><?/*= __('Total Buy Amount: '); */?></b> <span id="totalBuyAmountW"><?/*= number_format($totalBuy, 2); */?> KRW</span>,
                            <b><?/*= __('Total Sell Amount: '); */?></b>
                            <span id="totalSellAmountW"> <?/*= number_format($totalSell, 2); */?> KRW</span>,<b><?/*= __('Total Deposit Amount: '); */?></b>
                            <span id="totalDepositAmount"> <?/*= number_format($totalDeposit + $totalOldDeposit, 2); */?> KRW</span>-->

                        <b><?= __('Total Buy Amount: '); ?></b> <span id="totalBuyAmountW"><?= number_format($totalBuy, 2); ?> USDT</span>,
                        <b><?= __('Total Sell Amount: '); ?></b>
                        <span id="totalSellAmountW"> <?= number_format($totalSell, 2); ?> USDT</span>,<b><?= __('Total Deposit Amount: '); ?></b>
                        <span id="totalDepositAmount"> <?= number_format($totalDeposit + $totalOldDeposit, 2); ?> USDT</span>

                        <!-- <b>총 출금 금액</b> -->
                        <b>total withdrawal amount</b>
                        <span>
                                <?php
                                $outprice = str_replace('-','',$outprice);
                                echo number_format($outprice,2);
                                ?>USDT
                            </span>

                        <br>
                        <!-- <b>출금 가능 금액</b> -->
                        <b>Amount that can be withdrawn</b>
                        <span id="inoutprice">
                                <?=number_format($inoutprice,2);?>USDT
                            </span>

                        <br/><br/>

                        <div class="flex-container">
                            <div id="ifNotAuthW" class="flex-child">
                                <span style="color: red;"><a style="color: red" id="elink" href="/front2/users/id-verification"><?= __('User Level: 1, Please authenticate yourself to be able to withdraw'); ?></a></span>
                            </div>
                            <div id="ifLessW" class="flex-child" style="margin-left: margin-left: 5px;">
                                <span style="color: red;"><?= __('Withdrawal conditions do not match.'); ?></span>
                            </div>
                            <div id="ifDepositW" class="flex-child" style="position: relative; margin-left: 5px;">
                                <img id="depositImg" src="<?= $this->request->webroot ?>assets/html/images/cross.png" style="position: absolute;
                            top: -40px; left: 50%; transform: translateX(-50%);"/>
                                <span style="color: red;"><?= __('Withdrawal conditions do not match'); ?></span>
                            </div>
                            <div id="ifDepositPendingW" class="flex-child" style="margin-left: margin-left: 5px;">
                                <!-- <span style="color: red;"><?= __('입금신청중'); ?></span> -->
                                <span style="color: red;"><?= __('Requesting for deposit'); ?></span>
                            </div>
                            <div id="ifWithdrawPendingW" class="flex-child" style="margin-left: margin-left: 5px;">
                                <!-- <span style="color: red;"><?= __('출금신청중'); ?></span> -->
                                <span style="color: red;"><?= __('Requesting for withdrawal'); ?></span>
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
                                            <!--<span class="amount" id="krw_amount_data" name="krw_amount_data"> <?/*= number_format((float)$trading, 2); */?></span><span class="unit">KRW</span>-->
                                            <span class="amount" id="krw_amount_data" name="krw_amount_data"> <?= number_format((float)$trading, 2); ?></span><span class="unit">USDT</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title">
                                            <?= __('Requested Amount') ?>
                                        </td>
                                        <td>
                                            <!--<input type="number" name="req_amount_krw" id="req_amount_krw" class="req_amount" placeholder="<?/*= __('Enter Amount') */?>" onkeypress="return isNumberKey(this, event);"/><span class="unit"> KRW</span>-->
                                            <input type="number" name="req_amount_krw" id="req_amount_krw" class="req_amount" placeholder="<?= __('Enter Amount') ?>" onkeypress="return isNumberKey(this, event);"/><span class="unit"> USDT</span>
                                        </td>
                                        <td class="title">
                                            <?= __('Withdrawal fee') ?>
                                        </td>
                                        <td class="right">
                                            <!--<span class="amount" id="krwWithdrawalfee">1,000</span><span class="unit">KRW</span>-->
                                            <span class="amount" id="krwWithdrawalfee">1,000</span><span class="unit">USDT</span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="cls">
                                    <br/><br/>
                                </div>
                                <table class="withdrawal">
                                    <tr>
                                        <td class="title" style="width: max-content;color: blue;">
                                            <?= __('Total Amount') ?>
                                        </td>
                                        <td style="width: 80%;" class="right">
                                            <!--<span name="totalAmountkrw" class="amount blue" id="totalAmountkrw">0</span><span style="color:blue;"> &nbsp;&nbsp;KRW</span>-->
                                            <span name="totalAmountkrw" class="amount blue" id="totalAmountkrw">0</span><span style="color:blue;"> &nbsp;&nbsp;USDT</span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="cls">
                                    <br/><br/>
                                </div>
                                <span><?= __('Details about withdrawal account'); ?></span>
                                <table class="withdrawal" style="margin-top: 10px;">
                                    <tr>
                                        <td class="title" style="width: 23%;">
                                            <?= __('Bank Account Number') ?>
                                        </td>
                                        <td colspan="3" style="text-align: start;">
                                            <span id="bank_account" style="padding-left: 10px;"> <?= $this->Decrypt($users['account_number']); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title" style="width: max-content;">
                                            <?= __('Bank Name') ?>
                                        </td>
                                        <td style="text-align: start;">
                                            <span id="bank_name" style="padding-left: 10px;"><?= __($users['bank']); ?></span>
                                        </td>
                                        <td class="title" style="width: 20%;">
                                            <?= __('Account Holder') ?>
                                        </td>
                                        <td style="text-align: start;">
                                            <span id="account_holder" style="padding-left: 10px;"><?= ucfirst($users['name']); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="cls">
                                    <br/><br/>
                                </div>
                                <table class="withdrawal">
                                    <tr>
                                        <td style="border: 1px #1b0552 solid;">
                                            <input id="password" name="password" required value="" type="password"  class="otp_number_krw" data-type="password" maxlength="20"
                                                   placeholder="<?= __('Please enter password') ?>" />
                                        </td>
                                    </tr>
                                </table>
                                <div id="otp_success_krw" class="alert alert-success" style="display: none;"></div>
                                <div id="ifpendingW">
                                    <!-- <span style="color: red;">주문 처리중 입니다</span> -->
                                    <span style="color: red;">Order is being processed</span>
                                </div>
                                <div id="notauthw">
                                    <span style="color: red;"><a style="color: red;" id="elink" href="/front2/users/id-verification"><?= __('Go to auth') ?></a></span>
                                </div>
                                <br/>
                                <div style="position:absolute; left:35%; margin-top: 5%; transform: translateX(-50%) padding: 10px;">
                                     <button name="" id="withdraw_btn_pw" class="middle" onclick="openOTP();"><?= __('Withdrawal request') ?></button>
                                </div>
                                <div class="cls">
                                    <br/><br/><br/><br/><br/><br/>
                                </div>
                            </div>
                        </span>
                </div>
                <!-- KRW Withdrawal end -->

                <!-- Statement Start-->
                <div style="display:none;" class="common_tab" id="statement_tab_content">
                    <ul class="tab_menu_statement">
                        <li id="depositListStatement_on" class="depositListState" onClick="tabClick('depositListState')">
                            <a href="javascript:void(0);"><?= __('Deposit Statement') ?></a>
                        </li>
                        <li id="withdrawalListStatement_on" class="withdrawListState" onClick="tabClick('withdrawListState')">
                            <a href="javascript:void(0);"><?= __('Withdrawal Statement') ?></a>
                        </li>
                    </ul>
                    <div class="order_tab_system" id="deposit_li_div" style="margin-top: 5%;">
                        <table class="list tablewidth" id="depositTable" style="background:#fff;">
                            <thead>
                            <tr>
                                <td><?= __('Transaction') ?></td>
                                <td><?= __('Amount') ?></td>
                                <td><?= __('Date & Time') ?></td>
                                <td><?= __('Status') ?></td>
                            </tr>
                            </thead>
                            <tbody id="myDepositlist"></tbody>
                        </table>
                    </div>
                    <div class="order_tab_system" id="withdraw_li_div" style="display:none">
                        <table class="list tablewidth" id="withdrawTable" style="background:#fff;">
                            <thead>
                            <tr>
                                <td><?= __('Transaction') ?></td>
                                <td><?= __('Amount') ?></td>
                                <td><?= __('Fees') ?></td>
                                <td><?= __('Date & Time') ?></td>
                                <td><?= __('Status') ?></td>
                            </tr>
                            </thead>
                            <tbody id="myWithdrawlist"></tbody>
                        </table>
                    </div>
                </div>
                <!-- Statement End -->

                <div style="display:none;" class="common_tab common_tab22 asset_list" id="breakdown_tab_content">
                    <table>
                        <thead>
                        <tr>
                            <td><?= __('Division') ?></td>
                            <td><?= __('Request amount') ?>(RBTC)</td>
                            <td><?= __('Fee') ?>(RBTC)</td>
                            <td><?= __('Amount') ?>(RBTC)</td>
                            <td><?= __('Date') ?></td>
                            <td><?= __('State') ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="6" class="blank">
                                <?= __('No transaction details') ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <form class="rwwd_modal_form1" onSubmit="return false;">
                    <div style="display:none;" class="common_tab" id="withdrawal_addr_tab_content">
                        <div class="asset_list common_tab22">
                            <table>
                                <thead>
                                <tr>
                                    <td></td>
                                    <td><?= __('Wallet Name') ?></td>
                                    <td><?= __('Wallet Address') ?></td>
                                    <td><?= __('Date and time of registration') ?></td>
                                </tr>
                                </thead>
                                <tbody id="withdrawal_addr"></tbody>
                            </table>
                        </div>
                        <table style="width:100%">
                            <tr>
                                <td style="text-align:left">
                                    <button type="submit" name="" id="deleteAddress" class="white"><?= __('Delete') ?></button>
                                </td>
                                <td style="text-align:right">
                                    <a name="" onclick="openAddWithdrawalWalletAddrModel()">+ <?= __('Register withdrawal address') ?></a>
                                </td>
                            </tr>
                        </table>
                        <div class="desc dest_mt70" style="margin-top:70px">
                            <p>- <?= __('Notes Address Text1') ?></p>
                            <p>- <?= __('Notes Address Text2') ?></p>
                        </div>

                        <div id="add_address">
                            <div style='margin:40px 0 30px; color:#000000; font-size: 22px;'>
                                <?= __('Register withdrawal address') ?>
                            </div>
                            <table>
                                <tr>
                                    <td class="title">
                                        <?= __('Name2') ?>
                                    </td>
                                    <td>
                                        <input type="text" name="" value=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <?= __('Wallet Address') ?>
                                    </td>
                                    <td>
                                        <input type="text" name="" value=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <?= __('Enter OTP Number') ?>
                                    </td>
                                    <td>
                                        <input type="text" name="" value=""/>
                                    </td>
                                </tr>
                            </table>
                            <div>
                                <button class='white' onclick='hideMsgWindow()'><?= __('Cancel') ?></button>
                                <button><?= __('Registration') ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="cls"></div>
    </div>
</div>
<div id="myModalDeposit" class="modal fade" role="dialog">
    <div class="modal-dialog" style='color:#000;'>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="modal_coin_name"></span> <?= __('Transfer to') ?> <span id="wallet_name"> </span> <?= __('account') ?></h4>
            </div>
            <div class="modal-body" style="text-align:center;">
                <form action="#" autocomplete="off" id="deposit_modal_form" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="coin_id" name="coin_id">
                    <input type="hidden" class="form-control" id="transfer_to" name="transfer_to">
                    <div class="form-group">
                        <label for="email"><?= __('Amount: ') ?></label>
                        <input type="text" class="form-control" required placeholder="<?= __('Enter Amount') ?>" name="amount" onkeypress="return isNumberKey(this, event);">
                    </div>
                    <button type="submit" class="btn btn-default" id="btnSubmitNew"><?= __('Submit') ?></button>
                    <img id="model_qr_code_flat" style="display:none;" src="/ajax-loader.gif"/>
                    <div id="get_resp" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="selected_coind_id"/>

<!-- Modal -->
<div id="myModalAddWithdrawalWalletAddr" class="modal fade" role="dialog">
    <div class="modal-dialog" style='color:#000;'>
        <?= $this->Form->create('', ['url' => ['controller' => 'Assets', 'action' => 'registerWithdrawalWalletAddrAjax']]); ?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= __('Register Withdrawal Wallet Address') ?></h4>
            </div>
            <div class="modal-body" style="text-align:center;">
                <!-- <form action="#" autocomplete="off" id="rwwd_modal_form"  onSubmit="return false;" enctype="multipart/form-data"> -->
                <input type="hidden" class="form-control" id="rwwd_coin_id" name="coin_id">
                <div class="form-group">
                    <label for="email"><?= __('Wallet Name: ') ?></label>
                    <input type="text" class="form-control" style="background-color:#fff!important;;" required placeholder="<?= __('Please enter wallet name') ?>" readonly id="rwwd_wallet_name" name="rwwd_wallet_name">
                </div>
                <div class="form-group">
                    <label for="email"><?= __('Wallet Address: ') ?></label>
                    <input type="text" class="form-control" style="background-color:#fff!important;" required placeholder="<?= __('Please enter wallet address') ?>" id="rwwd_wallet_addr" name="rwwd_wallet_addr">
                </div>
                <button type="button" class="btn btn-default" id="btnSubmit"><?= __('Submit') ?></button>
                <img id="rwwd_model_ajax" style="display:none;" src="/ajax-loader.gif"/>
                <div id="rwwd_get_resp" class="alert" style="display:none;"></div>
                <!-- </form> -->
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Modal -->
<div id="myModalOTP" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered bd-example-modal-sm">
        <!-- Modal content-->
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title"><?= __('Withdrawal OTP Authentication') ?></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?= __('OTP: ') ?></label>
                        <input type="number" maxlength="6" id="otp_number_krw" name="otp_number_krw" class="input" value="" placeholder="<?= __('Please Enter OTP') ?>" onkeydown="only_number(this)"
                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" style="background-color:#240978;" id="withdraw_btn"><?= __('Withdraw') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModalOTPW" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered bd-example-modal-sm">
        <!-- Modal content-->
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!--<h5 class="modal-title"><?/*= __('Withdrawal OTP Authentication') */?></h5>-->
                    <h5 class="modal-title">출금 확인</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <!--<label for="email"><?/*= __('OTP: ') */?></label>-->
                        <label for="email">출금 하시겠습니까?</label>
                        <input type="hidden" maxlength="6" id="otp_number" name="otp_number" class="input" value="" placeholder="<?= __('Please Enter OTP') ?>" onkeydown="only_number(this)"
                               oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                    </div>
                    <div>
                        <img id="ajax_loader_for_w" src="/webroot/ajax-loader.gif" class="send-ajax-img"  style="display: none;"/>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-primary" style="background-color:#240978;" id="withdrawrequestdata"><?/*= __('Withdraw') */?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?/*=__('Cancel');*/?></button>-->
                        <button type="button" class="btn btn-primary" style="background-color:#240978;" id="withdrawrequestdata">확인</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>