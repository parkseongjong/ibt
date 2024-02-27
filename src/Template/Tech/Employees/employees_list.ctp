<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'employeesList']);  ?>"><?=__("Employees List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'employeesList']);  ?>"><?=__("Employees List");?></a></li>
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
						<input type="hidden" id="sort_value" name="sort_value" value="<?= $this->request->query('sort_value'); ?>">
						<input type="hidden" id="order_value" name="order_value" value="<?= $this->request->query('order_value'); ?>">
						<input type="hidden" id="page" name="page" value="<?= $this->request->query('page'); ?>">
                        <div class="form-group m-t-15">
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
						<div class=" m-t-10 m-b-15">
							<a href="/tech/employees/add-employee" class="btn btn-primary">추가</a>
							<a href="javascript:void(0)" style="float:right; display:none;" class="btn btn-danger m-b-20 m-l-3" id="cancel_btn" onclick="cancel()" ><?=__("Delete");?></a>
						</div>
                        <table class="two-axis table" id="historyData" >
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr>
									<th style="color:#fff"><input type="checkbox" id="all_chk_btn"></th>
									<th style="color:#fff">No</th>
									<th style="color:#fff"><?= __("Name");?></th>
									<th style="color:#fff"><?= __("Phone Number");?></th>
									<th style="color:#fff"><?= __("Created");?></th>
									<th style="color:#fff"><?= __("Updated");?></th>
									<th style="color:#fff"><?= __("Admin Name");?></th>
									<th style="color:#fff">Edit</th>
								</tr>
                            </thead>
                            <tbody id="transferHistoryList">
								<?php
			                        foreach($employees_list as $l){ 
										$created = $l->created->format('Y-m-d H:i:s');
										$updated = $l->updated->format('Y-m-d H:i:s');
									?>
										<tr class="">
											<td ><input type="checkbox" id="" name="id[]" class="chk" value="<?php echo $l->id; ?>"></td>
											<td><?= $l->id; ?></td>
											<td><?= $l->name; ?></td>
											<td><?= $l->phone_number; ?></td>
											<td><?= $created; ?></td>
											<td><?= $updated; ?></td>
											<td><?= $l->admin_name; ?></td>
											<td><a href="/tech/employees/edit-employee/<?=$l->id?>" class="btn btn-xs btn-info">Edit</a></td>
										</tr>
								<?php } ?>
                            </tbody>
                        </table>
                        <?php 
							$this->Paginator->options(array('url' => array('controller' => 'Employees', 'action' => 'employeesList')+$this->request->query));
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
		} else {
			let cnt1 = $('input[name="id[]"]:checked').length;
			let cnt2 = $('input[name="id[]"]').length;
			if(cnt1 == cnt2){
				$('#all_chk_btn').prop('checked',true);
			}
		}
		cancel_btn_status();
	})
	function cancel_btn_status(){
		var cnt = $('input[name="id[]"]:checked').length;
		if(cnt > 0){
			$('#cancel_btn').show();
		} else {
			$('#cancel_btn').hide();
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
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				url: "<?php echo $this->Url->build(['controller'=>'Employees','action'=>'deleteEmployee']);?>",
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
</script>