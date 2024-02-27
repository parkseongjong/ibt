
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {box-sizing: border-box;cursor: pointer;display: block;height: 35px;user-select: none;-webkit-user-select: none;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'fees']);  ?>"><?= __('Fees Details');?> </a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'loandepositfees']);  ?>"><?= __('Loan Deposit Fees Details');?> </a></li>
        </ol>
    </section>


    <div class="inner_content_w3_agile_info" >
        <div class="agile-tables">
            <div class="w3l-table-info agile_info_shadow_menu" >
                <nav class="nav navbar-default" id="myTab" style="width: fit-content; height: fit-content;background: transparent;margin-bottom: 1%">
                    <div class="container" >
                        <ul class = "tab_menu">
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'fees']);  ?>"><?= __('Buy Fees') ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'sellfees']);  ?>"><?= __('Sell Fees') ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'accounttransferfees']);  ?>"><?= __('Internal Account Transfer Fees') ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawfees']);  ?>"><?= __('Withdrawal Fees') ?></a>
                            </li>
                            <li class="on">
                                <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'loandepositfees']);  ?>"><?= __('Loan Deposit Fees') ?></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">

                    <div class="clearfix"></div>
                    <form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask">
                        <div class="form-group">

                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$usersFindList,'id'=>"search_username",'value'=>(!empty($_GET['user_name']) ? $_GET['user_name'] : ""))); ?>
                            </div>
                            <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true,'value'=>(!empty($_GET['coin_first_id']) ? $_GET['coin_first_id'] : "")));?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>

                            </div>

                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
                        </div>
                        <!-- Token Button List -->
                        <div class="token-button-list-checkbox-container">
                            <label for=""><?= __('View in scroll form');?></label>
                            <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
                        </div>
                        <div class="token-button-list-container">
                            <?php foreach($coinsList as $k=>$data) {
                                echo $this->Form->submit($data['short_name'], array('value' => $data['id'], 'id'=>$k, 'name' => 'btn_submit', 'class' => 'btn btn-token'));
                            } ?>
                        </div>
                        <!-- /Token Button List -->
                    </form>

                    <div class="dropdown" style="margin-bottom: 20px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export');?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>


                    <div id="transferHistory" class="mt10 table-responsive">

                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Coin')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Transaction Type')?></th>
                                <th style="color:#fff"><?= __('Fees')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>
                            </tr>

                            <thead>
                            <tbody id="transferHistoryList">

                            <?php
                            $count= $serial_num;

                            foreach($listing->toArray() as $k=>$data){

                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>">
                                <tr class="<?=$class?>">
                                    <td> <?php echo $data['id']; ?></td>
                                    <td> <?php echo $data['user_id']; ?></td>
                                    <td> <?php echo $data['user']['name']; ?></td>
                                    <td> <?php echo $data['user']['phone_number']; ?></td>
                                    <td> <?php echo $data['cryptocoin']['short_name']; ?></td>
                                    <td><?php echo number_format((float)$data['amount'],2);?> </td>
                                    <td> <?php $type = $data['type'];
                                        if($type == 'loan_deposit'){
                                            echo __('Loan Deposit');
                                        }?> </td>
                                    <td> <?php echo number_format((float)$data['fees'],2);?> </td>
                                    <td> <?php echo __(ucfirst($data['status']));?> </td>
                                    <td><?=$data['created_at']->format('d M Y H:i:s');?> </td>

                                </tr>
                                </tr>
                                <?php $count++; } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        <?php
                        $searchArr = [];
                        foreach($this->request->query as $singleKey=>$singleVal){
                            $searchArr[$singleKey] = $singleVal;
                        }

                        $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'loandepositfees')+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__("First"));

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            echo $paginator->prev(__("Prev"));
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 2));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            echo $paginator->next(__("Next"));
                        }

                        // the 'last' page button
                        echo $paginator->last(__("Last"));

                        echo "</div>";

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    function export_f(v) {
        $('#export').val(v);
        $("form").submit();
        $('#export').val('');
    }

    function getCoinID(coinName){
        let coinID = 0;
        switch (coinName){
            case "BTC":
                coinID = 1;
                break;
            case "USDT":
                coinID = 5;
                break;
            case "TP3":
                coinID = 17;
                break;
            case "ETH":
                coinID = 18;
                break;
            case "MC":
                coinID = 19;
                break;
            case "KRW":
                coinID = 20;
                break;
            case "CTC":
                coinID = 21;
                break;
            case "XRP":
                coinID = 23;
                break;
            case "BNB":
                coinID = 27;
                break;
            default:
                coinID = 0;
        }
        return coinID;
    }

    function getUserInfo(id){
        $("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td><td colspan="2">&nbsp;</td></tr>');
        $.ajax({
            url: "<?php echo $this->Url->build(['controller'=>'transactions','action'=>'loandepositfeesajax']); ?>/",
            type:'POST',
            data: {id:id},
            dataType: 'JSON',
            success: function(resp) {
                if (resp.success === "false") {

                } else {
                    // alert("name: "+resp.data.user.name);
                    var getData = resp.data;
                    var html = '';
                    html = html + '<tr>';
                    html = html + '<td>' + getData.id + '</td>';
                    html = html + '<td>' + getData.user_id + '</td>';
                    html = html + '<td>' + getData.user.name + '</td>';
                    html = html + '<td>' + getData.user.phone_number + '</td>';
                    html = html + '<td>' + getData.cryptocoin.short_name + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(getData.amount).toFixed(2)) + '</td>';
                    html = html + '<td>' + getData.cryptocoin.short_name + '</td>';
                    html = html + '<td>' + getData.tx_type + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(getData.fees).toFixed(2)) + '</td>';
                    html = html + '<td>' + getData.status + '</td>';
                    var splitDateTime = getData.created_at;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T"," ");
                    html = html + '<td>' + getdateTime + '</td>';

                    html = html + '</tr>';

                    $('tbody').html(html);
                    $("#transferHistory").hide();
                }
            },
            error: function (e) {

                $("#ajax_coin_tr").hide();
                //$("#model_qr_code_flat").hide();
            }
        });
    }


    function getUserNameInfo(userId){
        $("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td><td colspan="2">&nbsp;</td></tr>');
        $.ajax({
            url: "<?php echo $this->Url->build(['controller'=>'transactions','action'=>'loandepositfeesajaxname']); ?>/",
            data: {user_id:userId},
            type:'POST',
            dataType: 'JSON',
            success: function(resp) {
                var html = '';
                $.each(resp.data.userlist,function(resKey,respVal) {
                    html = html + '<tr>';
                    html = html + '<td>' + respVal.id + '</td>';
                    html = html + '<td>' + respVal.userId + '</td>';
                    html = html + '<td>' + respVal.userName + '</td>';
                    html = html + '<td>' + respVal.phone + '</td>';
                    html = html + '<td>' + respVal.coin + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(respVal.amount).toFixed(2)) + '</td>';
                    html = html + '<td>' + respVal.type + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(respVal.fees).toFixed(2)) + '</td>';
                    html = html + '<td>' + respVal.status + '</td>';
                    var splitDateTime = respVal.created;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T", " ");
                    html = html + '<td>' + getdateTime + '</td>';

                    html = html + '</tr>';
                });

                $('tbody').html(html);
                $("#transferHistory").hide();
            },
            error: function (e) {
                $("#ajax_coin_tr").hide();
                //$("#model_qr_code_flat").hide();
            }
        });
    }

    $('document').ready(function(){

        let clicked = localStorage.getItem('clicked');

        if(clicked === "Yes"){
            let coinClick = localStorage.getItem('coin');
            var btn = localStorage.getItem('id');
            let coinID = getCoinID(coinClick);
            if(coinID !== 0){
                $("#coin_first_id").select2().val(coinID).trigger("change");
                $("#"+btn+"").addClass('selected');
                localStorage.removeItem('coin');
                localStorage.removeItem('clicked');
                localStorage.removeItem('id');
            }

        }

        $('#start-date').datepicker({

            format: 'yyyy-mm-dd',
            maxDate: '0'

        });
        $('#end-date').datepicker({

            format: 'yyyy-mm-dd',
            maxDate: '0'

        });
        $("#transferList").hide();


        $("#search_username").select2();

        $("#coin_first_id").select2();

        tokenButtonList();

        $('.btn-token').click(function (){
            let coin = $(this).val();
            let coinId = this.id;
            let result = "Yes";
            localStorage.setItem("coin", coin);
            localStorage.setItem("clicked", result);
            localStorage.setItem('id', coinId);
            $("#coin_first_id").select2().val(getCoinID(coin));
        });

    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
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

</script>
