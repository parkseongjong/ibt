<?php echo $this->element('Front/profile_sidebar'); ?>
<section>
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>Profile </h3>
                  <div class="row">
                    <div class="col-lg-12">

                     <!-- <div class="progress-box">
					  <div class="col-lg-2">
					  <div class="progress-bar2">
					  <?php if(!empty($user->image)) { ?>
						<img src="<?php echo $this->request->webroot.'uploads/user_thumb/'.$user->image; ?>" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
						<?php } else { ?>
							<img src="<?php echo $this->request->webroot ?>assets/html/images/02.jpg" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
						<?php } ?>

					  </div>
					  </div>-->
					  <div class="col-lg-10">
                        <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>
                          
                                
                                    <?= $this->Flash->render() ?>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Username <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('username',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
										 
										</div>
									  </div>
													
                                    
                                     <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('name',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
										 
										</div>
									  </div>
									  
									   <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Email <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('email',array('disabled','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email")); ?>
										 
										</div>
									  </div>
									  
									  <div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unique Address <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											 <?php  echo $this->Form->input('user_unique_address',array('readonly','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text",'value'=>$user->unique_id)); ?>
											 
											</div>
										</div>
									  
									   <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Phone Number  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('phone_number',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
										 
										</div>
									  </div>
                                    
                                   
                                    
                                   
                                    
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Image
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php  echo $this->Form->input('image',array('class' => 'form-control col-md-7 col-xs-12','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
										
											 ?>
											
										</div>
									  </div>
							
									  
                                    <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-md-offset-3">
											
											<?php  echo $this->Form->button('Update Profile', ['type' => 'submit','class'=>'btn confirm-btn']); ?>
										</div>
									  </div>
							</form>
							 </div>
                        </div>
                    </div>
                  </div>
                 
				 
				 
				 
				 <div class="row" style="padding:10px;">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-heading tablehadding">Recent Login History </div>
                        <div class="panel-body">
						<div class="table-responsive">
                          <table class="table">
                            <thead>
                              <tr>
                                <th>IP Address</th>
                               <th>Date</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php foreach($log_records as $singleLogin) { ?>
							<tr>
                            <td><?php echo $singleLogin['ip_address']; ?></td>
                              <td><?php echo $singleLogin['date']; ?></td>
                             
                            </tr>
							<?php } ?>
                            
                              </tbody>
                            
                          </table>
                        </div> </div>
                      </div>
                    </div>
                  </div> 
				 
				 
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>