<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'transactionsdetails']);  ?>"><?=__("Users Transactions Details");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'transactionsdetails']);  ?>"><?=__("Users Transactions Details");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" class="form-horizontal form-label-left input_mask" id="frm">
                        <div class="form-group">
							<div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
							</div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  //echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
								<input type="text" id="start_date" name="start_date" value="<?=(!empty($_GET['start_date']) ? $_GET['start_date'] : "")?>" class="form-control col-md-7 col-xs-12 has-feedback-left">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  //echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
								<input type="text" id="end_date" name="end_date" value="<?=(!empty($_GET['end_date']) ? $_GET['end_date'] : "")?>" class="form-control col-md-7 col-xs-12 has-feedback-left">
                                <input type="hidden" name="export" id="export" />
								<input type="hidden" name="export_date" id="export_date" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="button" class="btn btn-success" onclick="list_search()"><?=__("Search")?></button>
                            </div>
                        </div>
						<div style="position: relative;top: -10px;margin: 0 auto;left: 264px;">
							<button type="button" class="btn" onclick="set_date(1)"><?=__("Today")?></button>
							<button type="button" class="btn" onclick="set_date(7)"><?=__("One Week")?></button>
							<button type="button" class="btn" onclick="set_date(30)"><?=__("One Month")?></button>
						</div>
                    </form>
                    <div class="dropdown m-b-20" >
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_csv('c')">CSV</a></li>
                        </ul>
						<select class="btn btn-primary m-l-3" onchange="month_export(this.value)" id="select_export_month">
							<option value=""><?=__("Select Month");?></option>
							<?php
								$date1 = $select_start_date;
								$date2 = $select_end_date;
								$new_date = date("Y-m", strtotime("-1 month", strtotime($date1)));
								while(true) {
									 $new_date = date("Y-m", strtotime("+1 month", strtotime($new_date)));
									 echo '<option value="'.$new_date.'">'.$new_date.'</option>';
									 if($new_date == $date2) break;
								}
							?>
						</select>
                    </div>
                    <div id="transferHistory" class="mt10 table-responsive" >
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('User ID')?></th>
                                <th style="color:#fff"><?= __('User Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Wallet Address')?></th>
                                <th style="color:#fff"><?= __('Transaction ID')?></th>
                                <th style="color:#fff"><?= __('Coin')?></th>
                                <th style="color:#fff"><?= __('Coin Amount')?></th>
                                <th style="color:#fff"><?= __('Type')?></th>
                                <th style="color:#fff"><?= __('Remark')?></th>
                                <th style="color:#fff"><?= __('Description')?></th>
                                <th style="color:#fff"><?= __('Current Balance')?></th>
                                <th style="color:#fff"><?= __('Exchange ID')?></th>
                                <th style="color:#fff"><?= __('Exchange History ID')?></th>
                                <th style="color:#fff"><?= __('Fees')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>
                                <th style="color:#fff"><?= __('Date & Time Created')?></th>
                                <th style="color:#fff"><?= __('Date & Time Updated')?></th>
                            </tr>

                            <thead>
                            <tbody id="transferHistoryList">
                            <?php
								foreach($listing->toArray() as $k=>$data){
									$this->add_system_log(200, $data['user_id'], 1, '트랜잭션 로그 조회 (이름, 전화번호, 지갑주소)');
									if($k%2==0) $class="odd";
									else $class="even";

                                ?>
                                <tr class="<?=$class?>">
                                    <td><?= $data['id']; ?></td>
                                    <td><?= $data['user_id']; ?></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
                                    <td width="100"><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
                                    <td width="100"><?= $data['wallet_address']; ?></td>
                                    <td><?= $data['transaction_id']; ?></td>
                                    <td><?= $data['cryptocoin']['short_name']; ?></td>
                                    <td><?= number_format((float)$data['coin_amount'],2);?> </td>
                                    <td><?= $this->get_transaction_txtype($data['tx_type']);?></td>
                                    <td><?= $this->get_transaction_remark($remark = $data['remark']);?></td>
                                    <td><?= $this->get_transaction_description($data['description']);?></td>
                                    <td><?= $data['current_balance']; ?></td>
                                    <td><?= $data['exchange_id']; ?></td>
                                    <td><?= $data['exchange_history_id']; ?></td>
                                    <td><?= number_format((float)$data['fees'],2);?> </td>
                                    <td><?= __(ucfirst($data['status'])); ?></td>
                                    <td><?=$data['created']->format('Y-m-d H:i:s');?> </td>
                                    <td><?=$data['updated']->format('Y-m-d H:i:s');?> </td>
                                </tr>
                                <?php } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '18' style='text-align: center;'>".__('No record found')."</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        <?php
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'users', 'action' => 'transactionsdetails')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";
							// the 'first' page button
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
	var setIntervalId ;

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
        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
    });

	function export_csv(v) {
        $('#export').val(v);
        $("#frm").submit();
        $('#export').val('');
		LoadingWithMask();
		setIntervalId = setInterval(download_check_ajax, 2000);
    }

	function list_search(){
		if(!search_check()){
			return;
		}
		$('#frm').submit();
	}

	function search_check(){
		var search_date = new Date($('#end_date').val()) - new Date($('#start_date').val());
		var millisecond = 24 * 60 * 60 * 1000;
		var day_diff = parseInt(search_date / millisecond);
		if(day_diff > 31){
			alert('최대 검색 범위는 한달입니다.');
			return false;
		}
		return true;
	}

	function month_export(value){
		if(value != ''){
			$('#export').val('c');
			$('#export_date').val(value);
			$('#frm').submit();
			$('#export').val('');
			$('#export_date').val('');
			$('#select_export_month').prop('selectedIndex',0);
			LoadingWithMask();
			setIntervalId = setInterval(download_check_ajax, 2000);
		}
	}

	function download_check_ajax(){
		$.ajax({
			type: 'post',
			url: "<?php echo $this->Url->build(['controller'=>'Users','action'=>'downloadcheckajax']);?>",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"type" : "transactionsdetails",
			},
			success:function(resp) {
				if(resp == 'success'){
					clearInterval(setIntervalId);
					closeLoadingWithMask();
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
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
</script>