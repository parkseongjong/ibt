
<div class="right_col" role="main">

          <div class="">
            
			   <div class="page-title">
				<?= $this->Flash->render() ?>
              <div class="title_left">
               <span class=""><h3 class = "page_title">
                     FAQ
                      
                  </h3></span>
              </div>

              <div class="title_right">
			  </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 
                  <div class="x_content">

				  <?php echo $this->Form->create($cmsDetails,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>
				  
					<?= $this->Flash->render() ?>
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

