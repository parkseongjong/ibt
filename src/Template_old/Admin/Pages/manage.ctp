
<div class="right_col" role="main">

          <div class="">
            
			   <div class="page-title">
				<?= $this->Flash->render() ?>
              <div class="title_left">
               <span class=""><h3 class = "page_title">
                      Manage  Cms Pages
                      
                  </h3></span>
              </div>

              <div class="title_right">
				  
				<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <a href = "<?php echo $this->Url->build(['controller'=>'Pages' , 'action'=>'manage']);  ?>" class = "bck-btn" style = "display:none"><button class="btn btn-dark" type="button">Back</button></a>
                  </div>
                </div>
				
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 
                  <div class="x_content">

				  <?php echo $this->Form->create($Pages,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>
				  
					<?= $this->Flash->render() ?>
                      <!--<span class="section page-title">Manage Cms Pages</span> -->
					   <!--Loading class --> <div id="divLoading"> </div>
					     <div class="item form-group" id = "cms_main">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Cms pages <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('name',array('empty'=>'Select cms page','class' => 'form-control col-md-7 col-xs-12 cms_pages','label' =>false,'type'=>'select','options'=>$cmsPages )); ?>
                        </div>
                      </div>
					  <div class = "cms-details" style = "display:none">
					  
					  	<?php  echo $this->Form->input('id',array('class' => 'form-control col-md-7 col-xs-12 cms_id','label' =>false,"type"=>"hidden" )); ?>
						
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Title <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							   <?php  echo $this->Form->input('title',array('class' => 'form-control col-md-7 col-xs-12 title','label' =>false,"type"=>"text" )); ?>
							</div>
						  </div>
						  <!--
						  <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Slug <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							   <?php  echo $this->Form->input('slug',array('class' => 'form-control col-md-7 col-xs-12 slug','label' =>false,"type"=>"text")); ?>
							</div>
						  </div>
						  -->
						  <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Description <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							   <?php  echo $this->Form->input('description',array('class' => 'form-control col-md-7 col-xs-12 editor','label' =>false,"type"=>"textarea")); ?>
							</div>
						  </div>
						  
						  <div class="ln_solid"></div>
						  <div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<?php  echo $this->Form->button('Reset', ['type' => 'reset','class'=>'btn btn-primary']); ?>
								<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success']); ?>
							</div>
						  </div>
					  </div> <!-- Cms page Details --->
                    </form>
				  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
		
<script>
jQuery(document).ready(function(){
	
	

	jQuery('.cms_pages').change(function(){
		
		var cms_id = jQuery(this).val();
			
			jQuery.ajax({ 
						url: '<?php echo $this->Url->build(['controller'=>'Pages' , 'action'=>'search']);  ?>',
						data: {'cms_id':cms_id},
						type: 'POST',
						success: function(data) {
							if(data){
								var cmsDetails  =$.parseJSON(data);
								jQuery('.cms_id').val(cmsDetails.id);
								jQuery('.title').val(cmsDetails.title);
								jQuery('.slug').val(cmsDetails.slug);
								if(cmsDetails.description ==null) cmsDetails.description = '';
								tinyMCE.get('description').setContent(cmsDetails.description);
								jQuery('.page_title').html('Manage  " '+ cmsDetails.name +' "');
								jQuery('#cms_main').hide();
								jQuery('.cms-details').show();
								jQuery('.bck-btn').show();
							}
						}
			});
		
	});	

	if(jQuery('.cms_pages').val() != '') {jQuery('.cms-details').show(); jQuery('#cms_main').hide();  jQuery('.page_title').html('Manage  " '+ jQuery("#name option:selected").text() +' "'); jQuery('.bck-btn').show();}
});

</script>		
		
