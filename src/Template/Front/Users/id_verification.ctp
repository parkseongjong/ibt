<?php echo $this->element('Front/profile_sidebar'); ?>
<?php $statusArr = ['P'=>"Pending",'R'=>"Rejected",'A'=>"Approved"]; ?>
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
                  <h3>KYC </h3>
				  
                  <div class="row">
					
					  <div class="col-lg-10">
                        <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left kyc_form','novalidate','method'=>'post'));?>
                                    <?= $this->Flash->render() ?>
									<?php
									$checkBoxDisable = ($user->id_document_status !="A") ? "" : "disabled";
									$idNumberReadonly = ($user->id_document_status !="A") ? false : true;
									$passportChecked =  ($user->id_type=="passport") ? "checked" : "";
									$aadharChecked =  ($user->id_type=="aadhar") ? "checked" : "";
									$licenceChecked =  ($user->id_type=="licence") ? "checked" : "";
									$idDocumnetRejectedReason = ($user->id_document_status =="R" && !empty($user->id_document_reject_reason)) ? " ( ".$user->id_document_reject_reason." )" : "";
									$scanCopyRejectedReason = ($user->scan_copy_status =="R" && !empty($user->scan_copy_reject_reason)) ? " ( ".$user->scan_copy_reject_reason." )" : "";
									?>
									
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Document Type <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <input type="radio" class="" <?php echo $checkBoxDisable." ".$checkBoxDisable; ?> name="id_type" value="passport" /> Passport
										 &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" <?php echo $aadharChecked." ".$checkBoxDisable; ?> name="id_type" value="aadhar" /> Aadhar
										  &nbsp;&nbsp;&nbsp;&nbsp;
										 <input type="radio" class="" <?php echo $licenceChecked." ".$checkBoxDisable; ?> name="id_type" value="licence" /> Driving Licence
										</div>
									  </div>
                                     <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">ID Number <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('id_number',array('class' => 'form-control ','label' =>false,"type"=>"text","value"=>$user->id_number,"readonly"=>$idNumberReadonly)); ?>
										</div>
									  </div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Front Of ID Document
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<?php if($user->id_document_status !="A") { ?>
											<?php  echo $this->Form->input('id_document_front',array('class' => 'form-control ','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
											 ?>
											<?php } ?>
											 <br/>
											 
											 <?php if(!empty($user->id_document_front)) { ?>
												<a target="_blank" href="<?php echo BASEURL."uploads/id_verification/".$user->id_document_front; ?>"><img src="<?php echo $this->request->webroot."uploads/id_verification/".$user->id_document_front ?>" width=50 /></a>
												<?php echo $statusArr[$user->id_document_status].$idDocumnetRejectedReason; ?>
											<?php } ?>
										</div>
										
										
										
									  </div>
									   <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Back Of ID Document
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										<?php if($user->id_document_status !="A") { ?>
											<?php  echo $this->Form->input('id_document_back',array('class' => 'form-control ','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
											 ?>
											 	<?php } ?>
											  <br/>
											  
											 <?php if(!empty($user->id_document_back)) { ?>
												<a target="_blank" href="<?php echo BASEURL."uploads/id_verification/".$user->id_document_back; ?>"><img src="<?php echo $this->request->webroot."uploads/id_verification/".$user->id_document_back ?>" width=50 /></a>
												<?php echo $statusArr[$user->id_document_status].$idDocumnetRejectedReason; ?>
												
											<?php } ?>
										</div>
									  </div>
									   
									   <div class="item form-group">
									   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Scan Copy</span>
										</label>
									   <div class="col-md-6 col-sm-6 col-xs-12">
										<?php if($user->scan_copy_status !="A") { ?>
										 <?php  echo $this->Form->input('scan_copy',array('class' => 'form-control ','type'=>'file','label' =>false,'style'=>'padding-bottom:40px;'));
											 ?>
										<?php } ?>
											  <br/>
											 <?php if(!empty($user->scan_copy)) { ?>
												<a target="_blank" href="<?php echo BASEURL."uploads/id_verification/".$user->scan_copy; ?>"><img src="<?php echo $this->request->webroot."uploads/id_verification/".$user->scan_copy ?>" width=50 /></a>
												<?php echo $statusArr[$user->scan_copy_status].$scanCopyRejectedReason; ?>
											<?php } ?>
										</div>
									 
									<br/><br/>
									<?php if($user->id_document_status !="A" || $user->scan_copy_status !="A") { ?>
									  <div class="form-group">
										<div class="col-md-6 col-md-offset-3">
											
											<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn confirm-btn']); ?>
										</div>
									  </div>
									<?php } ?>
							</form>
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