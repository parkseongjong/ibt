<div class="container">

    <div class="assets_box">
        <div class="left mycoinleft mycoinleft22">
            <?php echo $this->element('Front2/assets_left'); ?>
        </div>


        <div class="mycoinrigth">
            <div class="mycoinrigth_pp">

                <?php echo $this->element('Front2/assets_menu'); ?>

<div style="text-align: center; margin-top:100px" class="common_tab" id="default_content">
    <!--<button class="big" onclick="createWallet()"><?=__('Generate Deposit Address')?></button>
						<p style="margin-top: 50px; font-weight: 300; font-size: 15px;">
							<?php //echo __('Generate Deposit Address Text')?>
						</p>-->



    <!-- <p class="gda">
							<?php //echo __('Please select coin');  ?>
						</p> -->

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
                            <!-- need db value -->
                            110-500-111111
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="grid-col grid-col-2 grid-title">
                            은행명
                        </div>
                        <div class="grid-col grid-col-4">
                            <!-- need db value -->
                            신한은행
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
                    <?php
                    $phone_number = $userDetail['phone_number'];
                    $masked_phone_number = substr($phone_number, -4);
                    echo ucfirst($_SESSION['Auth']['User']['name']) . $masked_phone_number;
                    ?>
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

            <div class="krw-input-area">
                <div class="input-group">
                    <input type="number" placeholder="입금요청금액을 입력하세요">
                    <div class="input-postfix">KRW</div>
                </div>
                <button class="krw-input-btn">일반입금</button>
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