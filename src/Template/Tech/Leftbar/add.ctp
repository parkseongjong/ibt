<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Add Leftbar</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Leftbar</li>
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
                                <h3 class="w3_inner_tittle two">Add leftbar :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create('',array('method'=>'post',"id"=>"frm"));?>
								<div class="col-md-12 col-md-offset-3">
                                    <div class="col-md-6 form-group valid-form">
                                        Menu Name :
										<input type="text" id="menu_name" name="menu_name" value="" class="form-control input-style" onkeyup="overlap(this.value,'menu_name')" required>
										<p id="error_menu_name" style="color:red;display:none;">에러영역</p>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        URL :
										<input type="text" id="url" name="url" value="" class="form-control input-style" placeholder="/tech/controller/action" required>
										<p id="error_url" style="color:red;display:none;">에러영역</p>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        Admin Level : 
										<select id="level_id" name="level_id" class="form-control input-style">
											<?php 
												foreach ($level as $l) {
													echo '<option value="'.$l->id.'">'.$l->level_name.'</option>';
												}
											?>
										</select>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        Icon : <i class="fa " id="icon_class_area"></i>
										<select id="icon_class" name="icon_class" class="form-control input-style" onchange="select_icon(this)"></select>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
                                        Treeview : <br>
										<label for="treeview1" class="btn btn-success" onclick="treeview_check('Y')">
											<input type="radio" id="treeview1" name="treeview" value="Y" class="" >Y
										</label>
										<label for="treeview2" class="btn btn-danger" onclick="treeview_check('N')" style="margin-left:5px;">
											<input type="radio" id="treeview2" name="treeview" value="N" class="" checked >N
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
												<option value="<?=$l->treeview_name;?>" ><?=$l->treeview_name?></option>
											<?php
												}
											?>
											<option value="Direct">Direct Input</option>
										 </select>
										 <input type="text" id="treeview_name_direct" name="treeview_name" value="" class="form-control input-style" style="display:none;">
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form treeview-area" style="display : none;" >
                                         Treeview Icon1 : <i class="fa " id="treeview_icon_class1_area"></i>
										 <select id="treeview_icon_class1" name="treeview_icon_class1" class="form-control input-style" onchange="select_icon(this)">
										 	<option value="">Please choose</option>
										</select>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form treeview-area" style="display : none;" >
                                         Treeview Icon2 : <i class="fa " id="treeview_icon_class2_area"></i>
										 <select id="treeview_icon_class2" name="treeview_icon_class2" class="form-control input-style" onchange="select_icon(this)">
										 	<option value="">Please choose</option>
										</select>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
                                        Status : <br>
										<label for="status1" class="btn btn-info" >
											<input type="radio" id="status1" name="status" value="Y" class="" checked >Y
										</label>
										<label for="status2" class="btn btn-info"  style="margin-left:5px;">
											<input type="radio" id="status2" name="status" value="N" class=""  >N
										</label>
                                    </div>
									<div class="clearfix"></div>
									<div class="form-group col-md-12" style="margin-top:15px;">
										<button type="button" class="btn btn-primary" onclick="submit_chk()">Add</button>
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
	var nameChk = false;
	var urlChk = true;
	$(function(){
		icon_list(); // icon list setting
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
		if(!nameChk){
			alert('check menu name');
			$('#menu_name').focus();
			return;
		}
		if(!urlChk){
			alert('check url');
			$('#url').focus();
			return;
		}
		if($('#menu_name').val() == ''){
			$('#menu_name').focus();
			return;
		}
		
		if($('#url').val() == ''){
			$('#menu_name').focus();
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
			if($('#treeview_name option:selected').val() == 'Direct'){
				if($('#treeview_icon_class1').val() == ''){
					$('#treeview_icon_class1').focus();
					return;
				}
				if($('#treeview_icon_class2').val() == ''){
					$('#treeview_icon_class2').focus();
					return;
				}
			}
		}
		$('#frm').submit();
	}
	/* get icon class list */
	function icon_list(){
		var iconArr = ['fa-angle-left pull-right','fa-address-book-o','fa-balance-scale','fa-comments','fa-dashboard','fa-download','fa-envelope','fa-exchange','fa-gear','fa-gears','fa-id-card-o','fa-money','fa-newspaper-o','fa-sign-out','fa-shield','fa-upload','fa-users','fa-user-circle','fa-vcard-o','fa-wrench'];
		
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

	function overlap(value,type){
		$.ajax({
			type: 'post',
			url: '/tech/leftbar/checkmenuname',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"value" : value,
				"type" : type
			},
			dataType : 'json',
			success:function(resp) {
				if(resp['status'] == 'success'){
					if(type == 'menu_name'){ nameChk = true; } 
					else if(type == 'url'){ urlChk = true; }
					$('#error_'+type).html('');
					$('#error_'+type).hide();

				} else if(resp['status'] == 'fail'){
					if(resp['msg']=='already exist'){
						var msg = '이미 존재합니다. (This ' + type + ' is already exist)';
					} else if (resp['msg']=='value is empty'){
						var msg = '값을 입력해주세요. (value is empty.)';
					}
					if(type == 'menu_name'){ nameChk = false; } 
					else if(type == 'url'){ urlChk = false; }

					$('#error_'+type).html(msg);
					$('#error_'+type).show();
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}

</script>
