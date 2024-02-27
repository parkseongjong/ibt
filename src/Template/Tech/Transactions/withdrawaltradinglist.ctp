<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawaltradinglist']);  ?>"><?= __('Users Withdrawal Trading Accouunt List')?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home')?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawaltradinglist']);  ?>"><?= __('Users Withdrawal Trading Account List')?> </a></li>
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
                            <div id="selectrec" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_id',array('empty'=>__('Please select record number'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"list_no")); ?>
                            </div>
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('id'=>'start_date','placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('id'=>'end_date','placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('',array('id'=>'total_amount','placeholder'=>__('Total Amount Withdrawn: '),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','readonly'=>'readonly','value'=>__('Total Amount Withdrawn: ').number_format(abs($totalWithdrawnAmount)) . ' KRW')); ?>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search')?></button>
                            </div>
                        </div>
                    </form>
					<div class="clearfix"></div>
					<div style="position: relative;top: 1px;margin: 0 auto;left: 541px;">
						<button type="button" class="btn" onclick="set_date(1)"><?= __('Today');?></button>
						<button type="button" class="btn" onclick="set_date(7)"><?= __('One Week');?></button>
						<button type="button" class="btn" onclick="set_date(30)"><?= __('One Month');?></button>
					</div>
					<div class="clearfix"></div>
                    <div class="dropdown m-t-10 m-b-20">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export')?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <?= $this->Flash->render() ?>
                    <div id="transferList" class="table-responsive" style="display:none;">
                        <table class="two-axis table" id="searchData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Annual Member')?></th>
                                <th style="color:#fff"><?= __('User Bank Name')?></th>
                                <th style="color:#fff"><?= __('User Bank Account Number')?></th>
                                <th style="color:#fff"><?= __('Currency')?></th>
                                <th style="color:#fff"><?= __('Total Amount')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Fees')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>
                                <th style="color:#fff"><?= __('Action')?></th>
                            </tr>
                            <thead>
                            <tbody>
                            <tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;">
                                <td colspan="2">&nbsp;</td>
                                <td ><?= __('Please select user')?></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div id="transferHistory" class="mt10 table-responsive">
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Annual Member')?></th>
                                <th style="color:#fff"><?= __('User Bank Name')?></th>
                                <th style="color:#fff"><?= __('User Bank Account Number')?></th>
                                <th style="color:#fff"><?= __('Currency')?></th>
                                <th style="color:#fff"><?= __('Total Amount')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Fees')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>
								<th style="color:#fff"><?= __('Updated at')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>
                                <th style="color:#fff"><?= __('Action')?></th>
                            </tr>

                            <thead>
                            <tbody id="transferHistoryList">

                            <?php

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['user_id'], 1, '고객 KRW 출금 요청 목록 조회 (이름, 번호, 계좌)');
                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>">
                                    <td><?= $data['id']; ?></td>
                                    <td><?= $data['user_id']; ?></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N', $data['user']['name']); ?></a></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P', $data['user']['phone_number']); ?></a></td>
                                    <td><?php if($data['user']['annual_membership'] == 'Y'){ echo "✔"; } else { echo "✗"; } ?></td>
                                    <td><?= __($data['user']['bank']); ?></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'B',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('B',$this->Decrypt($data['user']['account_number'])); ?></a></td>
                                    <td><?= $data['cryptocoin']['short_name']; ?></td>
                                    <td><?= number_format((float)$data['coin_amount'] * -1,2);?> </td>
                                    <td><?= number_format((float)$data['amount'],2);?> </td>
                                    <td><?= number_format((float)$data['fees'],2);?> </td>
                                    <td><?= $data['created']->format('Y-m-d H:i:s');?> </td>
									<td><?= $data['updated'] != null ? $data['updated'] : '';?> </td>
                                    <td class=" ">
                                        <input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['status']; ?>" />
                                        <a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(this,<?php echo $data['id'] ?>)">
                                            <?php
                                            if($data['status'] == 'completed'){
                                                echo '<button type="button" class="btn btn-success btn-xs">'.__("Completed").'</button>';
                                            } else if ($data['status'] == 'deleted'){
                                                echo '<button type="button" class="btn btn-success btn-xs" disabled>'.__("Deleted").'</button>';
                                            }else {
                                                echo '<button type="button" class="btn btn-danger btn-xs">'.__("Pending").'</button>';
                                            }
                                            ?></a>
                                    </td>
                                    <td>
                                        <input type="hidden" id="user_del_status_<?= $data['id'] ?>" value ="<?= $data['status']; ?>" />
                                        <a href="javascript:void(0)" id="del_status_id_<?= $data['id']; ?>" onclick="change_del_status_fix(<?php echo $data['id'] ?>,'transferHistory')">
                                            <?php
                                            if($data['status'] == 'deleted'){
                                                echo '<button type="button" class="btn btn-success btn-xs" disabled>'.__("Deleted").'</button>';
                                            }else{
                                                echo '<button type="button" class="btn btn-danger btn-xs">'.__("Delete").'</button>';
                                            }
                                            ?></a>
                                    </td>
                                </tr>
                                <?php } ?>
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
							$this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'withdrawaltradinglist')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";
							$paginator = $this->Paginator;
							echo $paginator->first(__("First"));
							if($paginator->hasPrev()){
								//echo $paginator->prev(__("Prev"));
							}
							echo $paginator->numbers(array('modulus' => 9));
							if($paginator->hasNext()){
								//echo $paginator->next(__("Next"));
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
    $(document).ready(function(){
		var dateFormat = "yyyy-mm-dd",
		from = $( "#start_date" )
			.datepicker({
			defaultDate: "+1m",
			changeMonth: true,
			numberOfMonths: 3,
			format: dateFormat,
			onSelect: function(){
				var edate = new Date($( "#end_date" ).val());
				$( "#end_date" ).datepicker({
					format: dateFormat,
					 minDate:edate
				});
			}
		})
		.on( "change", function() {
			to.datepicker( "option", "minDate", getDate( this ) );
		}),
		to = $( "#end_date" ).datepicker({
			defaultDate: "+1m",
			changeMonth: true,
			numberOfMonths: 3,
			format: dateFormat,
			onSelect: function(){
				var sdate= new Date($( "#start_date" ).val());
				$( "#start_date" ).datepicker({
					 maxDate:sdate,
					 format: dateFormat,
				});
			}

		})
		.on( "change", function() {
			from.datepicker( "option", "maxDate", getDate( this ) );
		});
		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}
			return date;
		}
		list_select2('list_no','withdrawaltradinglist');

        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
    });


    function change_user_status(getThis,id){
        // var status = $("#user_status_"+id).val();
        var status = $(getThis).prev().val();
        if(status === 'completed'){
            var ques = "<?=__("Do you want to change the status to pending?");?>";
            var status = "pending";
            var change = '<button type="button" class="btn btn-danger btn-xs"><?=__("Pending");?></button>'
        } else if(status === 'deleted'){
            var status = "deleted";
            var change = '<button type="button" class="btn btn-danger btn-xs" disabled><?=__("Deleted");?></button>'
        } else{
            var ques= "<?=__("Do you want to change the status to completed?");?>";
            var status = "completed";
            var change = '<button type="button" class="btn btn-success btn-xs"><?=__("Completed");?></button>';
        }

        bootbox.confirm(ques, function(result) {
            if(result === true){
                jQuery.ajax({
                    url: '<?php echo $this->Url->build(['controller'=>'transactions','action'=>'statustradingwallet']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
                    data: {'id':id,'status':status},
                    type: 'POST',
                    success: function(data) {
                        if(data == 1){
                            jQuery(getThis).html(change);
                            jQuery(getThis).prev().val(status);
                            new PNotify({
                                title: '<?=__("Success!");?>',
                                text: '<?=__("Status changed successfully!");?>',
                                type: 'success',
                                styling: 'bootstrap3',
                                delay:1200
                            });
                        }
                        if(data === 'forbidden'){
                            new PNotify({
                                title: '<?=__("403 Error");?>',
                                text: '<?=__("You do not have permission to perform this action");?>',
                                type: 'error',
                                styling: 'bootstrap3',
                                delay:1200
                            });

                        }
                    }
                });
            }
        });
    }

    function change_del_status_fix(id,type){
        var targetId = '';
        var targetId2 = '';

        if(type == 'transferHistory'){
            targetId = $('#transferHistory #del_status_id_'+id);
            targetId2 = $('#transferHistory #status_id_'+id);
        }
        else{
            targetId = $('#transferList #del_status_id_'+id);
            targetId2 = $('#transferList #status_id_'+id);
        }

        var status = targetId.prev().val();

        if(status === 'deleted'){
            var status = "deleted";
            var change = '<button type="button" class="btn btn-danger btn-xs" disabled><?=__("Deleted");?></button>'
        }else{
            var ques= "<?=__("Are you sure that, you want to delete this?");?>";
            var status = "deleted";
            var change = '<button type="button" class="btn btn-success btn-xs" disabled><?=__("Deleted");?></button>';
        }

        bootbox.confirm(ques, function(result) {
            if(result === true){
                jQuery.ajax({
                    url: '<?php echo $this->Url->build(['controller'=>'transactions','action'=>'statustradingwallet']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
                    data: {'id':id,'status':status},
                    type: 'POST',
                    success: function(data) {
                        if(data == 1){

                            jQuery(targetId).html(change);
                            jQuery(targetId).prev().val(status);

                            jQuery(targetId2).html(change);
                            jQuery(targetId2).prev().val(status);
                            new PNotify({
                                title: '<?=__("Success!");?>',
                                text: '<?=__("Status changed successfully!");?>',
                                type: 'success',
                                styling: 'bootstrap3',
                                delay:1200
                            });

                        }
                        if(data === 'forbidden'){

                            new PNotify({
                                title: '<?=__("403 Error");?>',
                                text: '<?=__("You do not have permission to perform this action");?>',
                                type: 'error',
                                styling: 'bootstrap3',
                                delay:1200
                            });

                        }
                    }
                });
            }
        });
    }
	function set_date(days){
		var today = $.datepicker.formatDate('yyyy-mm-dd', new Date());
		var date = new Date(); 
			date.setDate(date.getDate() - days);
		var ago = $.datepicker.formatDate('yyyy-mm-dd', date);
		if(days == 1){
			$( "#start_date" ).datepicker('setDate',today);
		} else {
			$( "#start_date" ).datepicker('setDate',ago);
		}
		$( "#end_date" ).datepicker('setDate',today);
	}
	function select_form_submit(){
		$('#frm').submit();
	}
</script>
