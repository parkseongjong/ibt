<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'Leftbar','action'=>'leftbarlist']);  ?>"><?=__("Leftbar List");?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'Leftbar','action'=>'leftbarlist']);  ?>"><?=__("Leftbar List");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask" id="frm">
						<input type="hidden" name="sort_value" id="sort_value" value="" />

                    </form>
                    <div id="transferHistory" class="mt10 table-responsive">
						<a href="<?php echo $this->Url->build(['controller'=>'Leftbar','action'=>'add']); ?>"style="margin-bottom: 20px;" class="btn btn-info" ><?=__("Add");?></a>
						<a href="javascript:void(0)"style="margin-bottom: 20px; float:right; display:none;" class="btn btn-danger" id="delete_btn" onclick="menu_delete()" ><?=__("Delete");?></a>
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea; font-size: 16px;">
								<tr>
									<th style="color:#fff"><input type="checkbox" id="all_chk_btn"></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('id')"><?= __('#')?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('level_id')"><?= __('Admin Level')?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('menu_name')"><?= __('Menu')?></a></th>
									<th style="color:#fff"><?= __('url')?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('treeview')"><?= __('Treeview')?></a></th>
									<th style="color:#fff"><?= __('Treeview name')?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('treeview_sort')"><?= __('Treeview Order')?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('sort_no')"><?= __('Order')?></a></th>
									<th style="color:#fff"><?= __('Status')?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sortting('created')"><?= __('Created')?></a></th>
								</tr>
                            <thead>
                            <tbody id="transferHistoryList">
                            <?php
                            foreach($listingNew->toArray() as $k=>$data){
                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>">
									<td ><input type="checkbox" id="" name="id[]" class="chk" value="<?php echo $data['id']; ?>"></td>
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['level_id']; ?></td>
                                    <td><a href="<?php echo $this->Url->build(['controller'=>'Leftbar','action'=>'edit',$data['id']]); ?>"><?php echo __($data['menu_name']); ?></a></td>
                                    <td><a href="<?php echo $data['url']; ?>" target="_blank"><?php echo $data['url'];?></td>
                                    <td><?php echo $data['treeview'];?></td>
                                    <td><?php echo __($data['treeview_name']);?></td>
                                    <td><?php echo $data['treeview_sort'];?></td>
                                    <td>
										<?php echo $data['sort_no'];?>
										<a href="javascript:void(0)" onclick="change_sort('up',<?=$data['sort_no']?>)">↑</a>
										<a href="javascript:void(0)" onclick="change_sort('down',<?=$data['sort_no']?>)">↓</a>
									</td>
                                    <td>
										<?php
											if ($data['status'] == 'Y'){
												$btn_class = 'btn-success';
												$btn_text = __("use");
											} else if ($data['status'] == 'N'){
												$btn_class = 'btn-danger ';
												$btn_text = __("unused");
											}
										?>
										<button type="button" class="btn btn-xs <?=$btn_class;?>" onclick="change_status(<?=$data["id"]?>,'<?=$data["status"]?>')"><?=$btn_text;?></button>
									</td>
                                    <td><?php echo $data['created']->format('Y-m-d H:i:s');?></td>
                                </tr>
                                <?php } ?>
                            <?php  if(count($listingNew->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
                            } ?>
                            </tbody>
                        </table>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	function change_status(id,status){
		if(confirm("<?=__('Are you sure you want to change?')?>")){
			$.ajax({
				type: 'post',
				url: '/tech/leftbar/change_status',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : id,
					"status" : status
				},
				success:function(resp) {
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

	function change_sort(type, sort_no){
		$.ajax({
			type: 'post',
			url: '/tech/leftbar/change_sort',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"sort_no" : sort_no,
				"type" : type
			},
			success:function(resp) {
				if(resp == 'success'){
					location.reload();
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}

		});
	}

	function sortting(value){
		$('#sort_value').val(value);
		$('#frm').submit();
	}
	
	function menu_delete(){
		var delete_id = [];
		$('input[name="id[]"]:checked').each(function(){
			delete_id.push($(this).val());
		})
		if(confirm("<?=__('삭제하시겠습니까?')?>")){
			$.ajax({
				type: 'post',
				url: "<?php echo $this->Url->build(['controller'=>'leftbar','action'=>'menuDelete']);?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {
					"id" : delete_id
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
	$('#all_chk_btn').click(function(){
		if($(this).prop('checked')==true){
			$('.chk').prop('checked',true);
		} else {
			$('.chk').prop('checked',false);
		}
		delete_btn_status();
	})
	$('.chk').click(function(){
		if($(this).prop('checked')==false){
			$('#all_chk_btn').prop('checked',false);
		}
		delete_btn_status();
	})
	function delete_btn_status(){
		var cnt = $('input[name="id[]"]:checked').length;
		if(cnt > 0){
			$('#delete_btn').show();
		} else {
			$('#delete_btn').hide();
		}
	}
</script>