<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'lists']);  ?>"> <?= __('E-Pay List');?> </a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'lists']);  ?>"><?= __('E-Pay List');?></a></li>
        </ol>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form id="frm" method="get" class="form-horizontal form-label-left input_mask">
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
                                <?php  //echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                                <select id="pagination" name="pagination" class="form-control col-md-7 col-xs-12">
                                    <option value=""><?=__("No of records");?></option>
                                    <option value="10" <?php if($this->request->query('pagination')==10){echo "selected";}?>>10</option>
                                    <option value="25" <?php if($this->request->query('pagination')==25){echo "selected";}?>>25</option>
                                    <option value="50" <?php if($this->request->query('pagination')==50){echo "selected";}?>>50</option>
                                    <option value="100" <?php if($this->request->query('pagination')==100){echo "selected";}?>>100</option>
                                </select>
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
                        <table id="table-two-axis" class="two-axis table" >
                            <thead>
				                <tr>
                                    <th>#</th>
                                    <th><?= __('Name'); ?></th>
                                    <th><?= __('Email'); ?></th>
                                    <th><?= __('User Level'); ?></th>
                                    <th><?= __('Phone Number'); ?></th>
                                    <th><?= __('ETH Address'); ?></th>
                                    <th><?= __('BTC Address'); ?></th>
                                    <th><?= __('Date of Registration'); ?></th>
                                    <th class="column-title no-link last"><span class="nobr"><?= __('Action'); ?></span>
                                </tr>
                            </thead>
                            <tbody>
								<?php
								foreach($users->toArray() as $k=>$data){
									$this->add_system_log(200, $data['id'], 1, 'E-pay 고객 조회 (이름, 전화번호, 메일)');
								?>
								<tr class="even" id="user_row_<?= $data['id']; ?>">
									<td><?= $data['id']?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
									<td><?= $data['user_level']; ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['phone_number']); ?></a></td>
									<td><?= $data['eth_address']; ?></td>
									<td><?= $data['btc_address']; ?></td>
									<td><?= $data['created']->format('Y-m-d H:i:s'); ?></td>
									<td><a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'logs',$data['id']]); ?>"  class="btn btn-info btn-xs"> <?= __('Logs'); ?> </a></td>
								</tr>
									<?php } ?>
								<?php  if(count($users->toArray()) < 1) {
									echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
								} ?>
							</tbody>
						</table>
						<?php
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'Epay', 'action' => 'lists')+$searchArr));
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
    });
// jQuery('.table-responsive').on('click','.pagination li a',function(event){
// 	event.preventDefault() ;
// 	var keyy = $('form').serialize();
// 	var urli = jQuery(this).attr('href');
// 	jQuery.ajax({
// 		url: urli,
// 		data: {key:keyy},
// 		type: 'POST',
// 		success: function(data) {
// 			if(data){
// 				jQuery('.table-responsive').html(data);
// 			}
// 		}
// 	});
// });
</script>