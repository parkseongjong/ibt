
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Permission <small>sub admin</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sub admin</li>
        </ol>
    </section>
      <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 <div id="divLoading"> </div><!--Loading class -->
                  <div class="x_content">

				  <?php echo $this->Form->create(null,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>
				  
					<?= $this->Flash->render() ?>
                      
					  
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Select Admin <span class="required">*</span>
                        </label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php  echo $this->Form->input('user_id',array('empty'=>'select user','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$Users,'onchange'=>'get_form(this.value)')); ?>
                    
						</div>
					   </div>	
					 <div id="form_id" ></div>
                      <div class="ln_solid"></div>
                       </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </section>
        </div>
<script>
	
	function get_form(id){
		if(id== ''){
			$("#form_id").html('');
		}else{
			$.ajax({ 
			url: '<?php echo $this->Url->build(['controller'=>'sub_admin' , 'action'=>'form']);  ?>',
				data: {'id':id},
				type: 'POST',
				success: function(data) {
					
					$("#form_id").html(data);
						
						
					
				}
			});
		}
	}
	 

</script>	
