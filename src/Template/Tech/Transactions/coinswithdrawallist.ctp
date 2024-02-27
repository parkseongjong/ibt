<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'coinswithdrawallist']);  ?>"><?=__('Users Coins Withdrawal Amount List');?> </a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?=__('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'coinswithdrawallist']);  ?>"><?=__('Users Coins Withdrawal Amount List');?> </a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm">
                        <div class="form-group">
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
							<div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search') ?></button>
                            </div>
                        </div>
                    </form>
					<div class="clearfix"></div>
                    <?= $this->Flash->render() ?>
                    <div id="transferHistory" class="table-responsive m-t-20">
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr >
									<th style="color:#fff"><?= __('#')?></th>
									<th style="color:#fff"><?= __('User ID')?></th>
									<th style="color:#fff"><?= __('Name')?></th>
									<th style="color:#fff"><?= __('Phone Number')?></th>
									<th style="color:#fff"><?= __('Wallet Address')?></th>
									<th style="color:#fff"><?= __('Coin')?></th>
									<th style="color:#fff"><?= __('Amount')?></th>
									<th style="color:#fff"><?= __('Coin Amount')?></th>
									<th style="color:#fff"><?= __('Fees')?></th>
									<th style="color:#fff"><?= __('Date & Time')?></th>
									<th style="color:#fff"><?= __('Status')?></th>
								</tr>
                            <thead>
                            <tbody id="transferHistoryList">
                            <?php

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['user_id'], 1, '고객 코인 인출 목록 조회');
                                if($k%2==0) $class="odd";
                                else $class="even";
                                ?>
                                <tr class="<?=$class?>">
                                    <td><?= $data['id']; ?></td>
                                    <td><?= $data['user_id']; ?></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
                                    <td><?= $data['wallet_address']; ?></td>
                                    <td><?= $data['cryptocoin']['short_name']; ?></td>
                                    <td><?= number_format((float)$data['coin_amount'],2); ?></td>
                                    <td><?= number_format((float)$data['amount'],2);?> </td>
                                    <td><?= number_format((float)$data['fees'],4);?> </td>
                                    <td><?=$data['created_at']->format('Y-m-d H:i:s');?> </td>
                                    <td><?= __(ucfirst($data['status']));?></td>
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
							$this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'coinswithdrawallist')+$searchArr));
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
    $(document).ready(function(){
        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
	});
</script>