<style>

    .modal-open {
        overflow: hidden
    }
    .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0
    }
    .modal.fade .modal-dialog {
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        -o-transform: translate(0, -25%);
        transform: translate(0, -25%);
        -webkit-transition: -webkit-transform .3s ease-out;
        -o-transition: -o-transform .3s ease-out;
        transition: -webkit-transform .3s ease-out;
        transition: transform .3s ease-out;
        transition: transform .3s ease-out, -webkit-transform .3s ease-out, -o-transform .3s ease-out
    }

    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0)
    }

    .modal-open .modal {
        overflow-x: hidden;
        overflow-y: auto
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 10px
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        background-clip: padding-box;

        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 6px;
        -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        outline: 0
    }

    .modal-content .service_contents td div input {
        box-sizing: border-box;
        height: 100%;
        font-size: 20px;
        padding: 12px 20px;
        margin: 10px 20px;
        display: inline-block;
        border: 1px solid #ccc;

    }



    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        background-color: #000
    }

    .modal-backdrop.fade {
        filter: alpha(opacity=0);
        opacity: 0
    }

    .modal-backdrop.in {
        filter: alpha(opacity=50);
        opacity: .5
    }

    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5
    }

    .modal-header .close {
        margin-top: 2px;
        float: right;
    }

    .modal-title {
        margin: 0;
        line-height: 1.42857143;
        font-size: large;
        font-weight: bold;
    }

    .modal-body {
        position: relative;
        padding: 15px;
        align-content: center;
        align-items: end;
        text-align: center;
    }

    .modal-footer {
        padding: 15px;
        text-align: center;
        border-top: 1px solid #e5e5e5
    }

    .modal-footer .myBtn button{
        width: 30%;
        height: 40px;
        font-size: 18px;
        font-weight: 600;
        line-height: 2.1;
        color: #6738ff;
        border-radius: 5px;
        border: solid 1px #6738ff;
        background-color: #ffffff;
        cursor: pointer;
        alignment: center;
    }

    .modal-footer .myBtn button:hover:enabled {
        background-color: #6738ff;
        color: white;
    }

    .modal-footer .myBtn button:active:enabled {
        background-color: white;
        color: black;
        border: 2px solid #6738ff
    }

    .application .myBtn button:disabled{
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
    .application .myBtn #authBtn {
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    /*.modal-footer .btn-group .btn+.btn {*/
    /*    margin-left: -1px*/
    /*}*/

    /*.modal-footer .btn-block+.btn-block {*/
    /*    margin-left: 0*/
    /*}*/

    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    @media (min-width:768px) {
        .modal-dialog {
            width: 450px;
            margin: 30px auto
        }
        .modal-dialog-auth {
            width: 800px;
        }
        .modal-content {
            -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
        }
        .modal-sm {
            width: 300px
        }
    }

    @media (min-width:992px) {
        .modal-lg {
            width: 900px
        }
    }
</style>

<style>

    .application .info {
        list-style-type: none;
        text-align: right;
        margin-top: 69px;
    }
    .application .info li {
        display: inline-block;
        margin: 0;
    }
    .application .info li span:nth-of-type(1){
        color: #8a8a8a;
        font-size: 14px;
    }
    .application .info li span:nth-of-type(2){
        font-size: 14px;
        margin-left: 22px;
    }
    .application .info li.bar {
        width: 1px;
        background-color: #dcdcdc;
        height: 15px;
        margin: 0 30px;
    }

    .application .my_info {
        background-color: #fcfbff;
        margin: 20px 0 44px;
        width: 100%;
    }
    .application .my_info td {
        text-align: left;
    }
    .application .my_info tr:nth-of-type(1) td {
        padding-top: 42px;
    }
    .application .my_info tr:nth-of-type(2) td {
        padding-bottom: 42px;
    }
    .application .my_info tr td:nth-of-type(1) {
        font-size: 16px;
        color: #515151;
        padding-left: 32px;
        padding-right: 24px;
    }
    .application .my_info .price {
        font-size: 30px;
        font-weight: bold;
    }
    .application .my_info .unit {
        font-size: 16px;
        color: #515151;
    }
    .application .my_info .text2 {
        font-size: 20px;
    }
    .application .my_info .btn {
        font-size: 13px;
        color: #FFFFFF;
        text-align: center;
        background-color: #1a1a1a;
        border-radius: 15px;
        padding: 11px 19px;
        margin-left: 28px;
        line-height: 50px;
        white-space: nowrap;
    }

    .application table.service_contents th {
        text-align: left;
        font-size: 15px;
        color; #515151;
        padding-right: 60px;
    }
    .application table.service_contents td {
        text-align: left;
        padding: 10px 0;
    }
    .application table.service_contents td span,p {
        font-size: 13px;
        color: #717171;
    }

    .application table.service_contents input[type=radio] {
        display: none;
    }
    .application table.service_contents input[type=radio] + label {
        display: inline-block;
        box-sizing: border-box;
        height: 48px;
        border-radius: 3px;
        border: solid 1px #dcdcdc;
        background-color: #FFFFFF;
        font-size: 15px;
        line-height: 48px;
        text-align: center;
        margin-right: 9px;
        padding: 0 16px;
        min-width: 81px;
    }
    .application table.service_contents input[type=radio]:checked + label {
        background-color: #828282;
        color: #f9f9f9;
    }
    .application table.service_contents tr:nth-of-type(4) td:nth-of-type(2) p {
        margin-bottom: 16px;
    }
    .application .service_contents td div {
        border: 1px solid #dcdcdc;
        width: 363px;
        box-sizing: border-box;
        display: inline-block;
        margin-right: 17px;
    }
    .application .service_contents td div input {
        box-sizing: border-box;
        height: 100%;
        padding: 14.5px 12px;
        font-size: 15px;
        border: 0;
    }
    .application .service_contents tr:nth-of-type(1) td div input {
        width: 320px;
    }
    .application .service_contents tr:nth-of-type(2) td div input {
        width: 320px;
    }
    .application .service_contents tr:nth-of-type(1) td div:after {
        /*content: '<?=__('TP3')?>';*/
        content: '<?=$coin?>';
        color: #9b9b9b;
        font-size: 15px;
    }
    .application .service_contents tr:nth-of-type(2) td div:after {
        /*content: '<?=__('TP3')?>';*/
        content: '<?=$coin?>';
        color: #9b9b9b;
        font-size: 15px;
    }
    .application .service_contents .per {
        font-size: 14px;
        padding-right: 9px;
        color: #000000;
    }
    .application .service_contents .per2 {
        font-size: 16px;
        padding-right: 19px;
        color: #000000;
    }

    .application .service_agree {
        margin-top: 60px;
        border-top: 1px solid #dddddd;
        margin-bottom: 70px;
        border-bottom: 1px solid #dddddd;
        padding: 36px 11px 51px;
    }
    .application .service_agree td div {
        display: inline-block;
    }
    .application .myBtn button{
        width: 50%;
        height: 50px;
        font-size: 20px;
        font-weight: 600;
        line-height: 2.1;
        color: #6738ff;
        border-radius: 5px;
        border: solid 1px #6738ff;
        background-color: #ffffff;
        cursor: pointer;
    }

    .application .myBtn button:hover:enabled {
        background-color: #6738ff;
        color: white;
    }

    .application .myBtn button:active:enabled {
        background-color: white;
        color: black;
        border: 2px solid #6738ff
    }

    .application .myBtn button:disabled{
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
    .service_contents{ width:100%;}


    @media only screen and (max-width:767px) {
        .tab_menu { margin-top: 5px;}
        .application table.service_contents tr, .application table.service_contents th, .application table.service_contents td{ display:block;  }
        .application .info { margin-top:20px;    text-align: left;}
        .application .info li{ width:100%;    margin-top: 10px;}
        .application .info li.bar{ display:none}
        .application .info li span{    margin-left: 22px;}
        .application .my_info tr, .application .my_info th, .application .my_info td{display:block;}
        .application .my_info tr:nth-of-type(1) td { padding-top: 5px;    padding-left: 0px;padding-right: 0px;}
        .application .my_info tr:nth-of-type(2) td{    padding-bottom: 20px;}
        .application .my_info .price { font-size: 24px;}
        .application .my_info .btn{ margin-left:0; margin-top:10px;}
        .application .my_info{    margin: 20px 0 20px;}
        .application .service_contents tr:nth-of-type(1) td div input, .application .service_contents tr:nth-of-type(2) td div input{ width:88%}
        .application table.service_contents input[type=radio] + label{margin-bottom: 10px;}
        .application .service_agree {margin: 0;}
        .application .myBtn button:disabled {margin-top: 15px;}
        .table_scrool table {
            width: 600px!important;
        }
        .table_scrool {    overflow-y: auto;}
        .application .service_contents td div{    margin-right: 0;    width: 100%;}
        .application .my_info .text2{ display:block;}
        .application .my_info td{text-align: center;}
        .modal-content .service_contents td div input{    width: 98%;    margin: 10px 0;padding: 12px 5%;}
        .modal-footer .myBtn button {width: 50%;}

    }
    .modal .copy-key {width: 90px; height: 36px;border-radius: 0px;background-color: rgba(43, 51, 220, 0.99);border: 0; color: #fff; font-size: 12px; font-weight: 600; outline: none;}
    .modal .otpAuth {text-align:left;}
    .modal .otp-tbl {margin: 15px; width: 100%;}
    .header2{
        display: none !important;
    }
    .tab_menu{
        display: none !important;
    }
    #bottom_menu{
        display: none !important;
    }
    #footer{
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
<?php echo $this->Form->create('depositapp',array('id'=>'depositapp', 'name'=>'depositapp','method'=>'post', 'type'=>'post'));?>
<div class="container">
    <div class="profile_box">
        <?php echo $this->element('Front2/investment_menu'); ?>
        <div class="service_info application">
            <ul class="info">
             <!--   <li>
                    <span><?/*=__('Usage Fee') */?></span>
                    <span><?/*=__('Free') */?></span>
                </li>-->
                <!--<li class="bar"></li>-->
                <li>
                    <span><?=__('Maximum Limit') ?></span>
                    <span><?=__('Fifty thousand coins') ?>~<?=__('Two million coins') ?></span>
                </li>
                <li class="bar"></li>
                <li>
                    <span><?=__('Period') ?></span>
                    <span><?=__('Deposit Service Period') ?></span>
                </li>
            </ul>
            
            <!-- 수정시작 -->
            <div id="success-msg" style="width: 50%; text-align: center; alignment: center; position: center; margin-left: 25%; margin-bottom: 2%"><?= $this->Flash->render() ?> </div>
            <div class="a_table_left">
                <table class="service_contents">
                    <tbody>
                    <tr>
                        <th>TP3 보유량</th>
                        <th>
                            <?=number_format($tp3_value);?> TP3
                        </th>
                    </tr>
                    <tr>
                        <th>MC 보유량</th>
                        <th>
                            <?=number_format($mc_value);?> MC
                        </th>
                    </tr>
                    <tr>
                        <th>KRW 보유량</th>
                        <th>
                            <?=number_format($total_krw);?> KRW
                        </th>
                    </tr>

                    <tr>
                        <th>신청할 코인</th>
                        <td>
                            <input type="radio" name="coin_type" id="coin_type" value="TP3" checked><label for="coin_type">TP3</label>
                            <input type="radio" name="coin_type" id="coin_type2" value="MC"><label for="coin_type2">MC</label>
                        </td>
                    </tr>


                    <tr style="display: none">
                        <th><?=__('Application quantity')?></th>
                        <td>
                            <?php
                            foreach($quantity_list as $l) {
                                ?><input type="radio" name="quantity" id="quantity<?=$l->id;?>" value="<?=$l->amount;?>" onchange="checkQuantity(this.value)"><label for="quantity<?=$l->id;?>"><?php echo number_format($l->amount); ?></label><?php
                            }
                            ?>
                            <p class="quantity-msg" style="display:none; color: #f36;"><?=__('Investment Deposit Text4')?></p>
                        </td>
                    </tr>
        <!--            <tr>
                        <th>
                            수수료 안내
                        </th>
                        <th>
                            TP3 쿠폰 1개당 KRW 얼마 소비
                        </th>
                    </tr>-->
                    <tr>
                        <th>신청</th>

                        <td>
                            <div style="border: 0">신청 수량 <p style="font-family: 'Arial Black'" id="coin_amount"><?=$amount;?></p><br>
                                 KRW <p id="krw_amount" style="font-family: 'Arial Black'"><?=$krw?></p>
                                <input type="hidden" id="coin_value" name="coin_value" value="<?=$amount;?>">
                                <input type="hidden" id="coin_value_tp3" name="coin_value_tp3" value="<?=$tp3_value;?>">
                                <input type="hidden" id="coin_value_mc" name="coin_value_mc" value="<?=$mc_value;?>">
                                <input type="hidden" id="krw_value" name="krw_value" value="<?=$krw;?>">
                                <input type="hidden" id="krw_total" name="krw_total" value="<?=$total_krw;?>">
                                <!-- 쿠폰값 개수 체크 -->
                                <input type="hidden" id="coupon_cnt" name="coupon_cnt" value="1">
                            </div>
                            <div style="border: 0">
                                <p><button  class="btn btn-default" onclick="uptest()">추가(+)</button></p>
                                <p><button  class="btn btn-default" onclick="downtest()">빼기(-)</button></p>
                            </div>
                        </td>
                    </tr>

                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap');
                        button {
                            -webkit-appearance: none;
                            -moz-appearance: none;
                            appearance: none;

                            background: var(--button-bg-color);
                            color: var(--button-color);

                            margin: 0;
                            padding: 0.5rem 1rem;

                            font-family: 'Noto Sans KR', sans-serif;
                            font-size: 1rem;
                            font-weight: 400;
                            text-align: center;
                            text-decoration: none;

                            border: none;
                            border-radius: 4px;

                            display: inline-block;
                            width: auto;

                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

                            cursor: pointer;

                            transition: 0.5s;
                        }
                    </style>

                    <script>



                        function uptest(){
                            var amount = '<?=$amount?>';

                            var coin_amount = $("#coin_amount").html();

                            var total = parseInt(amount) + parseInt(coin_amount);

                            //alert(total);
                            $("#coin_amount").html(total);
                            $("#coin_value").val(total);

                            //카운트 기본 횟수 0개 에서 최대 10

                            //수량 증가에 따른 증감식 표현 KRW
                            var krw = '<?=$krw?>';
                            var krw_amount = $("#krw_amount").html();

                            var total2 = parseInt(krw) + parseInt(krw_amount);

                            //alert(total);
                            $("#krw_amount").html(total2);
                            $("#krw_value").val(total2);

                            //수량 체크
                            var coupon_cnt = $("#coupon_cnt").val();
                            var test = parseInt(coupon_cnt)+parseInt(1);
                            $("#coupon_cnt").val(test);
                            //alert(test);


                            if($("#coupon_cnt").val() > 10){
                                var amount = '<?=$amount?>';
                                var coin_amount = $("#coin_amount").html();
                                var total =  parseInt(coin_amount)-parseInt(amount) ;


                                $("#coin_amount").html(total);
                                $("#coin_value").val(total);

                                //수량 증가에 따른 증감식 표현 KRW
                                var krw = '<?=$krw?>';
                                var krw_amount = $("#krw_amount").html();

                                var total2 =parseInt(krw_amount)-parseInt(krw);

                                $("#krw_amount").html(total2);
                                $("#krw_value").val(total2);
                                $("#coupon_cnt").val(10);

                                alert("최대 10장까지 입니다.");
                                return ;
                            }
                        }

                        function downtest(){
                            var amount = '<?=$amount?>';
                            var coin_amount = $("#coin_amount").html();
                            var total =  parseInt(coin_amount)-parseInt(amount) ;

                            $("#coin_amount").html(total);
                            $("#coin_value").val(total);

                            //수량 증가에 따른 증감식 표현 KRW
                            var krw = '<?=$krw?>';
                            var krw_amount = $("#krw_amount").html();

                            var total2 = parseInt(krw_amount) - parseInt(krw);

                            $("#krw_amount").html(total2);
                            $("#krw_value").val(total2);

                            //수량 체크
                            var coupon_cnt = $("#coupon_cnt").val();
                            var test = parseInt(coupon_cnt)-parseInt(1);
                            $("#coupon_cnt").val(test);

                            if($("#coupon_cnt").val() < 1){
                                var amount = '<?=$amount?>';
                                var coin_amount = $("#coin_amount").html();
                                var total = parseInt(coin_amount)+parseInt(amount) ;

                                $("#coin_amount").html(total);
                                $("#coin_value").val(total);

                                //수량 증가에 따른 증감식 표현 KRW
                                var krw = '<?=$krw?>';
                                var krw_amount = $("#krw_amount").html();

                                var total2 =  parseInt(krw_amount) + parseInt(krw);

                                $("#krw_amount").html(total2);
                                $("#krw_value").val(total2);
                                $("#coupon_cnt").val(1);

                                alert("최소 1장 이상 주문하셔야합니다.");

                                return;
                            }
                        }
                    </script>
                    <tr>
                        <th><?=__('Service usage period')?></th>
                        <td>
                            <?php
                            foreach($period_list as $l) {
                                ?><input type="radio" name="servicePeriod" id="servicePeriod<?=$l->id;?>" value="<?=$l->period?>"><label for="servicePeriod<?=$l->id;?>"><?php echo $l->period.__('days') ?></label><?php
                            }
                            ?>
                            <p><?=__('Deposit Application Notice')?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!-- 쿠폰 선택 창 -->


            </div>
            <div class="cls"></div>
            <div class="table_scrool">
                <table class="service_agree" style="text-align: left">
                    <tbody>
                    <tr>
                        <th><?=__('All agree')?></th>
                        <td>
                            <input id="agreeAll" name="agreeAll" type="checkbox" class="check" onclick="checkAll(this)"><label for="agreeAll"><span class="bold"><?=__('All agree') ?></span></label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <th><?=__('Investment Deposit Agree Subject 1')?></th>
                        <td>
                            <input id="agree1" name="agree1" type="checkbox" class="check">
                            <label for="agree1">
                                <span><?=__('Investment Agreement 1')?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u> <a href="javascript:void(0);" onclick="openDialog()"><?= __('Full text view')?></a> </u>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?=__('Investment Deposit Agree Subject 2')?></th>
                        <td>
                            <input id="agree2" name="agree2" type="checkbox" class="check">
                            <label for="agree2">
                                <span><?=__('Investment Agreement 2')?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u> <a href="javascript:void(0);" onclick="openDialog()"><?= __('Full text view')?></a>  </u>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="myBtn">
                <?php
                if($stage == 0){
                    ?>
                    <!--<button id="btn" name="btn" class="button" onclick="openDialog()" data-toggle="modal" data-target="myModal"><?/*=__('Deposit3')*/?>1</button>
                    <button id="btn" name="btn" class="button" onclick="openDialogs()" data-toggle="modal" data-target="myModal"><?/*=__('Deposit3')*/?>2</button>
                    <button id="btn" name="btn" class="button" onclick="openDialogs()" data-toggle="modal" data-target="myModal"><?/*=__('Deposit3')*/?>22</button>-->
                    <?php
                } else {
                    ?>
                    <!--<button id="btn" name="btn" class="button" onclick="openDialog()" data-toggle="modal" data-target="myModal"><?/*=__('Deposit3')*/?>3</button>-->
                    <button id="btn" name="btn" class="button" onclick="openDialogs()" data-toggle="modal" data-target="myModal"><?=__('Deposit3')?></button>
                <?php }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="modalLabel"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="a_table_left">
                    <table class="service_contents">
                        <tbody>
                        <tr>
                            <td id="confirmServicePeriod"></td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->Form->input('password', ['id'=>'password','type'=>'password','label'=>false, 'required'=>true, 'placeholder'=>__('Enter password2')]); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="myBtn">
                    <button type="button" id="submit_btn" name="submit_btn" class="button" onclick="applicationSumbitCheck()"><?=__('Confirm')?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end();	?>
<div id="myModalDeposit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span><?=__('Service Check Information')?> <!--<?=__('Investment Deposit Agree Subject 1')?>--></span>
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
            </div>
            <div class="modal-body">
                <p><?=__('Service Check Information Text')?>232323</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close2')?></button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalGoogleAuth" role="dialog" tabindex="-1" aria-labelledby="GoogleAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-auth" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--<span class="modal-title" id="GoogleAuthModalLabel"><?/*=__('OTP authentication') */?></span>-->
                <span class="modal-title" id="GoogleAuthModalLabel">스테이킹 신청</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="height: 300px;">
                <div id="otpAuth" name="otpAuth" class="a_table_left otpAuth" >
                    <ul class="" style="text-align:center">
                        <li>
                            <label><input id="check" type="checkbox" class="check" onclick="otpSubmitCheck('check')"> <?=__('Confirm collection') ?> </label>
                        <li>
                    </ul>
                    <!--
					<h3 style="margin-top:30px;">
						1. <?=__('How OTP 1') ?>
					</h3>
					<table class="otp-tbl" >
						<tr>
							<td class="" >
								<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target = "_blank"><img src="/wb/imgs/google_play_button.png" width="150" /></a>
								<div style="margin-top:16px;">
									<?=__('Download android') ?>
								</div>
							</td>
							<td >
								<a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target = "_blank"><img src="/wb/imgs/app_store_button.png" width="150" /></a>
								<div style="margin-top:16px;">
									<?=__('Download iphone') ?>
								</div>
							</td>
						</tr>
					</table>
					<h3 style="margin-top:30px;">
						2. <?=__('How OTP 2') ?>
					</h3>
					<table class="otp-tbl" >
						<tr>
							<td style="width: 45%;">
								<img id="googleAuthUrl" src="" style="border:2px solid #2c33dc; padding:12px;width: 80px;" />
							</td>
							<td>
								<input id="key" name="key" type="text" class="text" class="bar_copy_input" style="width: 55%; height: 30px;" />
								<button type="button" class="copy-key" onclick="copyTxt()"style=""><?=__('Copy secret key') ?></button>
							</td>
						</tr>
					</table>
					-->
                    <!--
                     쿠폰 리스트 멘트 설정 부분
                     -->
                    <h3 style="margin-top:30px;">
                        스테이킹 서비스에 동의하십니까? <br>
                    </h3>
                    <div id="otpA" name="otpA" class="level_2_input">
                        <div id="show_msgs"></div>
                        <div id="return_msgs"></div>
                        <table class="otp-tbl" >
                            <tr>
                                <td class="scan_bar" style="width: 45%; display: none">
                                    <input id="authcode" name="authcode" type="text" maxlength="6" placeholder="확인" style="width: 40%; height: 30px;" onkeyup="otpSubmitCheck('authcode')"/>
                                    <!--<p id="six_msg" style="display:none;"><?/*=__('Please enter 6 digits') */?></p>
                                    <p id="checkbox_msg" style="display:none;"><?/*=__('Please agree to collect and use personal information') */?></p>
                                    <p id="error_msg" style="display:none; color: #f36;"></p>-->
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="otp_id_resp" style="display:none;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="myBtn">
                    <button type="button" id="authBtn" onclick="otpSubmitCheck('all')" >확인</button>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var quantityCheck = false;
    var clickCheck = false;
    function doubleSubmitCheck(){
        if(clickCheck){
            return clickCheck;
        }else{
            clickCheck = true;
            return false;
        }
    }
    function applicationSumbitCheck(){
        //임시 주석 체크 확인 체크 완료
        if(doubleSubmitCheck()) return;
        $('#depositapp').submit();
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

    function submitButtonDisableOrEnable(){
        var agree1 =$('input[name="agree1"]').is(':checked');
        var agree2 =$('input[name="agree2"]').is(':checked');
        var agree3 =$('input[name="agree3"]').is(':checked');
        if(agree1 && agree2 && agree3 && quantityCheck != false){
            $("#btn").css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
        }
        else {
            //$("#btn").attr('disabled','disabled');
        }
    }

    function changeStatus(status){
        //임시 주석
/*        if(status === true) {
            $("#agreeAll").prop("disabled", status).attr('disabled', status);
            $("#agree1").prop("disabled", status).attr('disabled', status);
            $("#agree2").prop("disabled", status).attr('disabled', status);
            $("#agree3").prop("disabled", status).attr('disabled', status);

        } else {
            $("#agreeAll").attr('disabled', status);
            $("#agree1").attr('disabled', status);
            $("#agree2").attr('disabled', status);
            $("#agree3").attr('disabled', status);
        }*/
    }

    function validation(){
        if($("#deposit").val() !== '' && $("#quantity").val() !== '' && $('input[name="servicePeriod"]').is(':checked') === true && quantityCheck !== false ){
            changeStatus(false);
        } else {
            changeStatus(true);
            unCheckAll();
        }
    }

    function sh(){
        document.location.href = "/front2/agreement";
    }

    function openDialog(){
        $("#myModalDeposit").modal('show');
    }

    $(document).ready(function(){
        //$("#modalGoogleAuth").modal('show');
        setTimeout(function() {
            $('#success-msg').fadeOut('fast');
        }, 3000);

        var tp3Balance = "<?= $mainBalance; ?>";
        //var totalBalance = "<?//= number_format((float)$totalBalance,2); ?>//";
        // $("#price").html(totalBalance);
        $("#myModalDeposit").hide();
        if(tp3Balance !== ""){
            $('#deposit').attr('readonly', true).val(numberWithCommas(parseFloat(tp3Balance).toFixed(2)));
        } else {
            $('#deposit').attr('readonly', true).val("0");
        }
        getGoogleAuth();

//         $("#quantity").on('keyup keydown keypress', function (e){
//
// //&& e.keyCode !== 46 // keycode for delete
// //               && e.keyCode !== 8 && $(this).val().length >= $("#deposit").val().length // keycode for backspace
// //               && e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57)
//            if($(this).val() > tp3Balance) {
//
//                e.preventDefault();
//                $(this).val($("#deposit").val());
//            }
//         });

        $("#btn").css({'pointer-events': 'none'}).attr('disabled','disabled');
        changeStatus(true);

        $('input[name="agreeAll"]').click(function(){
            submitButtonDisableOrEnable();
        });

        $('input[name="agree1"]').click(function(){
            submitButtonDisableOrEnable();
        });

        $('input[name="agree2"]').click(function(){
            submitButtonDisableOrEnable();
        });

        $('input[name="agree3"]').click(function(){
            submitButtonDisableOrEnable();
        });

        $('input[name="servicePeriod"]').click(function (){
            validation();
        });

    });

    function checkAll(obj) {
        if ($(obj).is(":checked") === true) {
            $('input[type=checkbox]').prop('checked',true);
            $("#btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
        } else {
            $('input[type=checkbox]').prop('checked',false);

            $("#btn").prop('disabled', true).css({'pointer-events': 'none'}).attr('disabled','disabled');
        }
    }

    function unCheckAll() {
        if ($('#agreeAll').is(":checked") === true) {
            $('input[type=checkbox]').prop('checked',false);
            $("#btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});

        } else {
        }
    }

    function openDialogs(){
        var lang = getlang();
        var title, msg, servicePeriod, quantity;
        servicePeriod = $('input[name="servicePeriod"]:checked').val();
        //quantity = numberWithCommas($('input[name="quantity"]:checked').val());
        //새로운값 지정
        var coin_quantity = $("#coin_amount").html();
        var coin_quantity_mc = $("#coin_value_mc").val();
        var coin_quantity_tp3 = $("#coin_value_tp3").val();
        var krw_quantity = $("#krw_amount").html();
        var krw_total = $("#krw_total").val();
        var deposit = $("#deposit").val();
        //투자관련 OTP 확인 후
        var coin_type = $('input[name="coin_type"]:checked').val();
        //코인데이터 계산해야됨

        if(coin_quantity_tp3 == ''){
            coin_quantity_tp3 = '0';
        }

        //alert(coin_quantity);
        //alert(coin_quantity_tp3);
        //alert(coin_quantity_mc);
        //alert(servicePeriod);
        //alert(coin_type);
        if(coin_type == 'TP3'){
            if(parseInt(coin_quantity) > parseInt(coin_quantity_tp3)){
                alert("보유하신 TP3코인이 부족합니다.");
                return;
            }
        }else if(coin_type == 'MC'){
            if(parseInt(coin_quantity) > parseInt(coin_quantity_mc)){
                alert("보유하신 MC코인이 부족합니다.");
                return;
            }
        }

        if(krw_total < parseInt(krw_quantity)){
            alert("보유하신 KRW이 부족합니다.");
            return;
        }


        if( lang == 'kr'){
            //title = quantity + " TP3를 투자하시겠습니까?";
            msg = servicePeriod + "<?=__('You cannot cancel the deposit')?>";
        } else {
            /*title = "Are you sure to deposit " + quantity + " TP3?";
            msg = "<?=__('You cannot cancel the deposit')?>" + servicePeriod + "<?=__('days')?>" ;*/
            //title = quantity + " TP3를 투자하시겠습니까?";
            msg = servicePeriod + "<?=__('You cannot cancel the deposit')?>";
        }
        $('#modalLabel').html(title);
        $('#confirmServicePeriod').html(msg);

        $("#modalGoogleAuth").modal('show');
        //$("#myModal").modal('show');
    }

    /* check Quantity value */

    //여기 부분 수정 값 체크
    function checkQuantity(value){
        var deposit = removeComma($('#deposit').val());
        if(deposit < value){
            $('.quantity-msg').show();
            //quantityCheck = false;
            validation();
            submitButtonDisableOrEnable();
            return;
        } else {
            $('.quantity-msg').hide();
            //quantityCheck = true;
            validation();
            submitButtonDisableOrEnable();
            return;
        }
    }
    /* removeComma */
    function removeComma(str) {
        var n = parseInt(str.replace(/,/g,""));
        return n;
    }
    /* get language */
    function getlang(){
        var cookie = getCookie('Language');
        if(cookie == 'ko_KR'){
            return 'kr';
        } else {
            return 'en';
        }
    }
    /* get QR code and secret key */
    function getGoogleAuth(){
        $.ajax({
            type: 'get',
            url: '/front2/investment/googleAuth',
            dataType : 'json',
            data: "",
            success:function(resp) {
                var secret = resp['secret'] ;
                var googleAuthUrl = resp['googleAuthUrl'];
                $('#googleAuthUrl').attr('src',googleAuthUrl);
                $('#key').val(secret);

            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    }
    /* secret key copy */
    function copyTxt(){
        var copyText = document.getElementById("key");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
    }
    /* OTP submit check */
    function otpSubmitCheck(type){
        var chk1 = false, chk2 = false;
       /* if(type == 'all' || type == 'check'){
            if($('#check').is(':checked') != true){
                $('#checkbox_msg').show();
                chk1 = false;
                return;
            } else {
                $('#checkbox_msg').hide();
                chk1 = true;
            }
        }*/
       /* if(type == 'all' || type == 'authcode'){
            var text = $('#authcode').val().replace(/[^0-9]/g,'');
            $('#authcode').val(text);
            if($('#authcode').val().length < 6){
                $('#six_msg').show();
                chk2 = false;
                return;
            } else {
                $('#six_msg').hide();
                chk2 = true;
            }
        }*/
        /*if(type == 'all' && chk1 != false && chk2 != false){
            otpSubmit();
        }*/

        otpSubmit();
    }
    /* OTP submit */
    function otpSubmit(){
        var authcode = $('#authcode').val();
        $.ajax({
            type: 'post',
            dataType : 'json',
            url: '/front2/investment/otpAuthOk',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data: {
                "authcode" : authcode
            },
            success:function(resp) {
                var status = resp['status'];
                var message = resp['message'];
                if(status == 'true'){
                    $("#modalGoogleAuth").modal('hide');
                    applicationSumbitCheck();
                    //$("#myModal").modal('show');
                } else {
                    var text;
                    if(message == 'invalidCode'){
                        text = "<?=__('You entered invalid authentication code')?>";
                    } else if (message == 'enterCode'){
                        text = "<?=__('Please enter authentication code')?>";
                    }
                    $('#error_msg').html(text);
                    $('#error_msg').show();
                }
            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    }

</script>