<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'admincouponslist']);  ?>"><?=__("Admin Coupons List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'admincouponslist']);  ?>"><?=__("Admin Coupons List");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form id="frm" method="get" >
                        <div class="form-group">
						<!--	<div id="selectrec" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_id',array('empty'=>__('Please select record number'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"list_no")); ?>
                            </div>-->
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__("Start Date"),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__("End Date"),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?=__("Search");?></button>
                            </div>
                        </div>
                    </form>
					<div class="clearfix"></div>
                    <div class="dropdown m-b-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <?= $this->Flash->render() ?>
                    <div id="transferHistory" class="mt10 table-responsive">
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr >
									<th style="color:#fff"><?= __('#')?></th>
									<th style="color:#fff"><?= __('Admin ID')?></th>
									<th style="color:#fff"><?= __('Admin Name')?></th>
									<th style="color:#fff"><?= __('Admin Phone Number')?></th>
									<th style="color:#fff"><?= __('Admin Wallet Address')?></th>
									<th style="color:#fff"><?= __('User ID')?></th>
									<th style="color:#fff"><?= __('User Name')?></th>
									<th style="color:#fff"><?= __('Phone Number')?></th>
									<th style="color:#fff"><?= __('Wallet Address')?></th>
									<th style="color:#fff"><?= __('Annual Membership')?></th>
									<th style="color:#fff"><?= __('Bank Name')?></th>
									<th style="color:#fff"><?= __('Bank Account Number')?></th>
									<th style="color:#fff"><?= __('Coupon Currency')?></th>
									<th style="color:#fff"><?= __('Coupon Amount')?></th>
									<th style="color:#fff"><?= __('KRW')?></th>
									<th style="color:#fff"><?= __('KRW Amount')?></th>
									<th style="color:#fff"><?= __('Transaction Type')?></th>
									<th style="color:#fff"><?= __('Date & Time')?></th>
								</tr>
                            <thead>
                            <tbody id="transferHistoryList">
                            <?php

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['coupon_user_id'], 1, '관리자 쿠폰 목록 조회');
                                if($k%2==0) $class="odd";
                                else $class="even";
                                ?>
                                <tr class="<?=$class?>">
                                    <td><?= $data['id']; ?></td>
                                    <td><?= $data['user_id']; ?></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
                                    <td width="120" style="word-break:break-all;"><?= $data['user']['eth_address']; ?></td>
                                    <td><?= $data['coupon_user_id']; ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['coupon_user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['usersa']['name']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['coupon_user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['usersa']['phone_number']); ?></a></td>
                                    <td width="120" style="word-break:break-all;"><?= $data['usersa']['eth_address']; ?></td>
                                    <td><?php if($data['usersa']['annual_membership'] == 'Y'){ echo "✔"; } else { echo "✗"; } ?></td>
                                    <td><?= __($data['usersa']['bank']); ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'B',<?= $data['coupon_user_id']; ?>)" class="text-dark"><?= $this->masking('B',$this->Decrypt($data['usersa']['account_number'])); ?></a></td>
                                    <td><?= $data['cryptocoinsa']['short_name']; ?></td>
                                    <td><?= number_format((float)$data['coin_amount'],2);?> </td>
                                    <td><?= $data['cryptocoin']['short_name']; ?></td>
                                    <td><?= number_format((float)$data['amount'],2);?> </td>
                                    <td><?php if ($data['type'] == "deducted_coupon_krw") { echo __('Deducted Amount'); } ?></td>
                                    <td><?=$data['created_at']->format('Y-m-d H:i:s');?> </td>
                                </tr>
                                <?php } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo '<tr class="even"><td colspan = "18" style="text-align: center;">'.__("No record found").'</td></tr>';
                            } ?>
                            </tbody>
                        </table>
                        <?php
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'admincouponslist')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";
							$paginator = $this->Paginator;
							echo $paginator->first(__("First"));
							if($paginator->hasPrev()){
								//echo $paginator->prev("Prev");
							}
							echo $paginator->numbers(array('modulus' => 9));
							if($paginator->hasNext()){
								//echo $paginator->next("Next");
							}
							echo $paginator->last(__("Last"));
							echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<input type="hidden" id="user_name_search" name="" value="<?=$this->request->query('user_name');?>">
<script>
    function getUserInfo(id){
        $("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td><td colspan="2">&nbsp;</td></tr>');
        $.ajax({
            url: "<?php echo $this->Url->build(['controller'=>'transactions','action'=>'admincouponslistajax']); ?>/",
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
                    html = html + '<td>' + getData.user.eth_address + '</td>';
                    html = html + '<td>' + getData.coupon_user_id + '</td>';
                    html = html + '<td>' + getData.usersa.name + '</td>';
                    html = html + '<td>' + getData.usersa.phone_number + '</td>';
                    html = html + '<td>' + getData.usersa.eth_address + '</td>';
                    if(getData.usersa.annual_membership === "Y"){
                        html = html + '<td> ✔ </td>';
                    } else {
                        html = html + '<td> ✗ </td>';
                    }
                    html = html + '<td>' + getData.usersa.bank + '</td>';
                    html = html + '<td>' + getData.usersa.account_number + '</td>';
                    html = html + '<td>' + getData.cryptocoinsa.short_name + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(getData.coin_amount).toFixed(2)) + '</td>';
                    html = html + '<td>' + getData.cryptocoin.short_name + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(getData.amount).toFixed(2)) + '</td>';
                    if(getData.tx_type === "deducted_coupon_krw"){
                        html = html + '<td>Deducted Amount</td>';
                    }

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
            url: "<?php echo $this->Url->build(['controller'=>'transactions','action'=>'admincouponslistajaxname']); ?>/",
            data: {user_id:userId},
            type:'POST',
            dataType: 'JSON',
            success: function(resp) {
                var html = '';
                $.each(resp.data.userlist,function(resKey,respVal) {
                    html = html + '<tr>';
                    html = html + '<td>' + respVal.id + '</td>';
                    html = html + '<td>' + respVal.adminId + '</td>';
                    html = html + '<td>' + respVal.adminName + '</td>';
                    html = html + '<td>' + respVal.adminPhone + '</td>';
                    html = html + '<td>' + respVal.adminWallet + '</td>';
                    html = html + '<td>' + respVal.userId + '</td>';
                    html = html + '<td>' + respVal.userName + '</td>';
                    html = html + '<td>' + respVal.phone + '</td>';
                    html = html + '<td>' + respVal.userWallet + '</td>';
                    if(respVal.membership === "Y"){
                        html = html + '<td> ✔ </td>';
                    } else {
                        html = html + '<td> ✗ </td>';
                    }
                    html = html + '<td>' + respVal.bank + '</td>';
                    html = html + '<td>' + respVal.accountnum + '</td>';
                    html = html + '<td>' + respVal.couponCoin + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(respVal.coinAmount).toFixed(2)) + '</td>';
                    html = html + '<td>' + respVal.coin + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(respVal.amount).toFixed(2)) + '</td>';
                    if(respVal.type === "deducted_coupon_krw"){
                        html = html + '<td>Deducted Amount</td>';
                    }

                    var splitDateTime = respVal.created;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T", " ");
                    html = html + '<td>' + getdateTime + '</td>';

                    html = html + '</tr>';
                });

                $('tbody').html(html);
                $("#transferHistory").hide();
                $("#search_users").hide();
            },
            error: function (e) {
                $("#ajax_coin_tr").hide();
                //$("#model_qr_code_flat").hide();
            }
        });
    }

    $(document).ready(function(){
        datepicker_set('start-date');
		datepicker_set('end-date');
        list_select2('list_no','admincouponslist');

        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
    });
</script>
