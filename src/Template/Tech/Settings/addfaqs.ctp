<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
/* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
	    height: 25%;
    }
	.modal-text-area {
	    margin: 20px auto;
	}.modal-text-area > p {
	    margin: 3px auto;
		color: #ff4444;
		display:none;
	}
	.modal-btn-area {
		margin: 30px auto 5px auto;
		text-align: center;
	}
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
	.btn-area {
		margin: 10px auto;
	}
	.valid-form > p{
		margin: 3px auto;
		color: #ff4444;
		display:none;
	}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Coin Management </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Coin Management</li>
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
                                <h3 class="w3_inner_tittle two">Add Notice :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('method'=>'post','enctype'=>'multipart/form-data','id'=>'frm')); ?>
								<div class="col-md-12 col-md-offset-3">
                                    <?= $this->Flash->render() ?>
									<div class="col-md-6 form-group valid-form">
										Select Language :
										<?php  
											echo $this->Form->input('lang', ['type'=>'select','options'=> array('ko_KR'=>"KOR",'en_US'=>"ENG"),'label'=>false,'class'=>"form-control input-style required",'onchange'=>"get_category_list(this.value)"]);
										?>
										<p id="lang_error" >Please Select Language</p>
                                    </div>
									<div class="clearfix"></div>
                                    <div class="col-md-6 form-group valid-form">
										Category :
										<select id="category" name="category" class="form-control input-style required" ></select>
										<p id="category_error" >Please Select Category</p>
										<div class="btn-area">
											<button type="button" class="btn btn-primary " onclick="add_category()">Add Category</button>
										</div>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        Subject :
                                        <?php  echo $this->Form->input('subject',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"subject","required"=>true));?>
										<p id="subject_error" >Please Enter Subject</p>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
										Contents :
										<?php echo $this->Form->input('contents', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'contents','class' => 'form-control input-style required']); ?>                     
										<p id="contents_error" >Please Enter Contents</p>
									</div>
									<div class="clearfix"></div>
									<div class="clearfix"></div>
									<div class="col-md-12 btn-area">
										<?php  echo $this->Form->button('button', ['type' => 'button','class'=>'btn btn-primary','onclick'=>'submitCheck()']); ?>
									</div>
									</div>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="close">&times;</span>
				<div id="text_area">
					<div class="modal-text-area">
						<select id="category_lang" class="form-control" style="width:25%;">
							<option value="">Select Language</option>
							<option value="en_US">ENG</option>
							<option value="ko_KR">KOR</option>
						</select>
						<p id="category_lang_error" >Select Category Language</p>
					</div>
					<div class="modal-text-area">
						<input type="text" id="category_value" name="category_value" class="form-control" placeholder="Please enter a category name" >
						<p id="category_value_error" >Enter Category Name</p>
					</div>
				</div>
				<div class="modal-btn-area" >
					<button type="button" onclick="add_category_submit()" class="btn btn-primary">Add Category</button>
				</div>
			</div>
		</div>
    </section>
</div>
<script>
	var clickCheck = false;
	function doubleSubmitCheck(){
		if(clickCheck){
            return clickCheck;
        }else{
            clickCheck = true;
            return false;
        }
	}

	function submitCheck(){
		if($('#lang').val()==''){
			$('#lang_error').css('display','block');
			$('#lang').focus();
			return;
		}
		if($('#category').val()==''){
			$('#category_error').css('display','block');
			$('#category').focus();
			return;
		}
		if($('#subject').val()==''){
			$('#subject_error').css('display','block');
			$('#subject').focus();
			return;
		} else {
			$('#subject_error').css('display','none');
		}
		if($('#contents').val()==''){
			$('#contents_error').css('display','block');
			$('#contents').focus();
			return;
		} else {
			$('#contents_error').css('display','none');
		}
		$('#frm').submit();
	}

	function get_category_list(lang){
		$('#category').empty();
		$.ajax({
			type: 'post',
			url: '/tech/settings/getfaqcategory',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"lang" : lang,
			},
			dataType : 'json',
			success:function(resp) {
				$.each(resp, function (i) {
					$('#category').append($('<option>', { 
						value: resp[i]['category'],
						text : resp[i]['category'] 
					}));
				});
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}
	$(function(){
		get_category_list($('#lang').val());
	})
	var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
	/* show modal popup */
	function add_category(){
		$('#category_lang').val('');
		$('#category_value').val('');
		$('#myModal').css('display','block');
	}
	/* add category submit */
	function add_category_submit(){
		var lang = $('#category_lang').val();
		var category = $('#category_value').val();

		if(lang == ''){
			$('#category_lang_error').css('display','block');
			return;
		} else {
			$('#category_lang_error').css('display','none');
		}
		if(category == ''){
			$('#category_value_error').css('display','block');
			return;
		} else {
			$('#category_value_error').css('display','none');
		}
		
		$.ajax({
			type: 'post',
			url: '/tech/settings/addfaqcategory',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {
				"lang" : lang,
				"category" : category
			},
			success:function(resp) {
				get_category_list($('#lang').val());
				modal.style.display = "none";
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}
</script>