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
				<?php 
							$showStatusText = ($user->id_verification_status=="Y") ?  "Verified" : ($user->id_verification_status=="P") ? "Pending" : "Cancelled";
							
							$showStatusBgColor = ($user->id_verification_status=="Y") ?  "#4db04a" : ($user->id_verification_status=="P") ? "#c6bf54" : "#f03434";
							
							?>
                <section class="main-content">
                  <h3>KYC
				  <?php if(!empty($user->id_document_front)) { ?>
				  <span style="font-size: 17px;padding: 10px;background: <?php echo $showStatusBgColor; ?>;border-radius: 4px;color: white;"><?php echo $showStatusText; ?></span>
				  <?php } ?>
				  </h3>
                  <div class="row">
					  <?php // if(empty($user->id_document_front) || $user->id_verification_status=='N') { ?>
					  <div class="col-lg-10">
                        <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left kyc_form','novalidate','method'=>'post'));?>
                                    <?= $this->Flash->render() ?>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Document Type <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <input type="radio" class="" name="id_type" value="passport" /> Passport
										 &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" name="id_type" value="ktp" /> Aadhar
										  &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" name="id_type" value="licence" /> Driving Licence
										</div>
									  </div>
                                     <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">ID Number <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('id_number',array('class' => 'form-control ','label' =>false,"type"=>"text")); ?>
										</div>
									  </div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Front Of ID Document
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php  echo $this->Form->input('image',array('class' => 'form-control ','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
											 ?>
										</div>
									  </div>
									   <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Back Of ID Document
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php  echo $this->Form->input('image',array('class' => 'form-control ','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
											 ?>
										</div>
									  </div>
									   <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Address Proof <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <input type="radio" class="" name="id_type" value="bill" /> Bill
										 &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" name="id_type" value="electricity" />  Electricity Bill
										  &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" name="id_type" value="telephone" /> Telephone Bill
										</div>
									  </div>
									   <div class="item form-group">
									   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Scan Copy</span>
										</label>
									   <div class="col-md-6 col-sm-6 col-xs-12">
									  
										 <input type="text"  class="form-control" required="required"  value="Scan Copy">
										</div>
									  <!--<?php if(!empty($user->id_document_front)) { ?>
										<img src="<?php echo $this->request->webroot.'uploads/id_verification/'.$user->id_document_front; ?>" alt="Avatar" width="150"  class="img-thumbnail img-circles account-img-mb"> 
										<?php } ?>
										</div>--></div>
                                    <!--<div class="ln_solid">
									<span>Please ensure that the documents and declaration are clearly visible with the text completely exposed</span></div>-->
									<br/>
									  <div class="form-group">
										<div class="col-md-6 col-md-offset-3">
											
											<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn confirm-btn']); ?>
										</div>
									  </div>
							</form>
							 </div>
						<?php //} ?>
						
						
						
						
						
						
						
						
						
						
						
						
                        
                    </div>
                  </div>
                 
				 
				 
			
				 
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>