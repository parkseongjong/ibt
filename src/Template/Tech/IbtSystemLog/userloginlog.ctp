<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'IbtSystemLog','action'=>'userloginlog']);  ?>"><?=__("Log List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'IbtSystemLog','action'=>'userloginlog']);  ?>"><?=__("Log List");?></a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
					<div class="clearfix"></div>
                    <form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask" id="frm">
						<input type="hidden" name="export" id="export" />
						<input type="hidden" id="sort_value" name="sort_value" value="<?= $this->request->query('sort_value'); ?>">
						<input type="hidden" id="order_value" name="order_value" value="<?= $this->request->query('order_value'); ?>">
						<input type="hidden" id="page" name="page" value="<?= $this->request->query('page'); ?>">
                        <div class="form-group m-t-15">
							<div id="search" class="col-md-3 col-sm-2 col-xs-12">
								<input type="text" id="search_value" name="search_value" value="<?= $this->request->query('search_value'); ?>" class="form-control col-md-7 col-xs-12" placeholder="이름, 전화번호, 회원번호">
                            </div>
							<div class="col-md-3 col-sm-2 col-xs-12">
								<input type="date" id="start_date" name="start_date" value="<?= $this->request->query('start_date'); ?>" class="form-control col-md-7 col-xs-12" >
                            </div>
							<div  class="col-md-3 col-sm-2 col-xs-12">
								<input type="date" id="end_date" name="end_date" value="<?= $this->request->query('end_date'); ?>" class="form-control col-md-7 col-xs-12">
                            </div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<button type="submit" class="btn btn-primary"><?=__("Search");?></button>
								<button type="button" class="btn " onclick="form_reset()" style="margin-left:3px;" ><?=__("Reset");?></button>
                            </div>
							<div class="clearfix"></div>
							<div class="m-t-15">
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="sort_value" name="sort_value" class="form-control" onchange="sort()">
										<option value="DESC" <?php if($this->request->query('sort_value') == 'DESC'){echo "selected";}?>>최근 순</option>
										<option value="ASC" <?php if($this->request->query('sort_value') == 'ASC'){echo "selected";}?>>오래된 순</option>
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="limit" name="limit" class="form-control" onchange="sort()">
										<option value="20" <?php if($this->request->query('limit') == 20){echo "selected";}?>>20개</option>
										<option value="40" <?php if($this->request->query('limit') == 40){echo "selected";}?>>40개</option>
										<option value="60" <?php if($this->request->query('limit') == 60){echo "selected";}?>>60개</option>
										<option value="80" <?php if($this->request->query('limit') == 80){echo "selected";}?>>80개</option>
										<option value="100" <?php if($this->request->query('limit') == 100){echo "selected";}?>>100개</option>
										<option value="200" <?php if($this->request->query('limit') == 200){echo "selected";}?>>200개</option>
									</select>
								</div> 
							</div>
                        </div>
                    </form>
					<div class="clearfix"></div>
                    <div id="transferHistory" class="m-t-10 table-responsive">
						<!--<div class="dropdown m-t-10 m-b-15">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
							</ul>
						</div>-->
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr>
									<th style="color:#fff">No</th>
									<th style="color:#fff"><?= __("User ID");?></th>
									<th style="color:#fff"><?= __("User Name");?></th>
									<!--<th style="color:#fff"><?/*= __("Phone Number");*/?></th>-->
									<th style="color:#fff"><?= __("IP");?></th>
									<th style="color:#fff"><?= __("Created");?></th>
								</tr>
                            </thead>
                            <tbody id="transferHistoryList">
								<?php
			                        foreach($log_list as $l){ 
										$created = $l->created->format('Y-m-d H:i:s');
										$this->add_system_log(200, $l->user_id, 1, '고객 로그인 기록 조회 (이름, 전화번호)');
									?>
										<tr class="">
											<td><?= $l->id; ?></td>
											<td><?= $l->user_id;?></td>
											<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $l->user_id; ?>)" class="text-dark"><?= $this->masking('N',$l->u['name']); ?></a></td>
											<!--<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?/*= $l->user_id; */?>)" class="text-dark"><?/*= $this->masking('P',$l->u['phone_number']); */?></a></td>-->
											<td><?= $l->ip_address;?></td>
											<td><?= $created;?></td>
										</tr>
								<?php } ?>
                            </tbody>
                        </table>
                        <?php 
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'IbtSystemLog', 'action' => 'userloginlog')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";

							$paginator = $this->Paginator;
							echo $paginator->first(__("First"));

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
	function form_reset(){
		$('#frm')[0].reset();
		$('#sort_value').prop('selectedIndex',0);
		$('#limit').prop('selectedIndex',0);
		$('#search_value').val('');
		$('#sort_value').val('');
		$('#order_value').val('');
		$('#export').val('');
		$('#page').val('');
		$('#frm').submit();
	}
	function sort(){
		$('#frm').submit();
	}
</script>