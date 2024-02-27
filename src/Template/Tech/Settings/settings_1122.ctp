<style>
    .select2-container .select2-selection--single {box-sizing: border-box;cursor: pointer;display: block;height: 35px;user-select: none;-webkit-user-select: none;}
    .input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
    .open > .dropdown-menu {
        display: block;
        height: 400px;
        overflow-y: scroll;
    }
    /*table {*/
    /*    border-collapse: separate;*/
    /*    border-spacing: 0 15px;*/
    /*}*/
    /*td {*/
    /*    padding: 5px;*/
    /*}*/

.modal-dialog {
    width: 80%;
    margin: 30px auto;
}
</style>

<div class="content-wrapper" style="overflow: auto;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?=__("Main Settings");?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"><?=__("Main Settings");?></li>
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
                                <h3 class="w3_inner_tittle two">No. 1:</h3>
                            </div>
							<script>
							$(document).ready(function(){
								$("#first_setting").submit(function(e){
									e.preventDefault();
									$("#first_setting_loader").show();
									$.ajax({
										url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting']) ?>",
										beforeSend: function(xhr){
											xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
										},
										type:'POST',
										data:$("#first_setting").serialize(),
										dataType:'JSON',
										success:function(resp){
											$("#first_setting_loader").hide();
											$("#first_setting_resp").html(resp.message).show();
											setTimeout(function(){ $("#first_setting_resp").hide(); },5000);
										}
									});
								});
								
								$("#coinpair_id").change(function(){
									var getVal = $(this).val();
									$.ajax({
										url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting_get']) ?>/"+getVal,
										beforeSend: function(xhr){
											xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
										},
										type:'GET',
										dataType:'JSON',
										success:function(resp){
											console.log(resp);
											var getData = resp.data;
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
								
								
								
							})
							</script>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <?php echo $this->Form->create('Coinpair',array('method'=>'post','enctype'=>'multipart/form-data',"id"=>"first_setting"));
                                echo $this->Form->input('id',['type'=>'hidden']);
                                ?>
                                <div class="col-md-10 col-md-offset-1">

                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
									<div style="display:none;" class="alert alert-success" id="first_setting_resp"></div>
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <span style="margin-left: 15%;"><?=__("Coin First");?> : </span>
                                                </td>
<!--                                                <td>-->
<!--                                                    <span style="margin-left: 20%;"> Coin Second : </span>-->
<!--                                                </td>-->
                                                <td>
                                                    <span style="margin-left: 20%;"><?=__("Period");?> : </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;<?=__("Up Buy/Sell");?> % </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;&nbsp;<?=__("Down Buy/Sell");?> % </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;<?=__("Up Prev. Close Market");?> % </span>
                                                </td>
                                                <td>
                                                    <span style="display: inline-block;text-align: center;"> &nbsp;&nbsp;&nbsp;<?=__("Down Prev. Close Market");?> % </span>
                                                </td>
                                            </tr>
                                           <tr>
                                               <td>
                                                   <?php  //echo $this->Form->input('first_coinList',array('id'=>'first_coinList','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
												   <select name="coinpair_id" id="coinpair_id" class="form-control input-style required" style="width:20%, display: inline-block" >
														<option value=""><?=__("Select Coin");?></option>
														<?php foreach($coinpairList as $coinpairSingle){ ?>
														<option value="<?php echo $coinpairSingle["id"]; ?>"><?php echo $coinpairSingle["cryptocoin_first"]["short_name"]."/".$coinpairSingle["cryptocoin_second"]["short_name"] ?></option>
														<?php } ?>
												   </select>
                                               </td>
<!--                                               <td>-->
<!--                                                   --><?php // echo $this->Form->input('second_coinList',array('id'=>'second_coinList','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'margin-left: 10%; width:20%, display: inline-block'));?>
<!--                                               </td>-->
                                                <td>
                                                    <span style="margin-left: 10%;display: inline-block"><?=__("Exchange 1 day");?></span>
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
                                                   <?php  echo $this->Form->button(__("Save"), ['id'=>'btn_save_one','type' => 'submit','class'=>'btn btn-primary', 'style'=>'margin-left: 50%;']); ?>
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
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">No. 2:</h3>
                            </div>

							<div class="alert alert-danger" style="display:none;" id="number_two_error"></div>
							<div class="alert alert-success"  style="display:none;" id="number_two_success"></div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
							 <img style="display:none;" id="number_two_loader" src="/ajax-loader.gif" />
                                <?php echo $this->Form->create("form",array('method'=>'post','enctype'=>'multipart/form-data','onsubmit'=>"return false;","novalidate")); ?>
                                <div class="col-md-12 col-md-offset-1">

                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <span><?=__("ALL USERS");?></span>
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
													&nbsp;
                                                </td>
												<td>
													&nbsp;
                                                </td><td>
													&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'one_day_alluser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 day");?></span>
                                                </td>

                                                <td>
                                                    <input type="text" id="one_day_alluser_main_to_trading_transfer_amount" placeholder="500" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" id="one_day_alluser_trading_to_main_transfer_amount" placeholder="500" style="width:100%;margin-left: 10px;"/>
													
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_day_alluser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
													
                                                </td>
												<td>
													<div id="num_two_daily_all_user" class="btn btn-info m-l-5"><?=__("View");?></div>
                                                </td>
												<td>
													<div id="num_two_daily_all_user_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></div>
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
                                                    <?php  echo $this->Form->input('coin_first_id',array('id' => 'one_month_alluser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 year");?></span>
                                                </td>
                                                <td>
                                                    <input type="text"  id="one_month_alluser_main_to_trading_transfer_amount" placeholder="500" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text"  id="one_month_alluser_trading_to_main_transfer_amount" placeholder="500" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_month_alluser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
													
                                                </td> <td>
                                                   
													<span id="num_two_monthly_all_user" class="btn btn-info m-l-5"><?=__("View");?></span>
                                                </td>
												<td>
                                                   
													<span id="num_two_monthly_all_user_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-top: 20px;padding-bottom: 10px;">
                                                    <?=__("GENERAL USERS");?>:
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
                                                    <?php  echo $this->Form->input('search_users',array("id"=>"user_id",'class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","options"=>[''=>__('Select User')],"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
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
                                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'one_day_singleuser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
													
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 day");?> </span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_singleuser_main_to_trading_transfer_amount' style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_singleuser_trading_to_main_transfer_amount' style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_day_singleuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
													
                                                </td> <td>
                                                  
													<span id="num_two_daily_single_user" class="btn btn-info m-l-5"><?=__("View");?></span>
                                                </td>
												<td>
                                                  
													<span id="num_two_daily_single_user_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></span>
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
                                                    <?php  echo $this->Form->input('coin_first_id',array("id"=>"one_month_singleuser_cryptocoin_id",'class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 year");?></span>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_singleuser_main_to_trading_transfer_amount" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_singleuser_trading_to_main_transfer_amount" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_month_singleuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>
													
                                                </td>
												<td>
                                                   
													<span id="num_two_monthly_single_user" class="btn btn-info m-l-5"><?=__("View");?></span>
                                                </td>
												<td>
                                                   
													<span id="num_two_monthly_single_user_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-top: 15px;">
                                                    <?=__("ALL ANNUAL MEMBERSHIP USERS");?>:  <?php  echo $this->Form->checkbox('check_users',array("id"=>"annuser_all",'checked'=>false,'style'=>'margin-left:6px;'));?>
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
                                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'one_day_single_annuser_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>

                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 day");?> </span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_single_annuser_main_to_trading_transfer_amount' style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id='one_day_single_annuser_trading_to_main_transfer_amount' style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_day_single_annuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>

                                                </td> <td>

                                                    <span id="num_two_daily_single_annuser" class="btn btn-info m-l-5"><?=__("View");?></span>
                                                </td>
                                                <td>

                                                    <span id="num_two_daily_single_annuser_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></span>
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
                                                    <?php  echo $this->Form->input('coin_first_id',array("id"=>"one_month_single_annuser_cryptocoin_id",'class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block;'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Main Account 1 year");?></span>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_single_annuser_main_to_trading_transfer_amount" style="width:100%;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="500" id="one_month_single_annuser_trading_to_main_transfer_amount" style="width:100%;margin-left: 10px;"/>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['id' => 'one_month_single_annuser_save_btn','type' => 'submit','class'=>'btn btn-primary m-l-30']); ?>

                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_annuser" class="btn btn-info m-l-5"><?=__("View");?></span>
                                                </td>
                                                <td>

                                                    <span id="num_two_monthly_single_annuser_cancel" class="btn btn-danger m-l-5"><?=__("Cancel");?></span>
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
                                <h3 class="w3_inner_tittle two">No. 3:</h3>
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
                                                    <?php  echo $this->Form->input('user_id',array('class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","options"=>[''=>__('Select User')],"required"=>true, 'style' => 'width:100%; display: inline-block;',"id"=>"no_three_user_id"));?>
                                                </td>
                                                <td>
                                                    <input type="text" id="user_category" placeholder="<?=__('User Category');?>" style="width:70%; display: inline-block; margin-left:10%;" readonly/>
<!--                                                    --><?php // echo $this->Form->input('level_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>"Select User Type"]+$levelList,"required"=>true,'style' => 'width:100%; display: inline-block; margin-left:10px;')); ?>
                                                </td>
                                                <td>
                                                    <?=__("Select Period");?>:
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('cryptocoin_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block',"id"=>"no_three_coin_id"));?>
                                                </td>
                                                <td>
                                                    <span style="margin-left: 10%;"> <?=__("User's fees");?></span>
                                                    <input type="text" placeholder="0.25" name="no_three_user_fee" style="width:40%;" id="no_three_user_fee" />
                                                </td>

                                                <td>
<!--  <input type="text" placeholder="1day~" style="width:40%;margin-left: 10%;"/> -->
                                                    <?php echo $this->Form->input('days',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>__("Please Select"),"1"=>"1 day","7"=>"1 week","15"=>"15 days","30"=>"1 month","90"=>"3 months","180"=>"6 months"],"required"=>true,'style' => 'width:100%; display: inline-block;')); ?>
                                                </td>

                                                <td>
                                                    <?php  echo $this->Form->button(__("Save"), ['type' => 'submit','class'=>'btn btn-primary', 'style'=>'margin-left: 50%;']); ?>
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
                                <h3 class="w3_inner_tittle two">No. 4:</h3>
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
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("ALL USERS");?>
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
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Select  Coin");?>
														</span>
													</td> 
													<td >
													
														<?php  echo $this->Form->input('number_four_coin_id',array('class' => 'form-control input-style required','label' =>false,'id' =>'number_four_coin_id',"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:100%; display: inline-block'));?>
														
													</td>
                                                </tr>
												
												<tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Deposit Fee");?> % </span>
														
													</td> 
													<td >
													
														 <input type="text" placeholder="0.25" id="number_four_deposit_fee" name="number_four_deposit_fee"/>
														
													</td>
                                                </tr>
												<tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Withdrawal Fee");?> % </span>
														
													</td> 
													<td >
													
														  <input type="text" placeholder="0.25" id="number_four_withdrawal_fee" name="number_four_withdrawal_fee"/>
														
													</td>
                                                </tr>
												
												<tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Trading Account to Main Account Transfer Fee");?> % &nbsp;&nbsp;</span>
														
													</td> 
													<td >
													
														  <input type="text" placeholder="0.25" id="number_four_trading_to_main_transfer_fee" name="number_four_trading_to_main_transfer_fee"/>
														
													</td>
                                                </tr>
												
												<tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Main Account to Trading Account Transfer Fee");?> % &nbsp;&nbsp;</span>
														
													</td> 
													<td >
													
														  <input type="text" placeholder="0.25" id="number_four_main_to_trading_transfer_fee" name="number_four_main_to_trading_transfer_fee"/>
														
													</td>
                                                </tr>
												<tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Loan Deposit Fee");?> % </span>
														
													</td> 
													<td >
													
														 <input type="text" placeholder="0.25" id="number_four_load_deposit_fee" name="number_four_load_deposit_fee"/>
														
													</td>
                                                </tr>
                                               <tr style="padding: 5px;">
													<td >
													
														<span style="margin-left: 10px;display: inline-block;"> <?=__("Buy/Sell Fee");?> % </span>
														
													</td> 
													<td >
														<select name="number_four_buy_sell_fee_coinpair_id" id="number_four_buy_sell_fee_coinpair_id" class="form-control input-style required" style="width:20%, display: inline-block" >
															<option value=""><?=__("Select Coin");?></option>
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
                                <h3 class="w3_inner_tittle two">No. 5:</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <?php echo $this->Form->create("form",array('method'=>'post','enctype'=>'multipart/form-data')); ?>
                                <div class="col-md-10 col-md-offset-1">
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-offset-10 form-group valid-form">
                                        <table id="parent">
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coin_first_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>__('Select Coin')]+$coinList,"required"=>true, 'style' => 'width:20%, display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Users fees");?></span>
                                                </td>

                                                <td>
                                                    <input type="text" placeholder="0.25" style="width:50px;"/>
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="1day~" style="width:50px;margin-left: 10px;"/>
                                                </td>
<!--                                                <td>-->
<!--                                                    --><?php // echo $this->Form->input('coin_first_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'Select Coin']+$coinList,"required"=>true, 'style' => 'width:90%; display: inline-block; margin-left:20px'));?>
<!--                                                </td>-->
                                                <td>
                                                    <span style="padding: 10px;"><?=__("Level");?></span>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->input('level_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[""=>__("Select Level")]+$levelList,"required"=>true,'style' => 'width:100%; display: inline-block;')); ?>
                                                </td>

                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary','style'=>'margin-left: 50%;']); ?>
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
                                <h3 class="w3_inner_tittle two">No. 6: <?=__("Coupon Settings");?></h3>
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
                                                <th>코인</th>
                                                <th><?= __('Coupon Amount');?></th>
												<th>구매할 KRW 금액 설정</th>
                                                <th>한달 구매 제한</th>
												<th>구매 제한 시간</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php  echo $this->Form->input('coupons_cryptocoin_id',array('id' => 'coupons_cryptocoin_id','class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>[''=>'코인 선택']+$coinList,"required"=>true, 'style' => 'width:70%; display: inline-block'));?>
                                                </td>
                                                <td>
                                                    <input name="coupon_amount" id="coupon_amount" type="text" placeholder="0" style="width:40%;"/>
                                                </td>
												<td>
                                                    <input name="krw" id="krw" type="text" value="50000" placeholder="50000" style="width:60%;"/>
                                                </td>
                                                <td>
                                                    <input name="coupon_limit" id="coupon_limit" type="text" value="0" placeholder="0" style="width:60%;"/>
                                                </td>
												<td>
                                                    <input name="time_limit" id="time_limit" type="text" value="0" placeholder="0" style="width:60%;"/>
                                                </td>
                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                                </td>
                                                <td>
                                                    <div id="num_six_view" class="btn btn-info m-l-5"><?= __('View');?></div>
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
                                <h3 class="w3_inner_tittle two">No. 7: <?=__("Withdrawal % Settings");?></h3>
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
                                                    <span style="padding: 10px;"> <?=__("Withdrawal Percentage");?> </span>
                                                </td>
                                                <td>
                                                    <?php  echo $this->Form->input('percentage',array('placeholder'=>'%','type'=>'text', 'label' => false, 'style'=>'width:40%;', 'value'=>(!empty($percentage) ? $percentage : "" ))); ?>
                                                </td>
                                                <td>
                                                    <div class="clearfix"></div>
                                                    <?php  echo $this->Form->button(__('Save'), ['type' => 'submit','class'=>'btn btn-primary m-l-5']); ?>
                                                </td>
                                                <td>
                                                    <div id="num_seven_view" class="btn btn-info m-l-5" onclick="showNumberSevenSettingData();"><?=__("View");?></div>
                                                </td>
                                                <td>
                                                    <div id="num_seven_cancel" class="btn btn-danger m-l-5" onclick="numberSevenSettingCancel();"><?=__("Cancel");?></div>
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
	<!--<section id="content" class="table-layout">
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
    </section>-->
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
</div>
<script>
	$(document).ready(function(){
		get_server_check_list();
	});
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    function getValue(ele){
        return ele.value;
    }
	function delete_login_sesseion(){
		if(confirm('삭제하시겠습니까?')){
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
                var value = "Y";
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
                var  value = "N";
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

    });
    $(document).ready(function(){


		$("#coinpair_id").change(function(){
			var getVal = $(this).val();
			$.ajax({
				url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numonesetting_get']) ?>/"+getVal,
				type:'GET',
				dataType:'JSON',
				success:function(resp){
					console.log(resp);
					var getData = resp.data;
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
			var userId = 0;
			var memId = 0;
			var coin_id = $(this).val();
			var days = 1;
			numTwoSettingGet(userId,coin_id,days,"one_day_alluser_main_to_trading_transfer_amount","one_day_alluser_trading_to_main_transfer_amount");
		});
		
		$("#one_month_alluser_cryptocoin_id").change(function(){
			var userId = 0;
            var memId = 0;
			var coin_id = $(this).val();
			var days = 365;
			numTwoSettingGet(userId,coin_id,days,"one_month_alluser_main_to_trading_transfer_amount","one_month_alluser_trading_to_main_transfer_amount");
		});

        $("#one_day_single_annuser_cryptocoin_id").change(function(){
            var userId=1;
            var memId = 2;
            var coin_id = $(this).val();
            var days = 1;
            numTwoAnnSettingGet(coin_id,days,"one_day_single_annuser_main_to_trading_transfer_amount","one_day_single_annuser_trading_to_main_transfer_amount");
        });

        $("#one_month_single_annuser_cryptocoin_id").change(function(){
            var memId = 2;
            var coin_id = $(this).val();
            var days = 365;
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
			var userId = $("#user_id").val();
			var coin_id = $(this).val();
			var days = 1;
			numTwoSettingGet(userId,coin_id,days,"one_day_singleuser_main_to_trading_transfer_amount","one_day_singleuser_trading_to_main_transfer_amount");
		});
		
		$("#one_month_singleuser_cryptocoin_id").change(function(){
			var userId = $("#user_id").val();
			var coin_id = $(this).val();
			var days = 365;
			numTwoSettingGet(userId,coin_id,days,"one_month_singleuser_main_to_trading_transfer_amount","one_month_singleuser_trading_to_main_transfer_amount");
		});
		
		
		$("#num_two_daily_all_user").click(function(){
			var userId = 0 ;
			var memId = 0;
			var coinId  = $("#one_day_alluser_cryptocoin_id").val();
			var days = 1; 
			showNumberTwoSettingData(userId,coinId,days);
		});
		$("#num_two_monthly_all_user").click(function(){
			var userId = 0 ;
			var memId =0;
			var coinId  = $("#one_month_alluser_cryptocoin_id").val();
			var days = 365;
			showNumberTwoSettingData(userId,coinId,days);
		});

		$("#num_two_daily_single_user").click(function(){
			var userId = $("#user_id").val() ;
			var coinId  = $("#one_day_singleuser_cryptocoin_id").val();
			var days = 1; 
			showNumberTwoSettingData(userId,coinId,days);
		});
		$("#num_two_monthly_single_user").click(function(){
			var userId = $("#user_id").val() ; ;
			var coinId  = $("#one_month_singleuser_cryptocoin_id").val();
			var days = 365;
			showNumberTwoSettingData(userId,coinId,days);
		});

        $("#num_two_daily_single_annuser").click(function(){
            var memId = 2;
            var coinId  = $("#one_day_single_annuser_cryptocoin_id").val();
            var days = 1;
            showNumberTwoAnnSettingData(coinId,days);
        });
        $("#num_two_monthly_single_annuser").click(function(){
            var memId = 2;
            var coinId  = $("#one_month_single_annuser_cryptocoin_id").val();
            var days = 365;
            showNumberTwoAnnSettingData(coinId,days);
        });
		



        $("#num_two_daily_all_user_cancel").click(function(){
			var userId = 0 ;
			var memId = 0;
			var coinId  = $("#one_day_alluser_cryptocoin_id").val();
			var days = 1; 
			numberTwoSettingCancel(userId,coinId,days);
			
		});
		$("#num_two_monthly_all_user_cancel").click(function(){
			var userId = 0 ;
			var memId =0;
			var coinId  = $("#one_month_alluser_cryptocoin_id").val();
			var days = 365;
			numberTwoSettingCancel(userId,coinId,days);
		});
		$("#num_two_daily_single_user_cancel").click(function(){
			var userId = $("#user_id").val() ;
			var coinId  = $("#one_day_singleuser_cryptocoin_id").val();
			var days = 1; 
			numberTwoSettingCancel(userId,coinId,days);
		});
		$("#num_two_monthly_single_user_cancel").click(function(){
			var userId = $("#user_id").val() ;
			var coinId  = $("#one_month_singleuser_cryptocoin_id").val();
			var days = 365;
			numberTwoSettingCancel(userId,coinId,days);
		});

        $("#num_two_daily_single_annuser_cancel").click(function(){
            var memId = 2;
            var coinId  = $("#one_day_single_annuser_cryptocoin_id").val();
            var days = 1;
            numberTwoAnnSettingCancel(coinId,days);
        });
        $("#num_two_monthly_single_annuser_cancel").click(function(){
            var memId = 2;
            var coinId  = $("#one_month_single_annuser_cryptocoin_id").val();
            var days = 365;
            numberTwoAnnSettingCancel(coinId,days);
        });


        $("#one_day_singleuser_save_btn").click(function(){

            var userId = $("#user_id").val();
            var one_day_singleuser_cryptocoin_id = $("#one_day_singleuser_cryptocoin_id").val();
            var one_day_singleuser_main_to_trading_transfer_amount = $("#one_day_singleuser_main_to_trading_transfer_amount").val();
            var one_day_singleuser_trading_to_main_transfer_amount = $("#one_day_singleuser_trading_to_main_transfer_amount").val();
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
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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


            $("#one_day_single_annuser_save_btn").click(function(){
                var memId=2;
                var one_day_single_annuser_cryptocoin_id = $("#one_day_single_annuser_cryptocoin_id").val();
                var one_day_single_annuser_main_to_trading_transfer_amount = $("#one_day_single_annuser_main_to_trading_transfer_amount").val();
                var one_day_single_annuser_trading_to_main_transfer_amount = $("#one_day_single_annuser_trading_to_main_transfer_amount").val();
                if(one_day_single_annuser_cryptocoin_id === ""){
                    $("#number_two_error").html("Please Select Coin").show();
                    setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                    return false;
                }
                else if(one_day_single_annuser_main_to_trading_transfer_amount === ""){
                    $("#number_two_error").html("Please Enter Minimum Amount").show();
                    setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                    return false;
                }
                else if(one_day_single_annuser_trading_to_main_transfer_amount === ""){
                    $("#number_two_error").html("Please Enter Maximum Amount").show();
                    setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                    return false;
                }

            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwoAnnSetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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

            var userId = $("#user_id").val();
            var one_month_singleuser_cryptocoin_id = $("#one_month_singleuser_cryptocoin_id").val();
            var one_month_singleuser_main_to_trading_transfer_amount = $("#one_month_singleuser_main_to_trading_transfer_amount").val();
            var one_month_singleuser_trading_to_main_transfer_amount = $("#one_month_singleuser_trading_to_main_transfer_amount").val();
            if(userId==""){
                $("#number_two_error").html("Please Select User").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_singleuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
            var memId=2;
            var one_month_single_annuser_cryptocoin_id = $("#one_month_single_annuser_cryptocoin_id").val();
            var one_month_single_annuser_main_to_trading_transfer_amount = $("#one_month_single_annuser_main_to_trading_transfer_amount").val();
            var one_month_single_annuser_trading_to_main_transfer_amount = $("#one_month_single_annuser_trading_to_main_transfer_amount").val();
            if(one_month_single_annuser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_single_annuser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_single_annuser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwoAnnSetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
            var memId=0;
            var one_day_alluser_cryptocoin_id = $("#one_day_alluser_cryptocoin_id").val();
            var one_day_alluser_main_to_trading_transfer_amount = $("#one_day_alluser_main_to_trading_transfer_amount").val();
            var one_day_alluser_trading_to_main_transfer_amount = $("#one_day_alluser_trading_to_main_transfer_amount").val();
            if(one_day_alluser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_day_alluser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
            var memId=0;
            var one_month_alluser_cryptocoin_id = $("#one_month_alluser_cryptocoin_id").val();
            var one_month_alluser_main_to_trading_transfer_amount = $("#one_month_alluser_main_to_trading_transfer_amount").val();
            var one_month_alluser_trading_to_main_transfer_amount = $("#one_month_alluser_trading_to_main_transfer_amount").val();
            if(one_month_alluser_cryptocoin_id==""){
                $("#number_two_error").html("Please Select Coin").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_alluser_main_to_trading_transfer_amount==""){
                $("#number_two_error").html("Please Enter Minimum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }
            else if(one_month_alluser_trading_to_main_transfer_amount==""){
                $("#number_two_error").html("Please Enter Maximum Amount").show();
                setTimeout(function(){ $("#number_two_error").html("").hide(); },5000);
                return false;
            }


            $("#number_two_loader").show();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numtwosetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
            var getUserId = $(this).val();
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
                        var getData = resp.data;
                        $("#user_category").val(getData.category);
                    }
                }
            });
        });

		$("#number_three_form").submit(function(e){
			e.preventDefault();
			$.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numthreesetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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

			var userId = $(this).val();
			var coin_id = $("#no_three_coin_id").val();
			
			numThreeSettingGet(userId,coin_id);
		});
		
		$("#no_three_coin_id").change(function(){
			var userId = $("#no_three_user_id").val();
			var coin_id = $(this).val();
			
			numThreeSettingGet(userId,coin_id);
		});
		
		/* $("#days").change(function(){
			
			var userId = $("#no_three_user_id").val();
			var coin_id = $("#no_three_coin_id").val();
			var days = $("#days").val();
			numThreeSettingGet(userId,coin_id,days);
		}); */
		
		// number four setting save
		$("#number_four_setting").submit(function(e){
			e.preventDefault();
			$.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numfoursetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    $("#number_four_loader").hide();
                    $("#number_four_success").html(resp.message).show();
                    setTimeout(function(){ $("#number_four_success").hide(); },5000);
                }
            });
		});
		
		// $("#number_four_user_id").change(function(){
		// 	var userId = $(this).val();
		// 	var coinId = $("#number_four_coin_id").val();
		// 	var coinpairId = $("#number_four_buy_sell_fee_coinpair_id").val();
		// 	numFourSettingGet(userId,coinId,coinpairId);
		// });
		
		$("#number_four_coin_id").change(function(){
            var userId = 0;
			var coinId = $(this).val();
			var coinpairId = $("#number_four_buy_sell_fee_coinpair_id").val();
			numFourSettingGet(userId,coinId,coinpairId);
		})
		
		$("#number_four_buy_sell_fee_coinpair_id").change(function(){
			var userId = 0;
			var coinId = $("#number_four_coin_id").val();
			var coinpairId = $(this).val();
			numFourSettingGet(userId,coinId,coinpairId);
		});

        $("#coupons_cryptocoin_id").change(function(){
            var userId = 0;
            var coinId = $(this).val();
            numSixSettingGet(userId,coinId);
        })

        $("#number_six_setting").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numsixsetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
            showNumberSixSettingData();
        });

        $("#num_six_cancel").click(function(){
            var userId = 0 ;
            var coinId  = $("#coupons_cryptocoin_id").val();
            numberSixSettingCancel(userId,coinId);

        });

        $("#number_seven_setting").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numsevensetting']) ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
                type:'POST',
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
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    var getData = resp.data;
                    //console.log(getData); return false;
                    var getHtml = "<table class='table'>";
                    getHtml = getHtml+"<thead>";
                    getHtml = getHtml+"<tr>";
                    getHtml = getHtml+"<th>Admin Id</th>";
                    getHtml = getHtml+"<th>Coin</th>";
                    getHtml = getHtml+"<th>Date & Time</th>";
                    getHtml = getHtml+"<th>Main To Trading</th>";
                    getHtml = getHtml+"<th>Trading To Main</th>";
                    getHtml = getHtml+"<th>Status</th>";
                    getHtml = getHtml+"</tr>";
                    getHtml = getHtml+"</thead>";

                    getHtml = getHtml+"<tbody>";
                    $.each(getData,function(key,value){
                        getHtml = getHtml+"<tr>";
                        getHtml = getHtml+"<td>"+value.admin_user.name+"</td>";
                        getHtml = getHtml+"<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml = getHtml+"<td>"+value.created+"</td>";
                        getHtml = getHtml+"<td>"+value.main_to_trading_transfer_limit+"</td>";
                        getHtml = getHtml+"<td>"+value.trading_to_main_transfer_limit+"</td>";
                        getHtml = getHtml+"<td>"+value.status+"</td>";
                        getHtml = getHtml+"</tr>";
                    });
                    getHtml = getHtml+"</tbody>";
                    getHtml = getHtml+"</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function numberTwoSettingCancel(user_id,coin_id,days){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberTwoSettingCancel']) ?>/",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
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
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#"+minInputBoxId).val("");
                    $("#"+maxInputBoxId).val("");
                }
                else {
                    var getData = resp.data;
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
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#"+minInputBoxId).val("");
                    $("#"+maxInputBoxId).val("");
                }
                else {
                    var getData = resp.data;
                    $("#"+minInputBoxId).val(getData.main_to_trading_transfer_limit);
                    $("#"+maxInputBoxId).val(getData.trading_to_main_transfer_limit);
                }
            }
        });
    }

    function showNumberTwoAnnSettingData(coin_id,days){
        //memId = 2
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'showNumberTwoAnnSettingData']) ?>/",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    var getData = resp.data;
                    //console.log(getData); return false;
                    var getHtml = "<table class='table'>";
                    getHtml = getHtml+"<thead>";
                    getHtml = getHtml+"<tr>";
                    getHtml = getHtml+"<th>Admin Id</th>";
                    getHtml = getHtml+"<th>Coin</th>";
                    getHtml = getHtml+"<th>Date & Time</th>";
                    getHtml = getHtml+"<th>Main To Trading</th>";
                    getHtml = getHtml+"<th>Trading To Main</th>";
                    getHtml = getHtml+"<th>Status</th>";
                    getHtml = getHtml+"</tr>";
                    getHtml = getHtml+"</thead>";

                    getHtml = getHtml+"<tbody>";
                    $.each(getData,function(key,value){
                        getHtml = getHtml+"<tr>";
                        getHtml = getHtml+"<td>"+value.admin_user.name+"</td>";
                        getHtml = getHtml+"<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml = getHtml+"<td>"+value.created+"</td>";
                        getHtml = getHtml+"<td>"+value.main_to_trading_transfer_limit+"</td>";
                        getHtml = getHtml+"<td>"+value.trading_to_main_transfer_limit+"</td>";
                        getHtml = getHtml+"<td>"+value.status+"</td>";
                        getHtml = getHtml+"</tr>";
                    });
                    getHtml = getHtml+"</tbody>";
                    getHtml = getHtml+"</table>";
                    $("#model_content").html(getHtml);
                }
                $("#myModal").modal('show');
            }
        });
    }

    function numberTwoAnnSettingCancel(coin_id,days){
        //memId=2
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberTwoAnnSettingCancel']) ?>/",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {coin_id:coin_id,days:days},
            dataType:'JSON',
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

    function numThreeSettingGet(user_id,coin_id){

        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numThreeSettingGet']) ?>/",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                if(resp.success=="false"){
                    $("#no_three_user_fee").val("");
                    $("#days").val("");
                }
                else {
                    var getData = resp.data;
                    $("#no_three_user_fee").val(getData.user_fee);
                    $("#days").val(getData.days);
                }
            }
        });
    }

    function numFourSettingGet(user_id,coin_id,coinpair_id){

        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numFourSettingGet']) ?>/",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            data : {user_id:user_id,coin_id:coin_id,coinpair_id:coinpair_id},
            dataType:'JSON',
            success:function(resp){
                var getData = resp.data;
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
					$("#krw").val("");
					$("#time_limit").val("");
                }
                else {
                    let getData = resp.data;
                    $("#coupon_amount").val(getData.amount);
                    $("#coupon_limit").val(getData.coupon_limit);
					$("#krw").val(getData.krw);
					$("#time_limit").val(getData.time_limit);
                }
            }
        });
    }

    function showNumberSixSettingData(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'shownumbersixsettingdata']) ?>",
            type:'POST',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            data : {},
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    let getData = resp.data;
                    let getHtml = "<table class='table'>";
                    getHtml += "<thead>";
                    getHtml += "<tr>";
                    getHtml += "<th><?= __('Admin Id');?></th>";
                    getHtml += "<th><?= __('Coin');?></th>";
                    getHtml += "<th><?= __('Amount');?></th>";
					getHtml += "<th><?= __('KRW');?></th>";
                    getHtml += "<th><?=__('Coupon Limit');?></th>";
					getHtml += "<th>구매 제한 시간</th>";
                    getHtml += "<th><?= __('Status');?></th>";
                    getHtml += "<th><?= __('Created');?></th>";
					getHtml += "<th><?= __('Updated');?></th>";
					getHtml += "<th><?= __('Cancel');?></th>";
                    getHtml += "</tr>";
                    getHtml += "</thead>";
                    getHtml += "<tbody>";
                    $.each(getData,function(key,value){
                        var created = value.created.split("+")[0].replace("T", " ");
						var updated = value.updated.split("+")[0].replace("T", " ");
                        getHtml += "<tr>";
                        getHtml += "<td>"+value.admin_id+"</td>";
                        getHtml += "<td>"+value.cryptocoin.short_name+"</td>";
                        getHtml += "<td>"+value.amount+"</td>";
						getHtml += "<td>"+value.krw+"</td>";
                        getHtml += "<td>"+value.coupon_limit+"</td>";
						getHtml += "<td>"+value.time_limit+"</td>";
                        getHtml += "<td>"+value.status+"</td>";
                        getHtml += "<td>"+created+"</td>";
						getHtml += "<td>"+updated+"</td>";
						getHtml += '<td><button type="button" class="btn btn-xs btn-danger" onclick="numberSixSettingCancel('+value.cryptocoin_id+')">삭제</button></td>';
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

    function numberSixSettingCancel(coin_id){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'numberSixSettingCancel']) ?>/",
            type:'POST',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            data : {coin_id:coin_id},
            dataType:'JSON',
            success:function(resp){
                showNumberSixSettingData();
            }
        });
    }

    function showNumberSevenSettingData(){
        $.ajax({
            url:"<?php echo $this->Url->build(['controller'=>'Settings','action'=>'shownumbersevensettingdata']) ?>",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',
            dataType:'JSON',
            success:function(resp){
                if(resp.success === "false"){
                    $("#model_content").html(resp.message);
                }
                else {
                    var getData = resp.data;
                    //console.log(getData); return false;
                    var getHtml = "<table class='table'>";
                    getHtml = getHtml+"<thead>";
                    getHtml = getHtml+"<tr>";
                    getHtml = getHtml+"<th>Admin Id</th>";
                    getHtml = getHtml+"<th>Percentage</th>";
                    getHtml = getHtml+"<th>Status</th>";
                    getHtml = getHtml+"<th>Created</th>";
                    getHtml = getHtml+"<th>Updated</th>";
                    getHtml = getHtml+"</tr>";
                    getHtml = getHtml+"</thead>";

                    getHtml = getHtml+"<tbody>";
                    $.each(getData,function(key,value){
                        getHtml = getHtml+"<tr>";
                        getHtml = getHtml+"<td>"+value.admin_id+"</td>";
                        getHtml = getHtml+"<td>"+value.percentage+"</td>";
                        getHtml = getHtml+"<td>"+value.status+"</td>";
                        var splitDateTime = value.created;
                        var splitDateTime = splitDateTime.split("+");
                        var getdateTime = splitDateTime[0];
                        var getdateTime = getdateTime.replace("T", ", ");
                        getHtml = getHtml+"<td>"+getdateTime+"</td>";
                        var splitDateTimeU = value.updated;
                        var splitDateTimeU = splitDateTimeU.split("+");
                        var getdateTimeU = splitDateTimeU[0];
                        var getdateTimeU = getdateTimeU.replace("T", ", ");
                        getHtml = getHtml+"<td>"+getdateTimeU+"</td>";
                        getHtml = getHtml+"</tr>";
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
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type:'POST',

            dataType:'JSON',
            success:function(resp){
                if(resp.success=="true"){
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
</script>