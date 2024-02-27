<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'depositapplicationlist']);  ?>"><?=__("Investment List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'depositapplicationlist']);  ?>"><?=__("Investment List");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
					<?php echo $this->element('Admin/deposit_application_menu');?>
					<div class="clearfix"></div>
                    <form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask" id="frm">
                        <div class="form-group" style="margin-top:15px;">
							<div id="" class="col-md-2 col-sm-2 col-xs-12">
								<select id="investment_number" name="investment_number" onchange="get_inumber_list(this.value)" class="form-control">
									<option value=""><?=__("Select Stage");?></option>
									<?php 
										foreach($stage_list as $sl){
									?>
										<option value="<?=$sl->stage;?>"<?php if($this->request->query('investment_number')==$sl->stage){echo 'selected';}?>><?=$sl->stage;?><?=__("Stage");?></option>
									<?php 
										}
									?>
								</select>
                            </div>
							<div id="" class="col-md-2 col-sm-2 col-xs-12">
								<select id="status" name="status" onchange="get_inumber_list(this.value)" class="form-control">
									<option value=""><?=__('Select Status');?></option>
									<option value="A" <?php if($this->request->query('status') == 'A'){echo 'selected';}?>><?=__("Approval");?></option>
									<option value="P" <?php if($this->request->query('status') == 'P'){echo 'selected';}?>><?=__("Pending2");?></option>
									<option value="C" <?php if($this->request->query('status') == 'C'){echo 'selected';}?>><?=__("Cancel");?></option>
									<option value="E" <?php if($this->request->query('status') == 'E'){echo 'selected';}?>><?=__("Expire");?></option>
								</select>
                            </div>
							<div id="search" class="col-md-3 col-sm-2 col-xs-12">
								<input type="text" id="search_value" name="search_value" value="<?= $this->request->query('search_value'); ?>" class="form-control col-md-7 col-xs-12">
                            </div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<button type="submit" class="btn btn-primary"><?=__("Search");?></button>
								<button type="button" class="btn " onclick="form_reset()" style="margin-left:3px;" ><?=__("Reset");?></button>
                            </div>
                        </div>
						<input type="hidden" name="export" id="export" />
						<input type="hidden" id="sort_value" name="sort_value" value="<?= $this->request->query('sort_value'); ?>">
						<input type="hidden" id="order_value" name="order_value" value="<?= $this->request->query('order_value'); ?>">
						<input type="hidden" id="page" name="page" value="<?= $this->request->query('page'); ?>">
                    </form>
					<?php echo $this->Form->create('');?>
					<?php echo $this->Form->end();?>
					<div class="form-group ">
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>총 신청한 금액 (취소 제외): </p>
							<input type="text" readonly value="<?= number_format($total_quantity_amount->total_quantity_amount);?> TP3" class="form-control col-md-7 col-xs-12">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>총 지급한 금액 : </p>
							<input type="text" readonly value="<?= number_format($total_send_amount->total_send_amount,2);?> KRW" class="form-control col-md-7 col-xs-12">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>총 인출한 금액 : </p>
							<input type="text" readonly value="<?= number_format($total_withdrawal_amount->total_withdrawal_amount,2);?> KRW" class="form-control col-md-7 col-xs-12">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>총 취소 금액 : </p>
							<input type="text" readonly value="<?= number_format($total_cancel_amount->total_cancel_amount);?> TP3" class="form-control col-md-7 col-xs-12">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>신청 금액 : </p>
							<input type="date" value="<?=date('Y-m-d');?>" class="form-control" onchange="get_date_total_quantity(this.value)" id="date_total_quantity">
							<input type="text" readonly value="0" class="form-control col-md-7 col-xs-12" id="today_total_quantity">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<p>지급 금액 : </p>
							<input type="date" value="<?=date('Y-m-d');?>" class="form-control" onchange="get_date_total_profits(this.value)" id="date_total_profits">
							<input type="text" readonly value="0" class="form-control col-md-7 col-xs-12" id="today_total_profits">
						</div>
					</div>
					<div class="clearfix"></div>
                    <div id="transferHistory" class="m-t-10 table-responsive">
						<div class="dropdown m-t-10 m-b-15">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
								<li><a href="javascript:void(0)" id="handleAuthClick" onclick="handleAuthClick('createSheets')" ><?=__('GoogleSheet');?></a></li>
							</ul>
							<a href="javascript:void(0)" style="" class="btn btn-info" onclick="change_status_all()"><?=__("Accept All");?></a>
							<a href="javascript:void(0)" style="float:right; display:none;" class="btn btn-danger m-b-20 m-l-3" id="cancel_btn" onclick="cancel()" ><?=__("Cancel");?></a>
							<a href="javascript:void(0)" style="float:right; display:none;" class="btn btn-danger m-b-20" id="dropout_btn" onclick="dropout()" >중도 취소</a>
						</div>
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr>
									<th style="color:#fff"><input type="checkbox" id="all_chk_btn"></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('DepositApplicationList.id')"><?= __('#');?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('investment_number')"><?= __("Investment Stage");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('u.id')"><?= __("User ID");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('u.name')"><?= __("Name");?></a></th>
									<th style="color:#fff"><?= __('Cellphone')?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('quantity')"><?= __("Application quantity");?></a></th>
									<th style="color:#fff"><?= __('Assets name')?></th>
									<!--<th style="color:#fff"><?= __('Previous Balance')?></th>-->
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('service_period_month')"><?= __("Service Period Month");?></a></th>
									<th style="color:#fff"><?= __('Status')?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('DepositApplicationList.created')"><?= __("Application Date");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('approval_date')"><?= __("Approval Date");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('cancelled_date')"><?= __("Cancelled Date");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('number_of_received')"><?= __("Number Of Received");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('amount_received')"><?= __("Amount Received");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('total_withdrawal_amount')"><?= __("Total Withdrawal Amount");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('total_amount_received')"><?= __("Total Amount Received");?></a></th>
								</tr>
                            </thead>
                            <tbody id="transferHistoryList">
                            <?php
                            $count= 1;

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['user_id'], 1, '투자 목록 조회 (이름, 전화번호)');
                                if($k%2==0) $class="odd";
                                else $class="even";
								if ($data['status'] == 'A'){
									$btn_text = __("Approval");
									$btn_cls = 'btn-warning';
									$disabled = 'disabled';
								} else if ($data['status'] == 'P'){
									$btn_text = __("Pending2");
									$btn_cls = 'btn-info';
									$disabled = '';
								} else if($data['status'] == 'C'){
									$btn_text = __("Cancelled");
									$btn_cls = 'btn-danger';
									$disabled = 'disabled';
								} else {
									$btn_text = __("");
									$btn_cls = 'btn-primary';
									$disabled = '';
								}

                                ?>
                                <tr class="<?=$class?>">
									<td ><input type="checkbox" id="" name="id[]" class="chk" value="<?php echo $data['id']; ?>"></td>
                                    <td><?php echo $data['id']; ?></td>
									<td><?php echo $data['investment_number'] != null ? $data['investment_number'] : '' ;?></td>
                                    <td><?php echo $data['user_id']; ?> </td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['u']['name']); ?></a></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['u']['phone_number']);?></a></td>
                                    <td><?php echo number_format($data['quantity']);?></td>
                                    <td><?php echo $data['unit'];?></td>
                                    <!--<td><?php echo $data['previous_balance'] == null ? 0 : number_format($data['previous_balance']) ;?> </td>-->
                                    <td><?php echo $data['service_period_month'];?><?=__('days')?></td>
                                    <td>
										<button type="button" class="btn btn-xs <?=$btn_cls;?>" onclick="change_status(<?=$data["id"];?>)" <?=$disabled;?>><?=$btn_text;?></button>
									</td>
                                    <td><?php echo $data['created']->format('Y-m-d H:i:s');?></td>
									<td><?php echo $data['approval_date'] != null ? $data['approval_date']->format('Y-m-d H:i:s') : '' ;?></td>
									<td><?php echo $data['cancelled_date'] != null ? $data['cancelled_date']->format('Y-m-d H:i:s') : '' ;?></td>
									<td><?php echo $data['number_of_received'] != null ? $data['number_of_received'] : 0 ;?></td>
									<td><?php echo $data['amount_received'] != null ? number_format($data['amount_received']) : 0 ;?></td>
									<td><?php echo $data['total_withdrawal_amount'] != null ? number_format($data['total_withdrawal_amount']) : 0 ;?></td>
									<td><?php echo $data['total_amount_received'] != null ? number_format($data['total_amount_received']) : 0 ;?></td>
                                </tr>
                                <?php $count++; } ?>
                            <?php  if(count($listing->toArray()) < 1) {
								echo '<tr class="even"><td colspan = "17" style="text-align: center;">'.__("No record found").'</td></tr>';
                            } ?>
                            </tbody>
                        </table>
                        <?php 
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'DepositApplication', 'action' => 'depositapplicationlist')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";

							// the 'first' page button
							$paginator = $this->Paginator;
							echo $paginator->first(__("First"));

							// 'prev' page button,
							// we can check using the paginator hasPrev() method if there's a previous page
							// save with the 'next' page button
							if($paginator->hasPrev()){
								//echo $paginator->prev(__("Prev"));
							}

							// the 'number' page buttons
							echo $paginator->numbers(array('modulus' => 9));

							// for the 'next' button
							if($paginator->hasNext()){
								//echo $paginator->next(__("Next"));
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
	$(document).ready(function(){
		getData();
		get_date_total_quantity($('#date_total_quantity').val());
		get_date_total_profits($('#date_total_profits').val());
	})
	function export_f(v) {
		$('#export').val(v);
		$("#frm").attr('method','post');
		$("#frm").submit();
		$("#frm").attr('method','get');
		$('#export').val('');
	}
	function change_status(id){
		if(confirm("<?=__('Are you sure you want to approve?')?>")){
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/changedepositapplicationstatus',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : id
				},
				success:function(resp) {
					if(resp == 'success'){
						location.reload();
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	
	$('#all_chk_btn').click(function(){
		if($(this).prop('checked')==true){
			$('.chk').prop('checked',true);
		} else {
			$('.chk').prop('checked',false);
		}
		cancel_btn_status();
	})
	$('.chk').click(function(){
		if($(this).prop('checked')==false){
			$('#all_chk_btn').prop('checked',false);
		}
		cancel_btn_status();
	})
	function cancel_btn_status(){
		var cnt = $('input[name="id[]"]:checked').length;
		if(cnt > 0){
			$('#cancel_btn').show();
			$('#dropout_btn').show();
		} else {
			$('#cancel_btn').hide();
			$('#dropout_btn').hide();
		}
	}
	function cancel(){
		var cancel_id = [];
		$('input[name="id[]"]:checked').each(function(){
			cancel_id.push($(this).val());
		})
		if(confirm("<?=__('Are you sure you want to cancel?')?>")){
			$.ajax({
				type: 'post',
				url: "<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'cancel']);?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : cancel_id
				},
				success:function(resp) {
					console.log(resp);
					if(resp == 'success'){
						location.reload();
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}

			});
		}
	}
	function dropout(){
		var cancel_id = [];
		$('input[name="id[]"]:checked').each(function(){
			cancel_id.push($(this).val());
		})
		if(confirm("30% 제외 후 반환 됩니다. 취소하시겠습니까?")){
			$.ajax({
				type: 'post',
				url: "<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'dropout']);?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : cancel_id
				},
				success:function(resp) {
					console.log(resp);
					if(resp == 'success'){
						location.reload();
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}

			});
		}
	}
	function change_status_all(){
		if(confirm("<?=__('Do you want to approve all except cancellation and expiration?');?>")){
			$.ajax({
				type: 'post',
				url: "<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'changestatusall']);?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					
				},
				dataType : 'json',
				success:function(resp) {
					console.log(resp);
					if(resp.success == 'true'){
						alert(resp.message + " <?=__('Approval');?> <?=__('Completed');?>");
						location.reload();
					} else if (resp.success == 'fail'){
						alert(resp.message);
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	function get_inumber_list(value){
		$('#frm').submit();
	}
	function sort(order_value){
		var sort_value = $('#sort_value').val();
		if(sort_value == 'desc'){
			sort_value = 'asc';
		} else {
			sort_value = 'desc';
		}
		$('#sort_value').val(sort_value);
		$('#order_value').val(order_value);
		$('#frm').submit();
	}
	function form_reset(){
		$('#frm')[0].reset();
		$('#investment_number').prop('selectedIndex',0);
		$('#status').prop('selectedIndex',0);
		$('#search_value').val('');
		$('#sort_value').val('');
		$('#order_value').val('');
		$('#export').val('');
		$('#page').val('');
		$('#frm').submit();
	}
	function get_date_total_quantity(days){
		$.ajax({
			type: 'post',
			url: "<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'getdatetotalquantity']);?>",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"days" : days,
			},
			dataType : 'json',
			success:function(resp) {
				if(resp.status == 'success'){
					$('#today_total_quantity').val(resp.total_quantity + ' TP3');
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}
	function get_date_total_profits(days){
		$.ajax({
			type: 'post',
			url: "<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'getdatetotalprofits']);?>",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"days" : days,
			},
			dataType : 'json',
			success:function(resp) {
				if(resp.status == 'success'){
					$('#today_total_profits').val(resp.total_profits + ' KRW');
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}
</script>
<script type="text/javascript">
	var clickCheck = false;
	var list;
	// Client ID and API key from the Developer Console
	var CLIENT_ID = '1081669061111-pi22oogik92v430m21r1fltcm8t3l739.apps.googleusercontent.com'; 
	var API_KEY = 'AIzaSyAqCfgqxRsfOQXIv1a6zXLX7tlqSYhvW5w'; 

	// Array of API discovery doc URLs for APIs used by the quickstart
	var DISCOVERY_DOCS = ["https://sheets.googleapis.com/$discovery/rest?version=v4"];

	// Authorization scopes required by the API; multiple scopes can be
	// included, separated by spaces.
	var SCOPES = "https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file";
	/**
	*  On load, called to load the auth2 library and API client library.
	*/
	function handleClientLoad() {
		gapi.load('client:auth2', initClient);
	}

	/**
	*  Initializes the API client library and sets up sign-in state
	*  listeners.
	*/
	function initClient() {
		gapi.client.init({
			apiKey: API_KEY,
			clientId: CLIENT_ID,
			discoveryDocs: DISCOVERY_DOCS,
			scope:  SCOPES
		}).then(function () {
		// Listen for sign-in state changes.
			gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
			// Handle the initial sign-in state.
			updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
		}, function(error) {
			appendPre(JSON.stringify(error, null, 2));
		});
	}

	/**
	*  Called when the signed in status changes, to update the UI
	*  appropriately. After a sign-in, the API is called.
	*/
	function updateSigninStatus(isSignedIn) {
		if (isSignedIn) {
			//$('#handleAuthClick').css('display','none');
			//$('#GoogleSheet').css('display','block');
			if(clickCheck == true){
				//createSheets();
			}
        } else {
			//$('#handleAuthClick').css('display','block');
			//$('#GoogleSheet').css('display','none');
        }
	}

	/**
	*  Sign in the user upon button click.
	*/
	function handleAuthClick(chk) {
		gapi.auth2.getAuthInstance().signIn();
		if(chk=='createSheets'){
			clickCheck = true;
			createSheets();
		}
	}

	/**
	*  Sign out the user upon button click.
	*/
	function handleSignoutClick(event) {
		gapi.auth2.getAuthInstance().signOut();
	}

	function createSheets(){
		var data = JSON.parse(dataValue(list));
		var timestamp = new Date().getTime();
		var title = "DepositApplicationList_" + timestamp;
		var spreadsheetBody = {
			// TODO: Add desired properties to the request body.
			"properties": {
					"title": title,
				},
			"sheets": [
				{
				"columnGroups": [
					{
					  "range": {
						"startIndex": 0,
						"endIndex": 10
					  }
					}
				  ],
				  "properties": {
					"title": "DepositApplicationList"
				  },
				  "data": data
			   }
			]
		};
		var request = gapi.client.sheets.spreadsheets.create({},spreadsheetBody);
		request.then(function(response) {
			// TODO: Change code below to process the `response` object:
			//console.log(response);
			//console.log('Spreadsheet ID: ' + response.result.spreadsheetId);
			if(response.result.spreadsheetId != ''){
				if(confirm("<?=__('Do you want to go to Google Spreadsheet?')?>")){
					window.open(response.result.spreadsheetUrl);
				}
			}
		}, function(reason) {
			console.error('error: ' + reason.result.error.message);
		});
	}
	function getData(){
		$.ajax({
            type: 'post',
            url: '/tech/deposit-application/depositapplicationlist',
			dataType : 'json',
            data: "",
            success:function(resp) {
				list = resp;
            }, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		    }

        });
	}

	function dataValue(obj){
		var columnName = ['#','User Id','User Name','Phone number','Quantity','Unit','Previous Balance','Service Period Month','Status','Created','Approval Date','Cancelled Date','Amount Received','Number Of Received','Total Withdrawal Amount','Investment Number'];
		var data = '[';
		for(var j = 0; j < columnName.length; j++){ // 컬럼수만큼
			for(var i = 0; i < obj.length; i++){
				var id = obj[i]['id'];
				var user_id = obj[i]['user_id'];
				var name = obj[i]['u']['name'];
				var phone_number = obj[i]['u']['phone_number'];
				var quantity = comma(obj[i]['quantity']);
				var unit = obj[i]['unit'];
				var previous_balance = obj[i]['previous_balance'] == null ? '0' : comma(obj[i]['previous_balance']);
				var service_period_month = obj[i]['service_period_month'];
				var status = obj[i]['status'];
				var created = obj[i]['created'].split("+")[0].replace("T"," ");
				var approval_date = obj[i]['approval_date'] == null ? '' : obj[i]['approval_date'].split("+")[0].replace("T"," ");
				var cancelled_date = obj[i]['cancelled_date'] == null ? '' : obj[i]['cancelled_date'].split("+")[0].replace("T"," ");
				var amount_received = obj[i]['amount_received'] == null ? 0 : comma(obj[i]['amount_received']);
				var number_of_received = obj[i]['number_of_received'];
				var total_withdrawal_amount = obj[i]['total_withdrawal_amount'] == null ? 0 : comma(obj[i]['total_withdrawal_amount']);
				var investment_number = obj[i]['investment_number'];

				if(i == 0){
					if(j > 0){ data += ',';}
					data += '{"startRow": '+i+',"startColumn": '+j+',"rowData": [';
					data += '{"values": [{"userEnteredValue": {"stringValue": "'+columnName[j]+'"}}]}';
				}
				if(j == 0){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+id+'"}}]}';
				} else if(j == 1){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+user_id+'"}}]}';
				} else if(j == 2){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+name+'"}}]}';
				} else if(j == 3){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+phone_number+'"}}]}';
				} else if(j == 4){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+quantity+'"}}]}';
				} else if(j == 5){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+unit+'"}}]}';
				} else if(j == 6){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+previous_balance+'"}}]}';
				} else if(j == 7){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+service_period_month+'"}}]}';
				} else if(j == 8){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+status+'"}}]}';
				} else if(j == 9){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+created+'"}}]}';
				} else if(j == 10){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+approval_date+'"}}]}';
				} else if(j == 11){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+cancelled_date+'"}}]}';
				} else if(j == 12){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+amount_received+'"}}]}';
				} else if(j == 13){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+number_of_received+'"}}]}';
				} else if(j == 14){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+total_withdrawal_amount+'"}}]}';
				} else if(j == 15){
					data += ',{"values": [{"userEnteredValue": {"stringValue": "'+investment_number+'"}}]}';
				}
				if(i == obj.length-1){
					data +=']}';
				}
			}
		}
		data += ']';
		return data;
	}

	function comma(x){
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>
<script async defer src="https://apis.google.com/js/api.js"
	onload="this.onload=function(){};handleClientLoad()"
	onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
