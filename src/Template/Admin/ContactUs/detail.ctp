<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Queries <small>Detail</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Contact Us</li>
        </ol>
    </section>
    <section id="content" class="table-layout">
		<div class="inner_content_w3_agile_info">
			<div class="agile-validation agile_info_shadow">
            <div class="clearfix"></div>
			


            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
					 <div class="x_content">
					<?= $this->Flash->render() ?>
				<?php echo $this->Form->create($ContactUs,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>		
					
				 <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Name
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                           <?= $ContactUs->name;?>
                        </div>
                      </div> 	
					 <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                           <?= $ContactUs->email;?>
                        </div>
                      </div> 	
					 <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Phone Number
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
					   <?= $ContactUs->phone;?>
					</div>
				  </div> 	
					 
					 <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Query
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;word-wrap: break-word;">
					   <?= $ContactUs->message;?>
					</div>
                      </div> 	
						
					
				 
              <?php if($ContactUs->status == 1)
              { ?>
				   
				<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Reply Subject
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
				<?= $ContactUs->reply_subject;?>
				</div>
				</div> 	
				<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Reply Message
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
					<?= $ContactUs->reply_message;?>
				
				</div>
				</div> 	

              
              
				<?php  }
				else
				{ ?>
					 <h3 class="w3_inner_tittle two">Send Reply :</h3>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Subject<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('reply_subject',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
                        </div>
                      </div>
                        
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Reply<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('reply_message',array('class' => 'editor form-control col-md-7 col-xs-12','label' =>false,"type"=>"textarea")); ?>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							
							<?php  echo $this->Form->button('Send', ['type' => 'submit','class'=>'btn btn-success']); ?>
                        </div>
                     
			<?php	}?>   
			
			</div>
               </form>
               
               
                </div>
                
                
              </div>
            </div>
         </div>
		</div>
   </section> 
</div>

