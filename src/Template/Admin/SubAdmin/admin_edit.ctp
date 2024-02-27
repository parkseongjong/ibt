<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Add <small>sub admin</small> </h1>
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
                 
                  <div class="x_content">

				  <?php echo $this->Form->create($user,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));
				  ?>
				   <?php  echo $this->Form->input('role',array('value' => 'admin',"type"=>"hidden")); ?>
				  
					<?= $this->Flash->render() ?>
                     
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Phone Number
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('phone_number',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","required" => false)); ?>
						 
                        </div>
                      </div>

					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('email',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email")); ?>
                        </div>
                      </div>
                      
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Username<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('username',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">First Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						 <?php  echo $this->Form->input('name',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
                         
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
          </section>
        </div>
	
