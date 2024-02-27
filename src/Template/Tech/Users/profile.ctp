<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?php echo $user->name ?> Profile </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $user->name ?> Profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
           
            <div class="w3agile-validation w3ls-validation ">
                <div class="agile-validation agile_info_shadow">
                    <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                        <div class="input-info">
                            <h3 class="w3_inner_tittle two"><?php echo $user->name ?> Profile :</h3>
                        </div>
                        <div class="  form-body form-body-info">
							<?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>
                          
                                
                                    <?= $this->Flash->render() ?>
                                    <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Username <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('aaa',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","disabled"=>true,"value"=>$user->username)); ?>
										 
										</div>
									  </div>
													
                                    
                                     <!--<div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Name <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  //echo $this->Form->input('name',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
										 
										</div>
									  </div>-->
									  
									   <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Email <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('email',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email","disabled"=>true,"value"=>$user->email)); ?>
										 
										</div>
									  </div>
									  
									  
									  
									   <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Phone Number  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('phone_number',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
										 
										</div>
									  </div>
									  
									  <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Category  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('category',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[""=>"Select","premium"=>"Premium","golden"=>"Golden","silver"=>"Silver","bronze"=>"Bronze","normal"=>"Normal"])); ?>
										 
										</div>
									  </div>
									  <br/>
									  <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">User Type  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('user_type',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>["U"=>"User","A"=>"Admin"])); ?>
										 
										</div>
									  </div>
									  
                                    
                                   <br/>
                                    
                                    <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Image
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php  echo $this->Form->input('image',array('class' => 'form-control col-md-7 col-xs-12','type'=>'file','label' =>false));
											if($user->image != '') echo '<img width="50px" src="'.$this->request->webroot.'uploads/user_thumb/'.$user->image.'"/>';
											 ?>
											
										</div>
									  </div>
									  
                                    
                                   
									  
                                    <div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-6 col-md-offset-2">
											
											<?php  echo $this->Form->button('Update Profile', ['type' => 'submit','class'=>'btn btn-success']); ?>
										</div>
									  </div>
                    </form>
					
                        </div>
                    </div>
                </div>
				<script>
				function wallet_address(){
					$("#wallet_address").toggle('slide');
				}
				</script>
              
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Change Password:</h3>
                            </div>
                            <div class="  form-body form-body-info">
                                 <?php echo $this->Form->create($user,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>
				  
			
                      <!--<div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Old Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						 <?php  //echo $this->Form->input('old_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password")); ?>
                         
                        </div>
                      </div>-->
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('new_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password")); ?>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">Confirm Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('confirm_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password")); ?>
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
							
							<?php  echo $this->Form->button('Update password', ['type' => 'submit','class'=>'btn btn-success']); ?>
                        </div>
                      </div>
                    </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/prograc-blocks_agileits-->
                <!--//prograc-blocks_agileits-->

            </div>
        </div>
    </section>
</div>
