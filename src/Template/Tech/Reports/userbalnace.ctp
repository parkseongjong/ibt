<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'userbalnace']);  ?>"><?=__("User Balance");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'userbalnace']);  ?>"><?=__("User Balance");?></a></li>
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
								<?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
							</div>
							<div id="selectuseremail" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_email',array('empty'=>__('Please select email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_email")); ?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>$this->request->query('start_date'))); ?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('end_date'))); ?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>__('No of records'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
								<input type="hidden" name="export" id="export" />
							</div> 
							<div class="col-md-1 col-sm-1 col-xs-12">
								<button type="submit" class="btn btn-success"><?=__("Search");?></button>
							</div>
						</div>
					</form>
					<div class="clearfix"></div>
					<h3 class="w3_inner_tittle two"></h3>
					<div class="dropdown m-b-15">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
							<tr> 
								<th><?=__("ID");?></th>
								<th><?=__("Name");?></th>
								<th><?=__("Email");?></th>
								<th><?=__("Phone Number");?></th>
								<th><?=__("User Level");?></th>
								<th>TP3 <?=__("Main Balance");?></th>
								<th>TP3 <?=__("Trading Balance");?></th>
								<th>CTC <?=__("Main Balance");?></th>
								<th>CTC <?=__("Trading Balance");?></th>
								<th>ETH <?=__("Main Balance");?></th>
								<th>ETH <?=__("Trading Balance");?></th>
								<th>BTC <?=__("Main Balance");?></th>
								<th>BTC <?=__("Trading Balance");?></th>
								<th>USDT <?=__("Main Balance");?></th>
								<th>USDT <?=__("Trading Balance");?></th>
								<th>MC <?=__("Main Balance");?></th>
								<th>MC <?=__("Trading Balance");?></th>
								<th>XRP <?=__("Main Balance");?></th>
								<th>XRP <?=__("Trading Balance");?></th>
								<th>BNB <?=__("Main Balance");?></th>
								<th>BNB <?=__("Trading Balance");?></th>
								<th>KRW <?=__("Main Balance");?></th>
								<th>KRW <?=__("Trading Balance");?></th>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num;
                        foreach($users->toArray() as $k=>$data){
							$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
							$getUserTransactions = $this->Custom->getBalance("TP3",$data['id']);
							$getUserTransactions1 = $this->Custom->getBalance("CTC",$data['id']);
							$getUserTransactions2 = $this->Custom->getBalance("ETH",$data['id']);
							$getUserTransactions3 = $this->Custom->getBalance("BTC",$data['id']);
							$getUserTransactions4 = $this->Custom->getBalance("USDT",$data['id']);
							$getUserTransactions5 = $this->Custom->getBalance("MC",$data['id']);
							$getUserTransactions6 = $this->Custom->getBalance("XRP",$data['id']);
							$getUserTransactions7 = $this->Custom->getBalance("BNB",$data['id']);
							$getUserTransactions8 = $this->Custom->getBalance("KRW",$data['id']);
						
							if($k%2==0) $class="odd";
							else $class="even";
                        ?>
                        <tr style="text-align: center; vertical-align: middle;" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?= $data['id'];?></td>
                            <td width="60"><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
                            <td width="100"><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
                            <td width="100"><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['phone_number']); ?></a></td>
                            <td><?= $data['user_level']; ?></td>
                            <td><?= $getUserTransactions['principalBalance'];?></td>         
							<td><?= $getUserTransactions['withdrawBalance'];?></td>       
                            <td><?= $getUserTransactions1['principalBalance'];?></td>         
							<td><?= $getUserTransactions1['withdrawBalance']; ?></td>   
							<td><?= $getUserTransactions2['principalBalance']; ?></td>         
							<td><?= $getUserTransactions2['withdrawBalance']; ?></td>         
							<td><?= $getUserTransactions3['principalBalance']; ?></td>         
							<td><?= $getUserTransactions3['withdrawBalance']; ?></td> 
							<td><?= $getUserTransactions4['principalBalance']; ?></td>         
							<td><?= $getUserTransactions4['withdrawBalance']; ?></td> 
							<td><?= $getUserTransactions5['principalBalance']; ?></td>         
							<td><?= $getUserTransactions5['withdrawBalance']; ?></td>
							<td><?= $getUserTransactions6['principalBalance']; ?></td>         
							<td><?= $getUserTransactions6['withdrawBalance']; ?></td>
							<td><?= $getUserTransactions7['principalBalance']; ?></td>         
							<td><?= $getUserTransactions7['withdrawBalance']; ?></td>
							<td><?= $getUserTransactions8['principalBalance']; ?></td>         
							<td><?= $getUserTransactions8['withdrawBalance']; ?></td>
                        </tr>
                            <?php $count++; } ?>
                        <?php  if(count($users->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'userbalnace')+$searchArr));
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
<input type="hidden" id="user_email_search" name="" value="<?=$this->request->query('user_email');?>">
<script>
	$(document).ready(function() {
        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
		user_email_select2('user_email'); /* user email search */
		email_ajax_check('user_email'); // 검색 후 selected 처리
		datepicker_set('start-date');
		datepicker_set('end-date');
    });
</script>