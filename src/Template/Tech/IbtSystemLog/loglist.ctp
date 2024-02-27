<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'IbtSystemLog','action'=>'loglist']);  ?>"><?=__("Log List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'IbtSystemLog','action'=>'loglist']);  ?>"><?=__("Log List");?></a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
					<div class="clearfix"></div>
                    <form method="get" id="frm">
						<input type="hidden" name="export" id="export" />
						<input type="hidden" id="sort_value" name="sort_value" value="<?= $this->request->query('sort_value'); ?>">
						<input type="hidden" id="order_value" name="order_value" value="<?= $this->request->query('order_value'); ?>">
						<input type="hidden" id="page" name="page" value="<?= $this->request->query('page'); ?>">
                        <div class="form-group m-t-15">
							<div class="col-md-1 col-sm-1 col-xs-12">
								<select id="search_type" name="search_type" class="form-control" >
									<option value="all">전체검색</option>
									<option value="admin_id" <?php if($this->request->query('search_type') == 'admin_id'){echo "selected";}?>>관리자ID</option>
									<option value="a.name" <?php if($this->request->query('search_type') == 'a.name'){echo "selected";}?>>관리자명</option>
									<option value="user_ip" <?php if($this->request->query('search_type') == 'user_ip'){echo "selected";}?>>IP</option>
									<option value="url" <?php if($this->request->query('search_type') == 'url'){echo "selected";}?>>URL</option>
									<option value="user_id" <?php if($this->request->query('search_type') == 'user_id'){echo "selected";}?>>회원ID</option>
									<option value="u.name" <?php if($this->request->query('search_type') == 'u.name'){echo "selected";}?>>회원명</option>
									<option value="description" <?php if($this->request->query('search_type') == 'description'){echo "selected";}?>>설명</option>
								</select>
							</div>
							<div id="search" class="col-md-3 col-sm-2 col-xs-12">
								<input type="text" id="search_value" name="search_value" value="<?= $this->request->query('search_value'); ?>" class="form-control col-md-7 col-xs-12" placeholder="검색어를 입력해주세요">
                            </div>
							<div class="col-md-3 col-sm-2 col-xs-12">
								<input type="date" id="start_date" name="start_date" value="<?= $this->request->query('start_date'); ?>" class="form-control col-md-7 col-xs-12" >
                            </div>
							<div  class="col-md-3 col-sm-2 col-xs-12">
								<input type="date" id="end_date" name="end_date" value="<?= $this->request->query('end_date'); ?>" class="form-control col-md-7 col-xs-12">
                            </div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<button type="button" class="btn btn-primary" onclick="list_search()"><?=__("Search");?></button>
								<button type="button" class="btn " onclick="form_reset()" style="margin-left:3px;" ><?=__("Reset");?></button>
                            </div>
							<div class="clearfix"></div>
							<div class="m-t-15">
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="log_level" name="log_level" class="form-control" onchange="sort()">
										<option value="">로그 레벨 선택</option>
										<option value="100" <?php if($this->request->query('log_level') == 100){echo "selected";}?>><?=$this->get_log_level(100);?></option>
										<option value="200" <?php if($this->request->query('log_level') == 200){echo "selected";}?>><?=$this->get_log_level(200);?></option>
										<option value="250" <?php if($this->request->query('log_level') == 250){echo "selected";}?>><?=$this->get_log_level(250);?></option>
										<option value="300" <?php if($this->request->query('log_level') == 300){echo "selected";}?>><?=$this->get_log_level(300);?></option>
										<option value="400" <?php if($this->request->query('log_level') == 400){echo "selected";}?>><?=$this->get_log_level(400);?></option>
										<option value="500" <?php if($this->request->query('log_level') == 500){echo "selected";}?>><?=$this->get_log_level(500);?></option>
										<option value="550" <?php if($this->request->query('log_level') == 550){echo "selected";}?>><?=$this->get_log_level(550);?></option>
										<option value="600" <?php if($this->request->query('log_level') == 600){echo "selected";}?>><?=$this->get_log_level(600);?></option>
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="action" name="action" class="form-control" onchange="sort()">
										<option value="">액션 선택</option>
										<option value="0" <?php if($this->request->query('action') == '0'){echo "selected";}?>><?=$this->get_log_action(0);?></option>
										<option value="1" <?php if($this->request->query('action') == '1'){echo "selected";}?>><?=$this->get_log_action(1);?></option>
										<option value="2" <?php if($this->request->query('action') == '2'){echo "selected";}?>><?=$this->get_log_action(2);?></option>
										<option value="3" <?php if($this->request->query('action') == '3'){echo "selected";}?>><?=$this->get_log_action(3);?></option>
										<option value="4" <?php if($this->request->query('action') == '4'){echo "selected";}?>><?=$this->get_log_action(4);?></option>
										<option value="5" <?php if($this->request->query('action') == '5'){echo "selected";}?>><?=$this->get_log_action(5);?></option>
									</select>
								</div>
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
					<script>
						function list_search(){
							if($('#search_value').val() == ''){
								alert('검색어를 입력해주세요');
								$('#search_value').focus();
								return;
							}
							$('#frm').submit();
						}
					</script>
                    <div id="transferHistory" class="m-t-10 table-responsive">
						<!--<div class="dropdown m-t-10 m-b-15">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
							</ul>
						</div>-->
                        <table class="two-axis table" id="historyData" >
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr>
									<th style="color:#fff">No</th>
									<th style="color:#fff"><?= __("Log Level");?></th>
									<th style="color:#fff"><?= __("Admin ID");?></th>
									<th style="color:#fff"><?= __("Admin Name");?></th>
									<th style="color:#fff"><?= __("Admin Agent");?></th>
									<th style="color:#fff"><?= __("IP");?></th>
									<th style="color:#fff"><?= __("URL");?></th>
									<th style="color:#fff"><?= __("User ID");?></th>
									<th style="color:#fff"><?= __("User Name");?></th>
									<th style="color:#fff"><?= __("Description");?></th>
									<th style="color:#fff"><?= __("action");?></th>
									<th style="color:#fff"><?= __("Created");?></th>
								</tr>
                            </thead>
                            <tbody id="transferHistoryList">
								<?php
			                        foreach($log_list as $l){ 
										//$created;
										//if($l->created->format('Y-m-d') >= date('Y-m-d')){
										//	$created = $l->created->format('H:i:s');
										//} else {
										$created = $l->created->format('Y-m-d H:i:s');
										//}
									?>
										<tr class="">
											<td><?= $l->id; ?></td>
											<td><?= $this->get_log_level($l->log_level);?></td>
											<td><?= $l->admin_id;?></td>
											<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $l->admin_id; ?>)" class="text-dark"><?= $this->masking('N',$l->a['name']); ?></a></td>
											<td width="120" style="word-break:break-all;"><?= $l->user_agent;?></td>
											<td><?= $l->user_ip; ?></td>
											<td width="250" style="word-break:break-all"><?= $l->url;?></td>
											<td><?= $l->user_id;?></td>
											<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $l->user_id; ?>)" class="text-dark"><?= $this->masking('N',$l->u['name']); ?></a></td>
											<td><?= $l->description;?></td>
											<td><?= $this->get_log_action($l->action);?></td>
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
							$this->Paginator->options(array('url' => array('controller' => 'IbtSystemLog', 'action' => 'loglist')+$searchArr));
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
		$('#log_level').prop('selectedIndex',0);
		$('#action').prop('selectedIndex',0);
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