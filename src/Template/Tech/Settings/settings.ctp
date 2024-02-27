<style>
    .input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
    .open > .dropdown-menu {
        display: block;
        height: 400px;
        overflow-y: scroll;
    }
    .modal-dialog {
        width: 80%;
        margin: 30px auto;
    }cc
</style>
<div class="content-wrapper" style="overflow: auto;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'settings']);  ?>"> <?= __('Settings');?></a> </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a>
            </li>
            <li class="active">
                <a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'settings']);  ?>"><?= __('Settings');?></a>
            </li>
        </ol>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 1:');?></h3>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    $("#first_setting").submit(function(e){
                                        e.preventDefault();
                                        $("#first_setting_loader").show();
                                        $.ajax({
                                            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting']) ?>",
                                            type:'POST',
                                            data:$("#first_setting").serialize(),
                                            dataType:'JSON',
                                            beforeSend: function(xhr){
                                                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                                            },
                                            success:function(resp){
                                                $("#first_setting_loader").hide();
                                                $("#first_setting_resp").html(resp.message).show();
                                                setTimeout(function(){ $("#first_setting_resp").hide(); },5000);
                                            }
                                        });
                                    });

                                    $("#coinpair_id").change(function(){
                                        let getVal = $(this).val();
                                        $.ajax({
                                            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting_get']) ?>/"+getVal,
                                            beforeSend: function(xhr){
                                                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                                            },
                                            type:'GET',
                                            dataType:'JSON',
                                            success:function(resp){
                                                console.log(resp);
                                                let getData = resp.data;
                                                $("#upBuySellPercentage").val(getData.max_buysell_per);
                                                $("#downBuySellPercentage").val(getData.min_buysell_per);
                                                $("#upMarketPercentage").val(getData.max_market_per);
                                                $("#downMarketPercentage").val(getData.min_market_per);
                                                //$("#first_setting_loader").hide();
                                                //$("#first_setting_resp").html(resp.message).show();
                                                //setTimeout(function(){ $("#first_setting_resp").hide(); },5000);
                                            }
                                        });
                                    });
                                });
                            </script>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <?php echo $this->Form->create('Coinpair',array('url'=>'/tech/settings/numonesetting','method'=>'post','enctype'=>'multipart/form-data',"id"=>"first_setting"));?>
                                <div class="col-md-10 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <div style="display:none;" class="alert alert-success" id="first_setting_resp"></div>
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <span style="margin-left: 15%;"> <?= __('Coin: ');?> </span>
                                                </td>
                                                <!--                                                <td>-->
                                                <!--                                                    <span style="margin-left: 20%;"> Coin Second : </span>-->
                                                <!--                                                </td>-->
                                                <td>
                                                    <span style="margin-left: 20%;"> <?= __('Period: ');?> </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;<?= __('Up Buy/Sell %: ');?> </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;&nbsp;<?= __('Down Buy/Sell %: '); ?> </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;<?= __('Up Prev. Close Market %: '); ?> </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;&nbsp;<?= __('Down Prev. Close Market %: '); ?> </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  //echo $this->Form->input('first_coinList',array('id'=>'first_coinList','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                    <select name="coinpair_id" id="coinpair_id" class="form-control input-style required" style="width:100%; display: inline-block" >
                                                        <option value=""><?= __('Please select coin');?></option>
                                                        <?php foreach($coinpairList as $coinpairSingle){ ?>
                                                            <option value="<?php echo $coinpairSingle["id"]; ?>"><?php echo $coinpairSingle["cryptocoin_first"]["short_name"]."/".$coinpairSingle["cryptocoin_second"]["short_name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <!--                                               <td>-->
                                                <!--                                                   --><?php // echo $this->Form->input('second_coinList',array('id'=>'second_coinList','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'margin-left: 10%; width:20%, display: inline-block'));?>
                                                <!--                                               </td>-->
                                                <td>
                                                    <span style="margin-left: 10%;display: inline-block"> <?= __('Exchange 1 day');?> </span>
                                                </td>
                                                <td>
                                                    <?php echo $this->Form->input('max_buysell_per',['type'=>'text','id'=>'upBuySellPercentage','style'=>'width:40%;margin-left:15%','label'=>false]); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->Form->input('min_buysell_per',['type'=>'text','id'=>'downBuySellPercentage','style'=>'width:40%;margin-left:15%','label'=>false]); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->Form->input('max_market_per',['type'=>'text','id'=>'upMarketPercentage','style'=>'width:40%;margin-left:15%','label'=>false]); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->Form->input('min_market_per',['type'=>'text','id'=>'downMarketPercentage','style'=>'width:40%;margin-left:15%','label'=>false]); ?>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id'=>'btn_save_one','type' => 'submit','class'=>'btn btn-primary', 'style'=>'margin-left: 50%;']); ?>
                                                    <img style="display:none;" id="first_setting_loader" src="/ajax-loader.gif" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow" data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 2:') ;?></h3>
                            </div>
                            <div class="alert alert-danger" style="display:none;" id="number_two_error"></div>
                            <div class="alert alert-success"  style="display:none;" id="number_two_success"></div>
                            <div>
                                <p>← 트레이딩에서 메인으로 보낼경우 제한값</p>
                                <p>→ 메인에서 트레이딩으로 보낼경우 제한값</p>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <img style="display:none;" id="number_two_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('method'=>'post','enctype'=>'multipart/form-data','onsubmit'=>"return false;","novalidate")); ?>
                                <div class="col-md-12 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <span><?= __('ALL USERS: ');?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>&nbsp;
                                                </td>
                                                <td>&nbsp;
                                                </td>
                                                <td>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'one_day_alluser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Day: ');?> </span>
                                                </td>
                                                <td>
                                                    <input type="text" id="one_day_alluser_main_to_trading_transfer_amount" placeholder="500" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" id="one_day_alluser_trading_to_main_transfer_amount" placeholder="500" style="width:100%;margin-left: 10px;"/>

                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_day_alluser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
                                                </td>
                                                <td>
                                                    <div id="num_two_daily_all_user" class="btn btn-info m-l-5"><?= __('View');?></div>
                                                </td>
                                                <td>
                                                    <div id="num_two_daily_all_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'month_day_alluser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> Main Account 30 Day: </span>
                                                </td>
                                                <td>
                                                    <input type="text" id="month_day_alluser_main_to_trading_transfer_amount" placeholder="500" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" id="month_day_alluser_trading_to_main_transfer_amount" placeholder="500" style="width:100%;margin-left: 10px;"/>

                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'month_day_alluser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
                                                </td>
                                                <td>
                                                    <div id="num_month_two_daily_all_user" class="btn btn-info m-l-5"><?= __('View');?></div>
                                                </td>
                                                <td>
                                                    <div id="num_month_two_daily_all_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'one_month_alluser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Year: ');?> </span>
                                                </td>
                                                <td>
                                                    <input type="text"  id="one_month_alluser_main_to_trading_transfer_amount" placeholder="500" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text"  id="one_month_alluser_trading_to_main_transfer_amount" placeholder="500" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_month_alluser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
                                                </td>
                                                <td>
                                                    <span id="num_two_monthly_all_user" class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>
                                                    <span id="num_two_monthly_all_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-top: 20px;padding-bottom: 10px;">
                                                    <?= __('GENERAL USERS: ');?>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr style="padding: 2%; border-spacing: 0 15px; border-collapse: separate;">
                                                <td>
                                                    <?php  echo $this->Form->input('search_users',array("id"=>"user_id",'class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","options"=>[''=>__('Please select user')],"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr style="margin-top: 10%;padding: 2%;">
                                                <td style="margin-top: 10%; padding-top: 2%;">
                                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'one_day_singleuser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>

                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Day: ');?> </span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_singleuser_main_to_trading_transfer_amount' style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_singleuser_trading_to_main_transfer_amount' style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_day_singleuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
                                                </td>
                                                <td>
                                                    <span id="num_two_daily_single_user" class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>
                                                    <span id="num_two_daily_single_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>
                                            <!-- 개인 사용자 한달 -->
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr style="margin-top: 10%;padding: 2%;">
                                                <td style="margin-top: 10%; padding-top: 2%;">
                                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'month_day_singleuser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>

                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> Main Account 30 Day: </span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="500" id='month_day_singleuser_main_to_trading_transfer_amount' style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id='month_day_singleuser_trading_to_main_transfer_amount' style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'month_day_singleuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
                                                </td>
                                                <td>
                                                    <span id="num_month_two_daily_single_user" class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>
                                                    <span id="num_month_two_daily_single_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array("id"=>"one_month_singleuser_cryptocoin_id",'class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Year: ');?> </span>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_singleuser_main_to_trading_transfer_amount" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_singleuser_trading_to_main_transfer_amount" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_month_singleuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>

                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_user" class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_user_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-top: 15px;">
                                                    <?= __('ALL ANNUAL MEMBERSHIP USERS: ');?>  <?php  echo $this->Form->checkbox('check_users',array("id"=>"annuser_all",'checked'=>false,'style'=>'margin-left:6px;'));?>
                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'one_day_single_annuser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>

                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Day: ');?> </span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_single_annuser_main_to_trading_transfer_amount' style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_single_annuser_trading_to_main_transfer_amount' style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_day_single_annuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>

                                                </td> <td>

                                                    <span id="num_two_daily_single_annuser"  class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>

                                                    <span id="num_two_daily_single_annuser_cancel"  class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                    <span style="margin-left: 10px;"> &nbsp;&nbsp;← </span>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 25px;"> → </span>
                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array("id"=>"one_month_single_annuser_cryptocoin_id",'class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Main Account 1 Year: ');?> </span>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_single_annuser_main_to_trading_transfer_amount" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_single_annuser_trading_to_main_transfer_amount" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['id' => 'one_month_single_annuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>

                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_annuser" class="btn btn-info m-l-5"><?= __('View');?></span>
                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_annuser_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 3:');?></h3>
                            </div>
                            <div class="alert alert-danger" style="display:none;" id="number_three_error"></div>
                            <div class="alert alert-success"  style="display:none;" id="number_three_success"></div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <img style="display:none;" id="number_three_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('method'=>'post','enctype'=>'multipart/form-data',"id"=>"number_three_form")); ?>
                                <div class="col-md-10 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent" style="border-collapse: separate;border-spacing: 0 15px;">
                                            <tr style="margin-bottom: 20px;">
                                                <td>
                                                    <?php  echo $this->Form->input('user_id',array('class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","options"=>[''=>__('Please select user')],"required"=>true, 'style' => 'width:100%; display: inline-block;',"id"=>"no_three_user_id"));?>
                                                </td>
                                                <td>
                                                    <input type="text" id="user_category" placeholder="<?= __('User Category');?>" style="width:70%; display: inline-block; margin-left:10%;" readonly/>
                                                    <!--                                                    --><?php // echo $this->Form->input('level_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>"Select User Type"]+$levelList,"required"=>true,'style' => 'width:100%; display: inline-block; margin-left:10px;')); ?>
                                                </td>
                                                <td>
                                                    <?= __('Select Period:');?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('cryptocoin_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block',"id"=>"no_three_coin_id"));?>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10%;"> <?= __("Users' Fees: ");?> </span>
                                                    <input type="text" placeholder="0.25" name="no_three_user_fee" style="width:40%;" id="no_three_user_fee" />
                                                </td>

                                                <td>
                                                    <!--  <input type="text" placeholder="1day~" style="width:40%;margin-left: 10%;"/> -->
                                                    <?php echo $this->Form->input('days',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>__("Please select time period"),"1"=>__("1 Day"),"7"=>__("1 Week"),"15"=>__("15 Days"),"30"=>__("1 Month"),"90"=>__("3 Months"),"180"=>__("6 Months")],"required"=>true,'style' => 'width:100%; display: inline-block;')); ?>
                                                </td>

                                                <td>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="content" class="table-layout" >
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " >
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 4:');?></h3>
                            </div>
                            <div class="alert alert-danger" style="display:none;" id="number_four_error"></div>
                            <div class="alert alert-success"  style="display:none;" id="number_four_success"></div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <img style="display:none;" id="number_four_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('id'=>'number_four_setting','method'=>'post','enctype'=>'multipart/form-data')); ?>
                                <div class="col-md-11 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent" style="border-collapse: separate;border-spacing: 0 15px;">
                                            <tr style="padding: 5px;">
                                                <td >
													<span style="margin-left: 10px;display: inline-block;"> <?= __('ALL USERS: ');?>
													</span>
                                                </td>
                                                <!--													<td >-->
                                                <!--													-->
                                                <!--														  --><?php // echo $this->Form->input('number_four_user_id',array('id'=>'number_four_user_id','class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","options"=>[''=>'Select User']+$userFindList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                <!--														-->
                                                <!--													</td>-->
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
														<span style="margin-left: 10px;display: inline-block;"> <?= __('Coin: ');?>
														</span>
                                                </td>
                                                <td >
                                                    <?php  echo $this->Form->input('number_four_coin_id',array('class' => 'form-control input-style required','label' =>false,'id' =>'number_four_coin_id',"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block'));?>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Deposit Fees %: ');?> </span>
                                                </td>
                                                <td >
                                                    <input type="text" placeholder="0.25" id="number_four_deposit_fee" name="number_four_deposit_fee"/>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Withdrawal Fees %: ');?> </span>
                                                </td>
                                                <td >
                                                    <input type="text" placeholder="0.25" id="number_four_withdrawal_fee" name="number_four_withdrawal_fee"/>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Trading Account → Main Account Transfer Fees %: ');?> &nbsp;&nbsp;</span>
                                                </td>
                                                <td >
                                                    <input type="text" placeholder="0.25" id="number_four_trading_to_main_transfer_fee" name="number_four_trading_to_main_transfer_fee"/>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Main Account → Trading Account Transfer Fees %: ');?> &nbsp;&nbsp;</span>
                                                </td>
                                                <td >
                                                    <input type="text" placeholder="0.25" id="number_four_main_to_trading_transfer_fee" name="number_four_main_to_trading_transfer_fee"/>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Loan Deposit Fees %: ');?> </span>
                                                </td>
                                                <td >
                                                    <input type="text" placeholder="0.25" id="number_four_load_deposit_fee" name="number_four_load_deposit_fee"/>
                                                </td>
                                            </tr>
                                            <tr style="padding: 5px;">
                                                <td >
                                                    <span style="margin-left: 10px;display: inline-block;"> <?= __('Buy/Sell Fees %: ');?> </span>
                                                </td>
                                                <td >
                                                    <select name="number_four_buy_sell_fee_coinpair_id" id="number_four_buy_sell_fee_coinpair_id" class="form-control input-style required" style="width:20%, display: inline-block" >
                                                        <option value=""><?= __('Please select coin');?></option>
                                                        <?php foreach($coinpairList as $coinpairSingle){ ?>
                                                            <option value="<?php echo $coinpairSingle["id"]; ?>"><?php echo $coinpairSingle["cryptocoin_first"]["short_name"]."/".$coinpairSingle["cryptocoin_second"]["short_name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="text" placeholder="0.25" id="number_four_buy_sell_fee" name="number_four_buy_sell_fee"/>
                                                </td>
                                            </tr>
                                        </table>
                                        <tr>
                                            <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary']); ?>
                                        </tr>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 5:');?></h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <?php echo $this->Form->create("form",array('method'=>'post','enctype'=>'multipart/form-data')); ?>
                                <div class="col-md-10 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __("Users' Fees: ");?> </span>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="0.25" style="width:50px;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="<?= __('1 day~');?>" style="width:50px;margin-left: 10px;"/>
                                                </td>
                                                <!--                                                <td>-->
                                                <!--                                                    --><?php // echo $this->Form->input('coin_first_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:90%; display: inline-block; margin-left:20px'));?>
                                                <!--                                                </td>-->
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Level');?> </span>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->input('level_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>__("Select level")]+$levelList,"required"=>true,'style' => 'width:100%; display: inline-block;')); ?>
                                                </td>
                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                    </div>
                                    </td>
                                    </tr>
                                    </table>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 6: Coupon Settings');?></h3>
                            </div>
                            <div class="alert alert-danger" style="display:none;" id="number_six_error"></div>
                            <div class="alert alert-success"  style="display:none;" id="number_six_success"></div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <img style="display:none;" id="number_six_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('id'=>'number_six_setting','method'=>'post','enctype'=>'multipart/form-data')); ?>
                                <div class="col-md-10 col-md-offset-1">

                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coupons_cryptocoin_id',array('id' => 'coupons_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Coupon Amount');?> </span>
                                                </td>
                                                <td>
                                                    <input name="coupon_amount" id="coupon_amount" type="text" placeholder="5000" style="width:40%;"/>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px; margin-left: -70px;"> <?= __('Coupon Monthly Limit');?> </span>
                                                </td>
                                                <td>
                                                    <input name="coupon_limit" id="coupon_limit" type="text" placeholder="2,000,000 KRW" style="width:60%;"/>
                                                </td>
                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                                </td>
                                                <td>
                                                    <div id="num_six_view" class="btn btn-info m-l-5"><?= __('View');?></div>
                                                </td>
                                                <td>
                                                    <div id="num_six_cancel" class="btn btn-danger m-l-5"><?= __('Cancel');?></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('No. 7: Withdrawal % Settings');?></h3>
                            </div>
                            <div class="alert alert-danger" style="display:none;" id="number_seven_error"></div>
                            <div class="alert alert-success"  style="display:none;" id="number_seven_success"></div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <img style="display:none;" id="number_seven_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('id'=>'number_seven_setting','method'=>'post','enctype'=>'multipart/form-data')); ?>
                                <div class="col-md-10 col-md-offset-1">

                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <span style="padding: 10px;"> <?= __('Withdrawal Percentage: ');?> </span>
                                                </td>

                                                <td>
                                                    <?php  echo $this->Form->input('percentage',array('placeholder'=>'%','type'=>'text', 'label' => false, 'style'=>'width:40%;', 'value'=>(!empty($percentage) ? $percentage : "" ))); ?>
                                                </td>

                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                                </td>
                                                <td>

                                                    <div id="num_seven_view" class="btn btn-info m-l-5" onclick="showNumberSevenSettingData();"><?= __('View');?></div>
                                                </td>
                                                <td>

                                                    <div id="num_seven_cancel" class="btn btn-danger m-l-5" onclick="numberSevenSettingCancel();"><?= __('Cancel');?></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">불필요 로그인 세션 데이터 삭제</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-offset-10 form-group valid-form m-t-15">
                                        <button type="button" onclick="delete_login_sesseion()" class="btn btn-primary">삭제</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">거래소 점검</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-offset-10 form-group valid-form m-t-15">
                                        <table class="table">
                                            <tr>
                                                <th>메세지</th>
                                                <th>상태</th>
                                                <th>생성일</th>
                                                <th>수정일</th>
                                                <th>마지막 작업자</th>
                                                <th>수정</th>
                                                <th>삭제</th>
                                                <th>동작</th>
                                            </tr>
                                            <tbody id="server_check_list">
                                            </tbody>
                                            <tr id="add_area" style="display:none;">
                                                <td colspan="7">
                                                    <input type="text" id="server_check_msg" name="server_check_msg" value="" class="form-control input-style" placeholder="점검 안내 메세지를 입력해주세요">
                                                </td>
                                                <td><button type="button" onclick="add_server_check()" class="btn btn-info btn-xs">추가</button></td>
                                            </tr>
                                        </table>
                                        <button type="button" id="open_add_area_btn" onclick="open_add_area()" class="btn btn-primary">추가</button>
                                        <button type="button" id="close_add_area_btn" onclick="close_add_area()" class="btn btn-danger" style="display:none;">취소</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- 추가 보관함 제한 컨트롤러 -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation " style="overflow: auto;">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">보관함 제한</h3>
                            </div>
                            <div>
                                코인 명
                                <p>
                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'safe_coin','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                </p>
                            </div>
                            <div>
                                보관함에 넣을수 있는 양
                                <input type="text" name="safe_in" id="safe_in">
                                <button type="button" id="in_safe" onclick="in_safe()" class="btn btn-primary">저장</button>
                                <button type="button" id="in_safe_list" class="btn btn-primary">보관함 제한 리스트</button>
                                보관함에 뺄수 있는 양
                                <input type="text" name="safe_out" id="safe_out">
                                <button type="button" id="out_safe" onclick="out_safe()" class="btn btn-primary">저장</button>
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>


</div>
<script>
    $(document).ready(function(){
        get_server_check_list();
    });


    function in_safe(){
        const safe_value = $("#safe_in").val();
        const coin_id = $("#safe_coin").val();
        //ajax 처리 페이지로 저장 후 데이터 보관


        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'safeininsert']) ?>",
            type:'POST',
            data : {
                "safein" : safe_value,
                "coinname" : coin_id,
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp == 'success'){
                    /*alert("성공");*/
                    alert(resp);
                } else {
                    alert(resp);
                }
            }
        });

    }

    function out_safe(){
        const safe_value = $("#safe_out").val();
        const coin_id = $("#safe_coin").val();
        //ajax 처리 페이지로 저장 후 데이터 보관


        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'safeoutinert']) ?>",
            type:'POST',
            data : {
                "safein" : safe_value,
                "coinname" : coin_id,
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp == 'success'){
                    /*alert("성공");*/
                    alert(resp);
                } else {
                    alert(resp);
                }
            }
        });
    }

    /* 서버 점검 메세지 추가 */
    function add_server_check(){
        if(confirm('추가하시겠습니까?')){
            if($('#server_check_msg').val() == ''){
                alert('메세지를 입력해주세요');
                return;
            }
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'addServerCheckMsg']) ?>",
                type:'POST',
                data : {
                    "message" : $('#server_check_msg').val(),
                },
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success:function(resp){
                    if(resp == 'success'){
                        close_add_area();
                        get_server_check_list();
                    } else {
                        alert(resp);
                        return;
                    }
                }
            });
        }
    }
    /* 서버 점검 메세지 수정 */
    function edit_server_check(id){
        const edit_msg = $('#message_'+id).val();
        if(confirm('수정하시겠습니까?')){
            if(edit_msg == ''){
                alert('메세지를 입력해주세요');
                return;
            }
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'editServerCheckMsg']) ?>",
                type:'POST',
                data : {
                    "message" : edit_msg,
                    "id" : id,
                },
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success:function(resp){
                    alert(resp);
                    get_server_check_list();
                }
            });
        }
    }
    /* 서버 점검 메세지 삭제 */
    function delete_server_check(id){
        if(confirm('삭제하시겠습니까?')){
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'deleteServerCheckMsg']) ?>",
                type:'POST',
                data : {
                    "id" : id,
                },
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success:function(resp){
                    alert(resp);
                    get_server_check_list();
                }
            });
        }
    }
    /* 서버 점검 실행 */
    function server_check(id, type){
        if(confirm('실행하시겠습니까?')){
            let send_status = 'Y';
            if(type == 'Y'){
                send_status = 'N';
            }
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'statusServerCheckMsg']) ?>",
                type:'POST',
                data : {
                    "id" : id,
                    "status" : send_status,
                },
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success:function(resp){
                    alert(resp);
                    get_server_check_list();
                }
            });
        }
    }
    /* 서버 점검 메세지 리스트 */
    function get_server_check_list(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'getServerCheckMsg']) ?>",
            type:'POST',
            dataType : 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                $('#server_check_list').html('');
                if(resp.length > 0){
                    let str = make_html_list(resp);
                    $('#server_check_list').html(str);
                }
            }
        });
    }
    /* 서버 점검 메세지 추가 영역 오픈 */
    function open_add_area(){
        $('#add_area').show();
        $('#close_add_area_btn').show();
        $('#open_add_area_btn').hide();
        $('#server_check_msg').focus();
    }
    /* 서버 점검 메세지 추가 영역 클로즈 */
    function close_add_area(){
        $('#add_area').hide();
        $('#close_add_area_btn').hide();
        $('#server_check_msg').val('');
        $('#open_add_area_btn').show();
    }
    /* html 리스트 만들기 */
    function make_html_list(obj){
        let tbl_html = '';
        $.each(obj,function(key,value){
            const id = value.id;
            const message = value.message;
            const created = value.created != null ? value.created.split("+")[0].replace("T"," ") : '' ;
            const updated = value.updated != null ? value.updated.split("+")[0].replace("T"," ") : '' ;
            const last_admin = value.last_admin;
            let status = '정지';
            let action_btn = '점검시작'
            const type = "'"+value.status+"'";
            if(value.status == 'Y'){
                status = '점검중';
                action_btn = '중지';
            }

            tbl_html += '<tr><td><input type="text" id="message_'+id+'" value="'+message+'" class="form-control input-style"></td>';
            tbl_html += '<td><button type="button" class="btn btn-primary btn-xs" disabled >'+status+'</button></td>';
            tbl_html += '<td>'+created+'</td>';
            tbl_html += '<td>'+updated+'</td>';
            tbl_html += '<td>'+last_admin+'</td>';
            tbl_html += '<td><button type="button" onclick="edit_server_check('+id+')" class="btn btn-info btn-xs">수정</button></td>';
            tbl_html += '<td><button type="button" onclick="delete_server_check('+id+')" class="btn btn-danger btn-xs">삭제</button></td>';
            tbl_html += '<td><button type="button" onclick="server_check('+id+','+type+')" class="btn btn-primary btn-xs">'+action_btn+'</button></td></tr>';
        });
        return tbl_html;
    }
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="model_content">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close');?></button>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function getValue(ele){
        return ele.value;
    }
    function delete_login_sesseion(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'deleteLoginSession']) ?>",
            type:'POST',
            data : {},
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                console.log(resp);
            }
        });
    }
    $(document).ready(function() {
        user_search_select2('user_id'); /* user name search */
        user_search_select2('no_three_user_id'); /* user name search */

        $("#annuser_all").prop('checked',false);
        $("#one_day_single_annuser_cryptocoin_id").prop('disabled', true).attr('disabled','disabled');
        $("#one_day_single_annuser_main_to_trading_transfer_amount").prop('disabled', true).attr('disabled','disabled');
        $("#one_day_single_annuser_trading_to_main_transfer_amount").prop('disabled', true).attr('disabled','disabled');
        $("#one_day_single_annuser_save_btn").prop('disabled', true).attr('disabled','disabled');
        $("#num_two_daily_single_annuser").prop('disabled', true).attr('disabled','disabled');
        $("#num_two_daily_single_annuser_cancel").prop('disabled', true).attr('disabled','disabled');
        $("#one_month_single_annuser_cryptocoin_id").prop('disabled', true).attr('disabled','disabled');
        $("#one_month_single_annuser_main_to_trading_transfer_amount").prop('disabled', true).attr('disabled','disabled');
        $("#one_month_single_annuser_trading_to_main_transfer_amount").prop('disabled', true).attr('disabled','disabled');
        $("#one_month_single_annuser_save_btn").prop('disabled', true).attr('disabled','disabled');
        $("#num_two_monthly_single_annuser").prop('disabled', true).attr('disabled','disabled');
        $("#num_two_monthly_single_annuser_cancel").prop('disabled', true).attr('disabled','disabled');

        $('#annuser_all').on('change',function (){
            if($(this).prop('checked') === true){
                let values = "Y";
                $("#one_day_single_annuser_cryptocoin_id").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_day_single_annuser_main_to_trading_transfer_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_day_single_annuser_trading_to_main_transfer_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_day_single_annuser_save_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#num_two_daily_single_annuser").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#num_two_daily_single_annuser_cancel").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_month_single_annuser_cryptocoin_id").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_month_single_annuser_main_to_trading_transfer_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_month_single_annuser_trading_to_main_transfer_amount").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#one_month_single_annuser_save_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#num_two_monthly_single_annuser").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $("#num_two_monthly_single_annuser_cancel").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');

            } else if($(this).prop('checked') === false){
                let  values = "N";
                $("#one_day_single_annuser_cryptocoin_id").prop('disabled', true).attr('disabled','disabled');
                $("#one_day_single_annuser_main_to_trading_transfer_amount").prop('disabled', true).attr('disabled','disabled');
                $("#one_day_single_annuser_trading_to_main_transfer_amount").prop('disabled', true).attr('disabled','disabled');
                $("#one_day_single_annuser_save_btn").prop('disabled', true).attr('disabled','disabled');
                $("#num_two_daily_single_annuser").prop('disabled', true).attr('disabled','disabled');
                $("#num_two_daily_single_annuser_cancel").prop('disabled', true).attr('disabled','disabled');
                $("#one_month_single_annuser_cryptocoin_id").prop('disabled', true).attr('disabled','disabled');
                $("#one_month_single_annuser_main_to_trading_transfer_amount").prop('disabled', true).attr('disabled','disabled');
                $("#one_month_single_annuser_trading_to_main_transfer_amount").prop('disabled', true).attr('disabled','disabled');
                $("#one_month_single_annuser_save_btn").prop('disabled', true).attr('disabled','disabled');
                $("#num_two_monthly_single_annuser").prop('disabled', true).attr('disabled','disabled');
                $("#num_two_monthly_single_annuser_cancel").prop('disabled', true).attr('disabled','disabled');

            }
            //$.ajax({
            //    type: 'post',
            //    url: '<?//= $this->Url->build(['controller'=>'users','action'=>'profile']);  ?>//',
            //    data: {"permission_person_info":value},
            //    success:function(data){
            //
            //    }
            //});
        });

        $("#coinpair_id").change(function(){
            let getVal = $(this).val();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting_get']) ?>/"+getVal,
                type:'GET',
                dataType:'JSON',
                success:function(resp){
                    //console.log(resp);
                    let getData = resp.data;
                    $("#upBuySellPercentage").val(getData.max_buysell_per);
                    $("#downBuySellPercentage").val(getData.min_buysell_per);
                    $("#upMarketPercentage").val(getData.max_market_per);
                    $("#downMarketPercentage").val(getData.min_market_per);
                    //$("#first_setting_loader").hide();
                    //$("#first_setting_resp").html(resp.message).show();
                    //setTimeout(function(){ $("#first_setting_resp").hide(); },5000);
                }
            });
        });


        $("#one_day_alluser_cryptocoin_id").change(function(){
            let userId = 0;
            let memId = 0;
            let coin_id = $(this).val();
            let days = 1;
            numTwoSettingGet(userId,coin_id,days,"one_day_alluser_main_to_trading_transfer_amount","one_day_alluser_trading_to_main_transfer_amount");
        });

        $("#one_month_alluser_cryptocoin_id").change(function(){
            let userId = 0;
            let memId = 0;
            let coin_id = $(this).val();
            let days = 365;
            numTwoSettingGet(userId,coin_id,days,"one_month_alluser_main_to_trading_transfer_amount","one_month_alluser_trading_to_main_transfer_amount");
        });

        $("#one_day_single_annuser_cryptocoin_id").change(function(){
            let userId=1;
            let memId = 2;
            let coin_id = $(this).val();
            let days = 1;
            numTwoAnnSettingGet(coin_id,days,"one_day_single_annuser_main_to_trading_transfer_amount","one_day_single_annuser_trading_to_main_transfer_amount");
        });

        $("#one_month_single_annuser_cryptocoin_id").change(function(){
            let memId = 2;
            let coin_id = $(this).val();
            let days = 365;
            numTwoAnnSettingGet(coin_id,days,"one_month_single_annuser_main_to_trading_transfer_amount","one_month_single_annuser_trading_to_main_transfer_amount");
        });


        $("#user_id").change(function(){
            var userId = $(this).val();
            var coin_id = $("#one_day_singleuser_cryptocoin_id").val();
            var days = 1;
            numTwoSettingGet(userId,coin_id,days,"one_day_singleuser_main_to_trading_transfer_amount","one_day_singleuser_trading_to_main_transfer_amount");

            var userId = $(this).val();
            var coin_id = $("#one_month_singleuser_cryptocoin_id").val();
            var days = 365;
            numTwoSettingGet(userId,coin_id,days,"one_month_singleuser_main_to_trading_transfer_amount","one_month_singleuser_trading_to_main_transfer_amount");
        });


        $("#one_day_singleuser_cryptocoin_id").change(function(){
            let userId = $("#user_id").val();
            let coin_id = $(this).val();
            let days = 1;
            numTwoSettingGet(userId,coin_id,days,"one_day_singleuser_main_to_trading_transfer_amount","one_day_singleuser_trading_to_main_transfer_amount");
        });

        $("#one_month_singleuser_cryptocoin_id").change(function(){
            let userId = $("#user_id").val();
            let coin_id = $(this).val();
            let days = 365;
            numTwoSettingGet(userId,coin_id,days,"one_month_singleuser_main_to_trading_transfer_amount","one_month_singleuser_trading_to_main_transfer_amount");
        });

        $("#num_two_daily_all_user").click(function(){
            let userId = 0 ;
            let memId = 0;
            let coinId  = $("#one_day_alluser_cryptocoin_id").val();
            let days = 1;
            showNumberTwoSettingData(userId,coinId,days);
        });
        $("#num_two_monthly_all_user").click(function(){
            let userId = 0 ;
            let memId =0;
            let coinId  = $("#one_month_alluser_cryptocoin_id").val();
            let days = 365;
            showNumberTwoSettingData(userId,coinId,days);
        });

        //추가 모든 사용자 코인 데이터 추가

        //모든 사용자 30일 추가
        $("#month_day_alluser_save_btn").click(function(){
            let memId=0; //회원 ID
            let one_day_alluser_cryptocoin_id = $("#month_day_alluser_cryptocoin_id").val(); //30 일 기준 코인 타입
            let one_day_alluser_main_to_trading_transfer_amount = $("#month_day_alluser_main_to_trading_transfer_amount").val(); //
            let one_day_alluser_trading_to_main_transfer_amount = $("#month_day_alluser_trading_to_main_transfer_amount").val(); //
            if(one_day_alluser_cryptocoin_id==""){
                $("#number_two_error").html("<?= __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:30,
                    user_id:0,
                    cryptocoin_id:one_day_alluser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_day_alluser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_day_alluser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        })

        //모든 사용자 30일 리스트
        $("#num_month_two_daily_all_user").click(function(){
            let userId = 0 ;
            let memId = 0;
            let coinId  = $("#month_day_alluser_cryptocoin_id").val();
            let days = 30;
            showNumberTwoSettingData(userId,coinId,days);
        });

        //모든 사용자 30일 취소
        $("#num_month_two_daily_all_user_cancel").click(function(){
            let userId = 0 ;
            let memId = 0;
            let coinId  = $("#one_day_alluser_cryptocoin_id").val();
            let days = 30;
            numberTwoSettingCancel(userId,coinId,days);
        });

        $("#num_two_daily_single_user").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#one_day_singleuser_cryptocoin_id").val();
            let days = 1;
            showNumberTwoSettingData(userId,coinId,days);
        });
        $("#num_month_two_daily_single_user").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#month_day_singleuser_cryptocoin_id").val();
            let days = 30;
            showNumberTwoSettingData(userId,coinId,days);
        });
        $("#num_two_monthly_single_user").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#one_month_singleuser_cryptocoin_id").val();
            let days = 365;
            showNumberTwoSettingData(userId,coinId,days);
        });


        $("#num_two_daily_single_annuser").click(function(){
            let memId = 2;
            let coinId  = $("#one_day_single_annuser_cryptocoin_id").val();
            let days = 1;
            showNumberTwoAnnSettingData(coinId,days);
        });

        $("#num_two_monthly_single_annuser").click(function(){
            let memId = 2;
            let coinId  = $("#one_month_single_annuser_cryptocoin_id").val();
            let days = 365;
            showNumberTwoAnnSettingData(coinId,days);
        });

        $("#num_two_daily_all_user_cancel").click(function(){
            let userId = 0 ;
            let memId = 0;
            let coinId  = $("#one_day_alluser_cryptocoin_id").val();
            let days = 1;
            numberTwoSettingCancel(userId,coinId,days);

        });
        $("#num_two_monthly_all_user_cancel").click(function(){
            let userId = 0 ;
            let memId =0;
            let coinId  = $("#one_month_alluser_cryptocoin_id").val();
            let days = 365;
            numberTwoSettingCancel(userId,coinId,days);
        });
        $("#num_two_daily_single_user_cancel").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#one_day_singleuser_cryptocoin_id").val();
            let days = 1;
            numberTwoSettingCancel(userId,coinId,days);
        });
        //개인 30일 취소
        $("#month_day_singleuser_cryptocoin_id").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#one_day_singleuser_cryptocoin_id").val();
            let days = 30;
            numberTwoSettingCancel(userId,coinId,days);
        });
        $("#num_two_monthly_single_user_cancel").click(function(){
            let userId = $("#user_id").val() ;
            let coinId  = $("#one_month_singleuser_cryptocoin_id").val();
            let days = 365;
            numberTwoSettingCancel(userId,coinId,days);
        });

        $("#num_two_daily_single_annuser_cancel").click(function(){
            let memId = 2;
            let coinId  = $("#one_day_single_annuser_cryptocoin_id").val();
            let days = 1;
            numberTwoAnnSettingCancel(coinId,days);
        });
        $("#num_two_monthly_single_annuser_cancel").click(function(){
            let memId = 2;
            let coinId  = $("#one_month_single_annuser_cryptocoin_id").val();
            let days = 365;
            numberTwoAnnSettingCancel(coinId,days);
        });

        $("#one_day_singleuser_save_btn").click(function(){

            let userId = $("#user_id").val();
            let one_day_singleuser_cryptocoin_id = $("#one_day_singleuser_cryptocoin_id").val();
            let one_day_singleuser_main_to_trading_transfer_amount = $("#one_day_singleuser_main_to_trading_transfer_amount").val();
            let one_day_singleuser_trading_to_main_transfer_amount = $("#one_day_singleuser_trading_to_main_transfer_amount").val();
            if(userId==""){
                $("#number_two_error").html("Please Select User").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }

            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:1,
                    user_id:userId,
                    cryptocoin_id:one_day_singleuser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_day_singleuser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_day_singleuser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });
        //개인별 30일
        $("#month_day_singleuser_save_btn").click(function(){

            let userId = $("#user_id").val();
            let one_day_singleuser_cryptocoin_id = $("#month_day_singleuser_cryptocoin_id").val();
            let one_day_singleuser_main_to_trading_transfer_amount = $("#month_day_singleuser_main_to_trading_transfer_amount").val();
            let one_day_singleuser_trading_to_main_transfer_amount = $("#month_day_singleuser_trading_to_main_transfer_amount").val();
            if(userId==""){
                $("#number_two_error").html("Please Select User").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_singleuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }

            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:30,
                    user_id:userId,
                    cryptocoin_id:one_day_singleuser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_day_singleuser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_day_singleuser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });


        $("#one_day_single_annuser_save_btn").click(function(){
            let memId=2;
            let one_day_single_annuser_cryptocoin_id = $("#one_day_single_annuser_cryptocoin_id").val();
            let one_day_single_annuser_main_to_trading_transfer_amount = $("#one_day_single_annuser_main_to_trading_transfer_amount").val();
            let one_day_single_annuser_trading_to_main_transfer_amount = $("#one_day_single_annuser_trading_to_main_transfer_amount").val();
            if(one_day_single_annuser_cryptocoin_id === ""){
                $("#number_two_error").html("<?= __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_single_annuser_main_to_trading_transfer_amount === ""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_single_annuser_trading_to_main_transfer_amount === ""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }

            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwoAnnSetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:1,
                    cryptocoin_id:one_day_single_annuser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_day_single_annuser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_day_single_annuser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });


        $("#one_month_singleuser_save_btn").click(function(){

            let userId = $("#user_id").val();
            let one_month_singleuser_cryptocoin_id = $("#one_month_singleuser_cryptocoin_id").val();
            let one_month_singleuser_main_to_trading_transfer_amount = $("#one_month_singleuser_main_to_trading_transfer_amount").val();
            let one_month_singleuser_trading_to_main_transfer_amount = $("#one_month_singleuser_trading_to_main_transfer_amount").val();
            if(userId==""){
                $("#number_two_error").html("<?= __('Please select user');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_cryptocoin_id==""){
                $("#number_two_error").html("<? __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:365,
                    user_id:userId,
                    cryptocoin_id:one_month_singleuser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_month_singleuser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_month_singleuser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });


        $("#one_month_single_annuser_save_btn").click(function(){
            let memId=2;
            let one_month_single_annuser_cryptocoin_id = $("#one_month_single_annuser_cryptocoin_id").val();
            let one_month_single_annuser_main_to_trading_transfer_amount = $("#one_month_single_annuser_main_to_trading_transfer_amount").val();
            let one_month_single_annuser_trading_to_main_transfer_amount = $("#one_month_single_annuser_trading_to_main_transfer_amount").val();
            if(one_month_single_annuser_cryptocoin_id==""){
                $("#number_two_error").html("<?= __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_single_annuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_single_annuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwoAnnSetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:365,
                    cryptocoin_id:one_month_single_annuser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_month_single_annuser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_month_single_annuser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });


        $("#one_day_alluser_save_btn").click(function(){
            let memId=0;
            let one_day_alluser_cryptocoin_id = $("#one_day_alluser_cryptocoin_id").val();
            let one_day_alluser_main_to_trading_transfer_amount = $("#one_day_alluser_main_to_trading_transfer_amount").val();
            let one_day_alluser_trading_to_main_transfer_amount = $("#one_day_alluser_trading_to_main_transfer_amount").val();
            if(one_day_alluser_cryptocoin_id==""){
                $("#number_two_error").html("<?= __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:1,
                    user_id:0,
                    cryptocoin_id:one_day_alluser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_day_alluser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_day_alluser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });

        $("#one_month_alluser_save_btn").click(function(){
            let memId=0;
            let one_month_alluser_cryptocoin_id = $("#one_month_alluser_cryptocoin_id").val();
            let one_month_alluser_main_to_trading_transfer_amount = $("#one_month_alluser_main_to_trading_transfer_amount").val();
            let one_month_alluser_trading_to_main_transfer_amount = $("#one_month_alluser_trading_to_main_transfer_amount").val();
            if(one_month_alluser_cryptocoin_id==""){
                $("#number_two_error").html("<?= __('Please select coin');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_alluser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter minimum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_alluser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("<?= __('Please enter maximum amount');?>").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:{days:365,
                    user_id:0,
                    cryptocoin_id:one_month_alluser_cryptocoin_id,
                    main_to_trading_transfer_amount:one_month_alluser_main_to_trading_transfer_amount,
                    trading_to_main_transfer_amount:one_month_alluser_trading_to_main_transfer_amount,
                },
                dataType:'JSON',
                success:function(resp){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
            });
        });


        $("#no_three_user_id").change(function(){
            let getUserId = $(this).val();
            if(getUserId==""){
                $("#user_category").val("");
                return false;
            }
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'userCategoryGet']) ?>/"+getUserId,
                type:'GET',
                dataType:'JSON',
                success:function(resp){
                    if(resp.success=="false"){
                        $("#user_category").val("");
                    }
                    else {
                        let getData = resp.data;
                        $("#user_category").val(getData.category);
                    }
                }
            });
        });

        $("#number_three_form").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numthreesetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    $("#number_three_loader").hide();
                    $("#number_three_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_three_success").hide(); },5000);
                }
            });
        });

        $("#no_three_user_id").change(function(){

            let userId = $(this).val();
            let coin_id = $("#no_three_coin_id").val();

            numThreeSettingGet(userId,coin_id);
        });

        $("#no_three_coin_id").change(function(){
            let userId = $("#no_three_user_id").val();
            let coin_id = $(this).val();

            numThreeSettingGet(userId,coin_id);
        });

        // number four setting save
        $("#number_four_setting").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numfoursetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    $("#number_four_loader").hide();
                    $("#number_four_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_four_success").hide(); },5000);
                }
            });
        });

        $("#number_four_coin_id").change(function(){
            let userId = 0;
            let coinId = $(this).val();
            let coinpairId = $("#number_four_buy_sell_fee_coinpair_id").val();
            numFourSettingGet(userId,coinId,coinpairId);
        })

        $("#number_four_buy_sell_fee_coinpair_id").change(function(){
            let userId = 0;
            let coinId = $("#number_four_coin_id").val();
            let coinpairId = $(this).val();
            numFourSettingGet(userId,coinId,coinpairId);
        })

        $("#coupons_cryptocoin_id").change(function(){
            let userId = 0;
            let coinId = $(this).val();
            numSixSettingGet(userId,coinId);
        })

        $("#number_six_setting").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numsixsetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    $("#number_six_loader").hide();
                    $("#number_six_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_six_success").hide(); },5000);
                }
            });
        });

        $("#num_six_view").click(function(){
            let userId = 0;
            let coinId  = $("#coupons_cryptocoin_id").val();
            showNumberSixSettingData(userId,coinId);
        });

        $("#num_six_cancel").click(function(){
            let userId = 0 ;
            let coinId  = $("#coupons_cryptocoin_id").val();
            numberSixSettingCancel(userId,coinId);

        });

        $("#number_seven_setting").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numsevensetting']) ?>",
                type:'POST',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    $("#number_seven_loader").hide();
                    $("#number_seven_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_seven_success").hide(); },5000);
                }
            });
        });
    });

    function showNumberTwoSettingData(user_id,coin_id,days){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'showNumberTwoSettingData']) ?>/",
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    let getData = resp.data;
                    //console.log(getData); return false;
                    let getHtml = "<table class='table'>";
                    getHtml += "<thead>";
                    getHtml += "<tr>";
                    getHtml += "<th><?= __('Admin Id');?></th>";
                    getHtml += "<th><?= __('Coin');?></th>";
                    getHtml += "<th><?= __('Date & Time');?></th>";
                    getHtml += "<th><?= __('Main → Trading');?></th>";
                    getHtml += "<th><?= __('Trading → Main');?></th>";
                    getHtml += "<th><?= __('Status');?></th>";
                    getHtml += "</tr>";
                    getHtml += "</thead>";

                    getHtml = getHtml+"<tbody>";
                    $.each(getData,function(key,value){
                        getHtml += "<tr>";
                        getHtml += "<td>"+value.admin_user.name+"</td>";
                        getHtml += "<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml += "<td>"+value.created+"</td>";
                        getHtml += "<td>"+value.main_to_trading_transfer_limit+"</td>";
                        getHtml += "<td>"+value.trading_to_main_transfer_limit+"</td>";
                        getHtml += "<td>"+__(value.status)+"</td>";
                        getHtml += "</tr>";
                    });
                    getHtml = getHtml+"</tbody>";
                    getHtml = getHtml+"</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function showNumberTwoAnnSettingData(coin_id,days){
        //memId = 2
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'showNumberTwoAnnSettingData']) ?>/",
            type:'POST',
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    let getData = resp.data;
                    //console.log(getData); return false;
                    let getHtml = "<table class='table'>";
                    getHtml += "<thead>";
                    getHtml += "<tr>";
                    getHtml += "<th><?= __('Admin Id');?></th>";
                    getHtml += "<th><?= __('Coin');?></th>";
                    getHtml += "<th><?= __('Date & Time');?></th>";
                    getHtml += "<th><?= __('Main → Trading');?></th>";
                    getHtml += "<th><?= __('Trading → Main');?></th>";
                    getHtml += "<th><?= __('Status');?></th>";
                    getHtml += "</tr>";
                    getHtml += "</thead>";

                    getHtml += "<tbody>";
                    $.each(getData,function(key,value){
                        getHtml += "<tr>";
                        getHtml += "<td>"+value.admin_user.name+"</td>";
                        getHtml += "<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml += "<td>"+value.created+"</td>";
                        getHtml += "<td>"+value.main_to_trading_transfer_limit+"</td>";
                        getHtml += "<td>"+value.trading_to_main_transfer_limit+"</td>";
                        getHtml += "<td>"+__(value.status)+"</td>";
                        getHtml += "</tr>";
                    });
                    getHtml += "</tbody>";
                    getHtml += "</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function numberTwoSettingCancel(user_id,coin_id,days){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberTwoSettingCancel']) ?>/",
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp.success=="true"){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();

                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
                else {
                    $("#number_two_loader").hide();
                    $("#number_two_error").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_error").hide(); },5000);
                }
            }
        });
    }

    function numberTwoAnnSettingCancel(coin_id,days){
        //memId=2
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberTwoAnnSettingCancel']) ?>/",
            type:'POST',
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success:function(resp){
                if(resp.success=="true"){
                    $("#number_two_loader").hide();
                    $("#number_two_success").html(resp.message).show();

                    setTimeout(function(){ $("#number_two_success").hide(); },5000);
                }
                else {
                    $("#number_two_loader").hide();
                    $("#number_two_error").html(resp.message).show();
                    setTimeout(function(){ $("#number_two_error").hide(); },5000);
                }
            }
        });
    }

    function numTwoSettingGet(user_id,coin_id,days,minInputBoxId,maxInputBoxId){

        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numTwoSettingGet']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#"+minInputBoxId).val("");
                    $("#"+maxInputBoxId).val("");
                }
                else {
                    let getData = resp.data;
                    $("#"+minInputBoxId).val(getData.main_to_trading_transfer_limit);
                    $("#"+maxInputBoxId).val(getData.trading_to_main_transfer_limit);
                }
            }
        });
    }

    function numTwoAnnSettingGet(coin_id,days,minInputBoxId,maxInputBoxId){
        //memId=2
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numTwoAnnSettingGet']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#"+minInputBoxId).val("");
                    $("#"+maxInputBoxId).val("");
                }
                else {
                    let getData = resp.data;
                    $("#"+minInputBoxId).val(getData.main_to_trading_transfer_limit);
                    $("#"+maxInputBoxId).val(getData.trading_to_main_transfer_limit);
                }
            }
        });
    }

    function numThreeSettingGet(user_id,coin_id){

        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numThreeSettingGet']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#no_three_user_fee").val("");
                    $("#days").val("");
                }
                else {
                    let getData = resp.data;
                    $("#no_three_user_fee").val(getData.user_fee);
                    $("#days").val(getData.days);
                }
            }
        });
    }


    function numFourSettingGet(user_id,coin_id,coinpair_id){

        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numFourSettingGet']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id,coinpair_id:coinpair_id},
            dataType:'JSON',
            success:function(resp){
                let getData = resp.data;
                $("#number_four_deposit_fee").val(getData.number_four_deposit_fee);
                $("#number_four_withdrawal_fee").val(getData.number_four_withdrawal_fee);
                $("#number_four_trading_to_main_transfer_fee").val(getData.number_four_trading_to_main_transfer_fee);
                $("#number_four_main_to_trading_transfer_fee").val(getData.number_four_main_to_trading_transfer_fee);
                $("#number_four_load_deposit_fee").val(getData.number_four_load_deposit_fee);
                $("#number_four_buy_sell_fee").val(getData.number_four_buy_sell_fee);

            }
        });
    }

    function numSixSettingGet(user_id,coin_id){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numsixsettingget']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#coupon_amount").val("");
                    $("#coupon_limit").val("");
                }
                else {
                    let getData = resp.data;
                    $("#coupon_amount").val(getData.amount);
                    $("#coupon_limit").val(getData.coupon_limit);
                }
            }
        });
    }

    function showNumberSixSettingData(user_id,coin_id){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'shownumbersixsettingdata']) ?>",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    let getData = resp.data;
                    //console.log(getData); return false;
                    let getHtml = "<table class='table'>";
                    getHtml += "<thead>";
                    getHtml += "<tr>";
                    getHtml += "<th><?= __('Admin Id');?></th>";
                    getHtml += "<th><?= __('Coin');?></th>";
                    getHtml += "<th><?= __('Amount');?></th>";
                    getHtml += "<th><?=__('Coupon Limit');?></th>"
                    getHtml += "<th><?= __('Date & Time');?></th>";
                    getHtml += "<th><?= __('Status');?></th>";
                    getHtml += "</tr>";
                    getHtml += "</thead>";

                    getHtml += "<tbody>";
                    $.each(getData,function(key,value){
                        getHtml += "<tr>";
                        getHtml += "<td>"+value.admin_id+"</td>";
                        getHtml += "<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml += "<td>"+value.amount+"</td>";
                        getHtml += "<td>"+value.coupon_limit+"</td>";
                        var created = value.created.split("+")[0].replace("T", ", ");
                        getHtml += "<td>"+created+"</td>";
                        getHtml += "<td>"+__(value.status)+"</td>";
                        getHtml += "</tr>";
                    });
                    getHtml += "</tbody>";
                    getHtml += "</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function numberSixSettingCancel(user_id,coin_id){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberSixSettingCancel']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data : {user_id:user_id,coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "true"){
                    $("#number_six_loader").hide();
                    $("#number_six_success").html(resp.message).show();
                    $("#coupon_amount").val("");
                    $("#coupon_limit").val("");
                    setTimeout(function(){ $("#number_six_success").hide(); },5000);
                }
                else {
                    $("#number_six_loader").hide();
                    $("#number_six_error").html(resp.message).show();
                    setTimeout(function(){ $("#number_six_error").hide(); },5000);
                }
            }
        });
    }

    function showNumberSevenSettingData(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'shownumbersevensettingdata']) ?>",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    let getData = resp.data;
                    //console.log(getData); return false;
                    let getHtml = "<table class='table'>";
                    getHtml +="<thead>";
                    getHtml +="<tr>";
                    getHtml +="<th><?= __('Admin Id');?></th>";
                    getHtml +="<th><?= __('Percentage');?></th>";
                    getHtml +="<th><?= __('Status');?></th>";
                    getHtml +="<th><?= __('Created');?></th>";
                    getHtml +="<th><?= __('Updated');?></th>";
                    getHtml +="</tr>";
                    getHtml +="</thead>";

                    getHtml += "<tbody>";
                    $.each(getData,function(key,value){
                        getHtml += "<tr>";
                        getHtml += "<td>"+value.admin_id+"</td>";
                        getHtml += "<td>"+value.percentage+"</td>";
                        getHtml += "<td>"+__(value.status)+"</td>";
                        var created = value.created.split("+")[0].replace("T", ", ");
                        getHtml += "<td>"+created+"</td>";
                        var updated = value.updated.split("+")[0].replace("T", ", ");
                        getHtml += "<td>"+updated+"</td>";
                        getHtml +="</tr>";
                    });
                    getHtml = getHtml+"</tbody>";
                    getHtml = getHtml+"</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function numberSevenSettingCancel(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberSevenSettingCancel']) ?>/",
            type:'POST',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "true"){
                    $("#number_seven_loader").hide();
                    $("#number_seven_success").html(resp.message).show();
                    $("#percentage").val("");
                    setTimeout(function(){ $("#number_seven_success").hide(); },5000);
                }
                else {
                    $("#number_seven_loader").hide();
                    $("#number_seven_error").html(resp.message).show();
                    setTimeout(function(){ $("#number_seven_error").hide(); },5000);
                }
            }
        });
    }

    function getLang(){
        let cookie = getCookie('Language');
        if(cookie === 'ko_KR'){
            return 'kr';
        } else {
            return 'en';
        }
    }


    function __(str) {
        if (typeof(i18n) != 'undefined' && i18n[str]) {
            if(getLang() === 'kr')
                return i18n[str];
            else
                return str;
        }
        return str;
    }
</script>