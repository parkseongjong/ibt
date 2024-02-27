 <?php $index_count2= $index_count3=0; ?>
 <div class="right_col" role="main">
          <div class="">

            <div class="page-title">
				<?= $this->Flash->render() ?>
              <div class="title_left">
                <h3>
                      Start Date
                     
                  </h3>
              </div>
			</div>

            <div class="clearfix"></div>

            <div class="row">
				

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 
                  <div class="x_content">
					  
					  <?php echo $this->Form->create(null,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>
				  
				  
					<?= $this->Flash->render() ?>
                      
					
					  
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Date(YYYY-MM-DD)<span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('from_date',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text",'value'=>$query->date->format('Y-m-d'))); ?>
                        </div>
                      </div>
                     
                      
					   
               
                 
		
					   
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							<?php  echo $this->Form->button('Reset', ['type' => 'reset','class'=>'btn btn-primary']); ?>
							<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success']); ?>
                        </div>
                      </div>
                    </form>

				  
                  </div>
                </div>
              </div>
              
		</div>
		
		
	 </div>
</div> 
 <script> 
  $(document).ready(function() {  
	 
	 var bookIndex2 =<?php echo $index_count2 ?>;
	 var bookIndex3 =<?php echo $index_count3 ?>;
	 
	
	  $('#bookForm2')
			.on('click', '.addButton', function() {
				bookIndex2++;
				var $template = $('#bookTemplate2'),
					$clone    = $template
									.clone()
									.removeClass('hide')
									.removeAttr('id')
									.attr('data-book-index', bookIndex2)
									.insertBefore($template);
				$clone
					.find('[name="free_service"]').attr('name','free_service_arr['+bookIndex2+']').attr('value','').end();
			
				
			})
			.on('click', '.removeButton', function() {
				
				var $row  = $(this).parents('.form-new'),
					index = $row.attr('data-book-index');
				$row.remove();
			})
	 $('#bookForm3')
			.on('click', '.addButton', function() {
				bookIndex3++;
				var $template = $('#bookTemplate3'),
					$clone    = $template
									.clone()
									.removeClass('hide')
									.removeAttr('id')
									.attr('data-book-index', bookIndex3)
									.insertBefore($template);
				$clone
					.find('[name="facility"]').attr('name','facility_arr['+bookIndex3+']').attr('value','').end();
			
				
			})
			.on('click', '.removeButton', function() {
				
				var $row  = $(this).parents('.form-new'),
					index = $row.attr('data-book-index');
				$row.remove();
			})
 
});    
</script>		

