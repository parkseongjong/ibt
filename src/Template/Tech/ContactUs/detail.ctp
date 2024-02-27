<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Queries '); ?> <small><?= __('Details'); ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Contact Us'); ?></li>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Username'); ?>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                           <?= $ContactUsData['user']['username'];?>
                        </div>
                      </div> 	
					 <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Email'); ?>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                           <?= $ContactUsData['user']['email'];?>
                        </div>
                      </div> 
					
					
					<?php if(!empty($ContactUsData['tx_id'])){ ?>
					<div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Transaction ID'); ?>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                         <?php echo $ContactUsData['tx_id']; ?>
                        </div>
                      </div> 
					<?php } ?>
					
					<?php if(!empty($ContactUsData['issue_file'])){ ?>
					<div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Attachments'); ?>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
                           <?php echo $issueFile = "<a target='_blank' href='".$this->request->webroot."uploads/issue_file/".$ContactUsData['issue_file']."'><img src='".$this->request->webroot."uploads/issue_file/".$ContactUsData['issue_file']."' width=50 /></a>"; ?>
                        </div>
                      </div> 
					<?php } ?>
					  
					
					 
					 <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Query'); ?>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;word-wrap: break-word;">
					   <?= $ContactUsData['issue'];?>
					</div>
                      </div> 	
						
					
				 
              <?php if($ContactUsData['status'] == 'resolved')
              { ?>
				   
					
				<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Response'); ?>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="line-height: 37px;">
					<?= $ContactUsData['response'];?>
				
				</div>
				</div> 	

              
              
				<?php  }
				else
				{ ?>
					 <h3 class="w3_inner_tittle two"><?= __('Send Response'); ?></h3>
                     
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?= __('Reply'); ?><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php  echo $this->Form->input('response',array('class' => 'editor form-control col-md-7 col-xs-12','label' =>false,"type"=>"textarea")); ?>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							
							<?php  echo $this->Form->button('Send', ['type' => 'submit','class'=>'btn btn-success','value'=>__('Send')]); ?>
                        </div>
                     
			<?php	}?>   
			
			</div>
               </form>
               
               
                </div>
				<h2> <?= __('Old Messages'); ?> </h2>
				<table class="table" border=2 cellspacing=2 cellpadding=2 >
				<tr>
					<th>#</th>
					<th><?= __('Query'); ?></th>
					<th><?= __('Reply'); ?></th>
					<th><?= __('Transaction ID'); ?></th>
					<th><?= __('Attachments'); ?></th>
				</tr>
				<?php $i=1; foreach($getAllMessage as $single) { ?>
				<tr>
					<td>
					<?php echo $i; ?>
					</td>
					<td>
					<?php echo $single['issue']; ?>
					</td>
					<td>
					<?php echo $single['response']; ?>
					</td>
					<td>
					<textarea><?php echo $single['tx_id']; ?></textarea>
					</td>
					<td>
					 <?php if(!empty($single['issue_file'])) { echo $issueFile = "<a target='_blank' href='".$this->request->webroot."uploads/issue_file/".$single['issue_file']."'><img src='".$this->request->webroot."uploads/issue_file/".$single['issue_file']."' width=50 /></a>"; 
					 }?>
					</td>
				</tr>
				<?php $i++; } ?>
                
				
				</table>
                
              </div>
            </div>
         </div>
		</div>
   </section> 
</div>

