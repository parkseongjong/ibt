<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Leftbar</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Leftbar</li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Edit leftbar :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create('',array('method'=>'post',"id"=>"frm"));?>
									<div class="col-md-12 col-md-offset-3">
										<div class="col-md-6 form-group valid-form">
											Menu Name :
											<input type="text" id="menu_name" name="menu_name" value="<?=$left_bar->menu_name;?>" class="form-control input-style ">
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											URL :
											<input type="text" id="url" name="url" value="<?=$left_bar->url;?>" class="form-control input-style">
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											Level : 
											<select id="level_id" name="level_id" class="form-control input-style">
												<?php 
													foreach ($level as $l) {
														$selected = $left_bar->level_id == $l->id ? 'selected' : '';
														echo '<option value="'.$l->id.'" '.$selected.'>'.$l->level_name.'</option>';
													}
												?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											Icon : <i class="fa <?= $left_bar->icon_class;?>" id="icon_class_area"></i>
											<select id="icon_class" name="icon_class" class="form-control input-style" onchange="select_icon(this)"></select>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
											Treeview : <br>
											<label for="treeview1" class="btn btn-success" onclick="treeview_check('Y')">
												<input type="radio" id="treeview1" name="treeview" value="Y" <?= $left_bar->treeview == 'Y' ? 'checked' : ''; ?>>Y
											</label>
											<label for="treeview2" class="btn btn-danger" onclick="treeview_check('N')" style="margin-left:5px;">
												<input type="radio" id="treeview2" name="treeview" value="N" <?= $left_bar->treeview == 'N' ? 'checked' : ''; ?>>N
											</label>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form treeview-area" style="display : none;" >
											 Treeview Name :
											 <select id="treeview_name" class="form-control input-style" onchange="treeview_group(this.value)">
												<option value="">Please choose</option>
												<?php
													foreach($treeview_list as $l){
												?>
													<option value="<?=$l->treeview_name;?>" <?= $left_bar->treeview_name == $l->treeview_name ? 'selected' : ''; ?>><?=$l->treeview_name?></option>
												<?php
													}
												?>
												
												<option value="Direct">Direct Input</option>
											 </select>
											 <input type="text" id="treeview_name_direct" name="treeview_name" value="<?= $left_bar->treeview_name;?>" class="form-control input-style" style="display:none;">
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form treeview-area" style="display : none;" >
											 Treeview Icon1 : <i class="fa <?= $left_bar->treeview_icon_class1;?>" id="treeview_icon_class1_area"></i>
											 <select id="treeview_icon_class1" name="treeview_icon_class1" class="form-control input-style" onchange="select_icon(this)">
												<option value="">Please choose</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form treeview-area" style="display : none;" >
											 Treeview Icon2 : <i class="fa <?= $left_bar->treeview_icon_class2;?>" id="treeview_icon_class2_area"></i>
											 <select id="treeview_icon_class2" name="treeview_icon_class2" class="form-control input-style" onchange="select_icon(this)">
												<option value="">Please choose</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
											Status : <br>
											<label for="status1" class="btn btn-info" >
												<input type="radio" id="status1" name="status" value="Y" class="" <?= $left_bar->status == 'Y' ? 'checked' : ''; ?> >Y
											</label>
											<label for="status2" class="btn btn-info"  style="margin-left:5px;">
												<input type="radio" id="status2" name="status" value="N" class="" <?= $left_bar->status == 'N' ? 'checked' : ''; ?>>N
											</label>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											Created :
											<input type="text" id="created" name="" value="<?=$left_bar->created;?>" class="form-control input-style" readonly>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											Last Updated :
											<input type="text" id="updated" name="" value="<?=$left_bar->updated;?>" class="form-control input-style" readonly>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											Last Updated Admin Id (name):
											<input type="text" id="last_admin_name" name="last_admin_name" value="<?= $left_bar->last_id.' ( '.$left_bar->u['name'].' ) ';?>" class="form-control input-style" readonly>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12" style="margin-top:15px;">
											<button type="button" class="btn btn-primary" onclick="submit_chk()">Edit</button>
										</div>
									</div>  
								</form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
    </section>
</div>
<script>
	var treeview = '<?=$left_bar->treeview;?>';
	$(function(){
		icon_list(); // icon list setting
		treeview_check(treeview);
	})
	/* treeview view */
	function treeview_check(value){
		if(value == 'Y'){
			$('.treeview-area').show();
		} else if (value == 'N'){
			$('.treeview-area').hide();
		}
	}
	/* check submit data */
	function submit_chk(){
		//alert('점검중입니다.');
		//return;
		if($('#menu_name').val() == ''){
			$('#menu_name').focus();
			return;
		}
		if($('#url').val() == ''){
			$('#url').focus();
			return;
		}
		if($('#level_id').val() == ''){
			$('#level_id').focus();
			return;
		}
		if($('input[name="treeview"]:checked').val() == ''){
			$('input[name="treeview"]:checked').focus();
			return;
		}
		if($('input[name="status"]:checked').val() == ''){
			$('input[name="status"]:checked').focus();
			return;
		}
		if($('input[name="treeview"]:checked').val() == 'Y'){
			if($('#treeview_name_direct').val() == ''){
				$('#treeview_name').focus();
				return;
			}
			if($('#treeview_icon_class1').val() == ''){
				$('#treeview_icon_class1').focus();
				return;
			}
			if($('#treeview_icon_class2').val() == ''){
				$('#treeview_icon_class2').focus();
				return;
			}
		}
		$('#frm').submit();
	}
	/* get icon class list */
	function icon_list(){
		var iconArr = ['fa-angle-left pull-right','fa-address-book-o','fa-balance-scale','fa-comments','fa-dashboard','fa-download','fa-envelope','fa-exchange','fa-gear','fa-gears','fa-id-card-o','fa-money','fa-newspaper-o','fa-sign-out','fa-shield','fa-upload','fa-users','fa-user-circle','fa-vcard-o','fa-wrench'];
		var icon_class = '<?=$left_bar->icon_class;?>';
		var treeview_icon_class1 = '<?=$left_bar->treeview_icon_class1;?>';
		var treeview_icon_class2 = '<?=$left_bar->treeview_icon_class2;?>';

		$.each(iconArr, function (i) {
			$('#icon_class').append($('<option>', { 
				value: iconArr[i],
				text : iconArr[i]
			}));
			$('#treeview_icon_class1').append($('<option>', { 
				value: iconArr[i],
				text : iconArr[i] 
			}));
			$('#treeview_icon_class2').append($('<option>', { 
				value: iconArr[i],
				text : iconArr[i] 
			}));
		});

		$('#icon_class').val(icon_class).prop('selected',true);
		$('#treeview_icon_class1').val(treeview_icon_class1).prop('selected',true);
		$('#treeview_icon_class2').val(treeview_icon_class2).prop('selected',true);
	}
	/* selected icon preview */
	function select_icon(select){
		var id = select.id;
		var value = select.value;
		$('#'+id+'_area').removeClass();
		$('#'+id+'_area').addClass('fa');
		$('#'+id+'_area').addClass(value);
	}
	/* treeview name */
	function treeview_group(value){
		if(value == 'Direct'){
			$('#treeview_name_direct').val('');
			$('#treeview_name_direct').show();
		} else {
			$('#treeview_name_direct').val(value);
			$('#treeview_name_direct').hide();
		}
	}

</script>
