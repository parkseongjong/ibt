<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'coin','action'=>'withdrawalrewardlist']);  ?>"><?= __('Withdrawn Rewards List') ?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'coin','action'=>'withdrawalrewardlist']);  ?>"><?= __('Withdrawn Rewards List') ?></a></li>
        </ol>
    </section>
<br/>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="col-lg-12 col-xl-12 center-block ">
            <div class="tab-block mb25" style="position: relative">
                <div class="tab-content pn-h-small">
                    <div id="main_wallet_panel" class="tab-pane active">
                        <div style="display: flow-root;">
                            <form id="frm" method="get" class="form-horizontal form-label-left input_mask">
                                <div class="form-group">
                                    <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                        <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name",'value'=>(!empty($_GET['user_name']) ? $_GET['user_name'] : ""))); ?>
                                    </div>
                                    <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                                        <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,'value'=>(!empty($_GET['coin_first_id']) ? $_GET['coin_first_id'] : "")));?>
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
                                    <div class="col-md-4 col-sm-1 col-xs-12">
                                        <?php  echo $this->Form->input('',array('id'=>'total_withdrawals','placeholder'=>__('Total amount withdrawn'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>__('Total Wallet ← Main Account Amount: ').number_format($adminWithdrawSumShow,2),'readonly'=>'readonly')); ?>
                                    </div>
                                </div>
                                <div class="token-button-list-checkbox-container">
                                    <label for=""><?= __('View in scroll form');?></label>
                                    <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
                                </div>
                                <div class="token-button-list-container">
                                    <?php 
										foreach($coinList as $k=>$data) { ?>
											<button type="button" onclick="form_submit('coin_first_id',<?=$k;?>)" class="btn btn-token <?php if($this->request->query('coin_first_id') == $k) {echo 'selected';}?>"><?=$data;?></button>
									<?php } ?>
                                </div>
				            </form>
                            <div class="dropdown m-b-20">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export');?><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                                </ul>
                            </div>

                            <div id="transferHistory" class="mt10 table-responsive">
                                <table class="two-axis table" id="historyData">
                                    <thead style="background: #d3ccea;    font-size: 16px;">
                                        <tr>
                                            <th style="color:#fff"><?= __('#')?></th>
                                            <th style="color:#fff"><?= __('ID')?></th>
                                            <th style="color:#fff"><?= __('Name')?></th>
                                            <th style="color:#fff"><?= __('Phone Number')?></th>
                                            <th style="color:#fff"><?= __('Coin')?></th>
                                            <th style="color:#fff"><?= __('Amount')?></th>
                                            <th style="color:#fff"><?= __('State')?></th>
                                            <th style="color:#fff"><?= __('Status')?></th>
                                            <th style="color:#fff"><?= __('Date & Time')?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="transferHistoryList">
                                        <?php $count= $serial_num;
                                            foreach($listing->toArray() as $k=>$data){
												$this->add_system_log(200, $data['user_id'], 1, 'Withdrawn Rewards List 기록 조회 (이름, 전화번호)');
                                                if($k%2==0) $class="odd";
                                                else $class="even"; ?>
												<tr class="<?=$class?>">
													<td><?= $data['id']; ?></td>
													<td><?= $data['user_id']; ?></td>
													<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
													<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
													<td><?= $data['cryptocoin']['short_name']; ?></td>
													<td><?= number_format((float)$data['amount'],2);?> </td>
													<td><?php $type = $data['type'];
														$remark = $data['remark'];
														if($type == 'withdrawal' && $remark == 'withdrawal airdrop reward'){
															echo __('Wallet ← Main Account');
														}
														?> </td>
													<td> <?= __(ucfirst($data['status']));?> </td>
													<td><?=$data['created_at']->format('Y-m-d H:i:s');?> </td>
												</tr>
                                                <?php $count++; } ?>

                                        <?php  if(count($listing->toArray()) < 1) {
                                            echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                                        } ?>
                                    </tbody>
                                </table>
                                <?php $searchArr = [];
                                foreach($this->request->query as $singleKey=>$singleVal){
                                        $searchArr[$singleKey] = $singleVal;
                                }
                                $this->Paginator->options(array('url' => array('controller' => 'coin', 'action' => 'withdrawalrewardlist')+$searchArr));
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
                                echo "</div>"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
</div>
<input type="hidden" id="user_name_search" name="" value="<?=$this->request->query('user_name');?>">
 <script>
    $(document).ready(function(){
		user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
		datepicker_set('start-date');
		datepicker_set('end-date');
        $("#coin_first_id").select2();
        tokenButtonList();
      });
</script>
