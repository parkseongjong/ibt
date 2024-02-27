<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
	.input-area {
		margin:30px auto;
	}
	.input-area input{
		width:45%;
		margin-left: 8px;
	}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'feecalculator']);  ?>"><?=__('Investment Profits Setting List');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'feecalculator']);  ?>"><?=__('Investment Profits Setting List');?></a></li>
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
					<div id="transferHistory" class="mt10 table-responsive" style="margin-top:50px;">
						<div id="" class="col-md-2 col-sm-2 col-xs-12">
						<form method="get" id="search_frm">
							<select id="investment_number" name="investment_number" onchange="get_inumber_list(this.value)" class="form-control">
								<option value=""><?=__('Please Select');?></option>
								<?php 
									foreach($stage_list as $sl){
								?>
									<option value="<?=$sl->stage;?>"<?php if($this->request->query('investment_number')==$sl->stage){echo "selected";}?>><?=$sl->stage;?><?=__('Stage');?></option>
								<?php 
									}
								?>

							</select>
						</form>
						</div>
						<a href="javascript:void(0)" style="margin-bottom: 20px; " class="btn btn-danger" id="" onclick="manual_payment()" ><?=__('Manual payment');?></a>
						<table class="two-axis table" id="historyData">
							<thead style="background: #d3ccea; font-size: 16px;">
								<tr>
									<th style="color:#fff">No</th>
									<th style="color:#fff"><?=__('Stage');?></th>
									<th style="color:#fff"><?=__('days');?></th>
									<th style="color:#fff"><?=__('Days Of Remain');?></th>
									<th style="color:#fff"><?=__('Earned Data (Amount)')?></th>
									<th style="color:#fff"><?=__('Number of people to be counted');?></th>
									<th style="color:#fff"><?=__('Status');?></th>
									<th style="color:#fff"><?=__('Date');?></th>
									<th style="color:#fff"><?=__('Last updated');?></th>
								</tr>
							</thead>
							<tbody id="transferHistoryList">
								<?php foreach($setting_list as $l) { 
									$status = '';
									$btn_cls = '';
									if($l->status == 'S'){
										$status = __("Stand By");
										$btn_cls = 'btn-warning btn-xs';
									} else if($l->status == 'O'){
										$status = __("Ongoing");
										$btn_cls = 'btn-danger btn-xs';
									} else if($l->status == 'T'){
										$status = __("Termination");
										$btn_cls = 'btn-primary btn-xs';
									} else if($l->status == 'C'){
										$status = __("Cancelled");
										$btn_cls = '';
									}
								?>
									<tr>
										<td><?=$l->id;?></td>
										<td><?=$l->investment_number;?></td>
										<td><?=$l->days;?></td>
										<td><?=$l->days_of_remain;?></td>
										<td><?=number_format($l->data);?></td>
										<td><?=$l->count_of_people;?></td>
										<td><button type="button" class="btn <?=$btn_cls;?>" <?php if ($l->status == 'S' || $l->status == 'O') { ?>onclick="cancel(<?=$l->id;?>)" <?php } ?>><?=$status;?></td>
										<td><?=$l->created;?></td>
										<td><?=$l->updated;?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'DepositApplication', 'action' => 'settinglist')+$searchArr));
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
						echo $paginator->numbers(array('modulus' => 4));

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

	function manual_payment(){
		if(confirm('<?=__("Do you want to pay manually?");?>')){
			if($('#investment_number').val() == ''){
				alert('<?=__("Please select an investment stage");?>');
				return;
			}
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/callajaxcalc',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"investment_number" : $('#investment_number').val()
				},
				dataType : "json",
				success:function(resp) {
					//console.log(resp);
					if(resp.success == 'false'){
						alert(resp.message);
					} else if(resp.success == 'true'){
						location.reload();
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	function cancel(id){
		if(confirm('<?=__("Are you sure you want to cancel?");?>')){
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/cancelsetting',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : id,
				},
				dataType : "json",
				success:function(resp) {
					if(resp.success == 'false'){
						alert(resp.message);
					} else if(resp.success == 'true'){
						location.reload();
					}
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	function fee_setting(type){
		$('#type').val(type);
		$.ajax({
			type: 'post',
			url: '/tech/deposit-application/getAmountPeriodList',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"type" : type,
			},
			dataType : "json",
			success:function(resp) {
				var html = '';
				if(type == 'stage'){
					$('#stage').empty();
					var lastElement = 1;
					$.each(resp,function(key,value){
						var isLastElement = key == resp.length -1;
						if(isLastElement){
							lastElement = value.stage+1;
						}
						var id = value.id;
						var stage = '';
						var status = '';
						var btn = '';

						if(value.stage == 0){ stage = '<?=__("Stop Stage");?>'; } 
						else { stage = value.stage + '<?=__("Stage");?>';}
						if(value.status == 'Y'){
							status = '<?=__("Ongoing");?>';
							btn = '<?=__("Stop");?>';
						} else if(value.status == 'N') {
							status = '<?=__("Stand By");?>';
							btn = '<?=__("Proceed");?>';
						}
						var created = value.created != null ? value.created.split("+")[0].replace("T"," ") : '' ;

						html += '<tr>';
						html += '<td>'+stage+'</td>';
						html += '<td>'+status+'</td>';
						html += '<td>'+created+'</td>';
						html += '<td><button type="button" class="btn btn-xs btn-warning" onclick="stage_change('+id+',\''+value.status+'\')">'+btn+'</button></td>';
						html += '</tr>';
					});
					for(var i = lastElement; i < lastElement+5; i++){
						$('#stage').append($('<option>', { 
							value: [i],
							text : [i]+'<?=__("Stage");?>' 
						}));
					}
					$('#modal_stage_tbody').html(html);
					$('#myModalStage').css('display','block');
				} else if(type == 'amount' || type == 'period') {
					$('#type_th').text(type);
					$.each(resp,function(key,value){
						var id = value.id;
						if(type == 'amount'){
							var contents = numberWithCommas(value.amount) + ' <?=__("WON");?>';
						} else if (type == 'period'){
							var contents = value.period + ' <?=__("days");?>';
						}
						var fee = value.fee;

						html += '<tr>';
						html += '<td>'+contents+'</td>';
						html += '<td>'+fee+'%</td>';
						html += '<td><button type="button" class="btn btn-xs btn-warning" onclick="delete_setting('+id+')">삭제하기</button></td>';
						html += '</tr>';
					});
					$('#modal_tbody').html(html);
					$('#myModal').css('display','block');
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}
	function add_fee_setting(){
		if($('#type').val() == ''){
			alert('<?=__("Please refresh and try again");?>');
			return;
		}
		if($('#contents_value').val() == ''){
			alert('<?=__("Please enter a value");?>');
			$('#contents_value').focus();
			return
		}
		if($('#fee').val() == ''){
			alert('<?=__("Please enter a fee");?>');
			$('#fee').focus();
			return;
		}

		$.ajax({
			type: 'post',
			url: '/tech/deposit-application/addFeeSetting',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"type" : $('#type').val(),
				"contents_value" : $('#contents_value').val(),
				"fee" : $('#fee').val(),
			},
			//dataType : "json",
			success:function(resp) {
				$('#contents_value').val('');
				$('#fee').val('');
				fee_setting($('#type').val());
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
		
		
	}
	function delete_setting(id){
		if($('#type').val() == ''){
			alert('<?=__("Please refresh and try again");?>');
			return;
		}
		if(confirm('<?=__("Are you sure that, you want to delete this?");?>')){
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/deleteFeeSetting',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"type" : $('#type').val(),
					"id" : id,
				},
				//dataType : "json",
				success:function(resp) {
					fee_setting($('#type').val());
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	function stage_change(id,status){
		if(confirm('<?=__("Do you really want to change?");?>')){
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/stagechange',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : id,
					"status" : status
				},
				//dataType : "json",
				success:function(resp) {
					fee_setting('stage');
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}

	function add_stage(){
		if(confirm('<?=__("Do you really want to add?");?>')){
			$.ajax({
				type: 'post',
				url: '/tech/deposit-application/addstage',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"stage" : $('#stage').val(),
				},
				//dataType : "json",
				success:function(resp) {
					fee_setting('stage');
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
	}
	function get_inumber_list(value){
		$('#search_frm').submit();
	}
</script>
