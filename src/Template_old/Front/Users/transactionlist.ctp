<style>
.panel-default > .panel-heading {
	    border-bottom: 1px solid #e0e0e0;
    padding: 10px;
    font-size: larger;
}

</style>


<section style="margin-left:0px;">
    <section class="main-content">
      <?php $showLink = ($goToWallet=1) ? $this->Url->build(['controller'=>'pages','action'=>'mywallet']) : '#';  ?>
      <a href="<?php echo $showLink; ?>" class="btn btn-labeled btn-primary pull-right"> <span class="btn-label"><i class="fa fa-dollar"></i> </span>Goto Wallet</div></a>
      <h3 class="h3">Transactions </h3>
   
	  
      <!-- Orders Book -->
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2 ">Deposit History <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  
				    <div class="col-md-12 col-sm-12">
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					 <table class="table table-striped table-hover table-condensed">
                        <thead>
                          <tr>
                            <th> Sr No. </th>
							<th> Amount </th>
							<th> Coin Type</th>
							<th> Remark</th>
                            <th> date </th>
                          </tr>
                        </thead>
                        <tbody id="buyAjaxData">
						<?php $i=1; foreach($depositList as $singleData) {
									if($singleData['status']=="pending"){
										continue;
									}
									
									//$tx_id = str_replace("_deposit","",$singleData['tx_id']);
						?>
                            <tr>
								<td> <?php echo $i; ?> </td>
								<td> <?php echo (!empty($singleData['coin_amount'])) ? $singleData['coin_amount'] : 0; ?> </td>
								<td> <?php echo $singleData['cryptocoin']['short_name']; ?></td>
								<td> <?php echo $singleData['remark']; ?></td>
								<td> <?php echo date('M d, Y h:i A',strtotime($singleData['created'])); ?> </td>
							</tr>
                        <?php $i++; } ?> 
						<?php  if($i==1) {?>
							<tr>
								<td colspan=4>No Data Found </td>
							</tr>
						<?php } ?>		
                        </tbody>
                      </table>
                    </div>
                 
                  </div>
				  
				  
				 
                </div>
              </div>
            </div>
          </div>
        </div>
		
		
		
		 <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2 ">Referral History <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  
				    <div class="col-md-12 col-sm-12">
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					 <table class="table table-striped table-hover table-condensed">
                        <thead>
                          <tr>
                            <th> Sr No. </th>
							<th> Amount </th>
							<th> Coin Type</th>
							<th> date </th>
                          </tr>
                        </thead>
                        <tbody id="buyAjaxData">
						<?php $i=1; foreach($referAmtList as $singleData) {
									if($singleData['status']=="pending"){
										continue;
									}
									
									//$tx_id = str_replace("_deposit","",$singleData['tx_id']);
						?>
                            <tr>
								<td> <?php echo $i; ?> </td>
								<td> <?php echo (!empty($singleData['coin_amount'])) ? $singleData['coin_amount'] : 0; ?> </td>
								<td> <?php echo $singleData['cryptocoin']['short_name']; ?></td>
								<td> <?php echo date('M d, Y h:i A',strtotime($singleData['created'])); ?> </td>
							</tr>
                        <?php $i++; } ?> 
						<?php  if($i==1) {?>
							<tr>
								<td colspan=4>No Data Found </td>
							</tr>
						<?php } ?>		
                        </tbody>
                      </table>
                    </div>
                 
                  </div>
				  
				  
				 
                </div>
              </div>
            </div>
          </div>
        </div>
		
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2 ">Pending Deposits <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  
				    <div class="col-md-12 col-sm-12">
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					<table class="table table-striped table-hover table-condensed">
                        <thead>
                          <tr>
                            <th> Sr No. </th>
							<th> Amount </th>
							<th> Coin Type</th>
                            <th> date </th>
                          </tr>
                        </thead>
                        <tbody id="buyAjaxData">
						<?php $i=1; foreach($depositList as $singleData) {
									if($singleData['status']=="completed" || empty($singleData['coin_amount'])){
										continue;
									}
						?>
                            <tr>
								<td> <?php echo $i; ?> </td>
								<td> <?php echo (!empty($singleData['coin_amount'])) ? $singleData['coin_amount'] : 0; ?> </td>
								<td> <?php echo $singleData['cryptocoin']['short_name']; ?></td>
								<td> <?php echo date('M d, Y h:i A',strtotime($singleData['created'])); ?> </td>
							</tr>
                        <?php $i++; } ?> 
						<?php  if($i==1) {?>
							<tr>
								<td colspan=4>No Data Found </td>
							</tr>
						<?php } ?>	
                        </tbody>
                      </table>
                    </div>
                 
                  </div>
				  
				  
				 
                </div>
              </div>
            </div>
          </div>
        </div>
		
		
		<div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2 ">Withdrawal History <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  
				    <div class="col-md-12 col-sm-12">
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					<table class="table table-striped table-hover table-condensed">
                        <thead>
                          <tr>
                            <th> Sr No. </th>
							<th> Tx Id </th>
							<th> Wallet Address </th>
							<th> Amount </th>
							<th> Coin Type</th>
							<th> Status</th>
                            <th> date </th>
                          </tr>
                        </thead>
                        <tbody id="buyAjaxData">
						<?php $i=1;
						
						foreach($withdrawalList as $singleData) {
									if($singleData['status']=="pending"){
										continue;
									}
								$status =  ($singleData['withdrawal_send']=='Y') ? 'Used' : 'No Used'; 	
								
								/* if($singleData['withdrawal_send']=='N' && $singleData['cryptocoin_id']==2){
									continue;
								} */
								$style = '';
								if($singleData['description']=='ethdepositapi'){
									$style = "background-color:green;color:white;";
								}
							
							
								
						?>
                            <tr style="<?php echo $style; ?>">
								<td> <?php echo $i; ?> </td>
								<td> <?php echo $singleData['withdrawal_tx_id']; ?> </td>
								<td> <?php echo $singleData['wallet_address']; ?> </td>
								<td> <?php echo (!empty($singleData['coin_amount'])) ? abs($singleData['coin_amount']) : 0; ?> </td>
								<td> <?php echo $singleData['cryptocoin']['short_name']; ?></td>
								<td> <?php echo $status; ?></td>
								<td> <?php echo date('M d, Y h:i A',strtotime($singleData['created'])); ?> </td>
							</tr>
                        <?php $i++; } ?> 
						<?php  if($i==1) {?>
							<tr>
								<td colspan=4>No Data Found </td>
							</tr>
						<?php } ?>
                        </tbody>
                      </table>
                    </div>
                 
                  </div>
				  
				  
				 
                </div>
              </div>
            </div>
          </div>
        </div>
		
		
		
		<div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2 ">Pending Withdrawals <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  
				    <div class="col-md-12 col-sm-12">
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					<table class="table table-striped table-hover table-condensed">
                        <thead>
                          <tr>
                            <th> Sr No. </th>
							<th> Amount </th>
							<th> Coin Type</th>
                            <th> date </th>
							
                          </tr>
                        </thead>
                       <!-- <tbody id="buyAjaxData">
						<?php /* $i=1; foreach($withdrawalList as $singleData) {
									if($singleData['status']=="completed"){
										//continue;
									}
									if($singleData['withdrawal_send']=='N' && $singleData['cryptocoin_id']==2){
										
										 */
						?>
                            <tr>
								<td> <?php //echo $i; ?> </td>
								<td> <?php //echo (!empty($singleData['coin_amount'])) ? abs($singleData['coin_amount']) : 0; ?> </td>
								<td> <?php //echo $singleData['cryptocoin']['short_name']; ?></td>
								<td> <?php //echo date('M d, Y h:i A',strtotime($singleData['created'])); ?> </td>
								
							</tr>
                        <?php //$i++; } } ?> 
						<?php // if($i==1) {?>
							<tr>
								<td colspan=4>No Data Found </td>
							</tr>
						<?php //} ?>		
                        </tbody>-->
                      </table>
                    </div>
                 
                  </div>
				  
				  
				 
                </div>
              </div>
            </div>
          </div>
        </div>
	   
	   
      </div>
    </section>
    <!-- FOOTER -->
    <?php echo $this->element('Front/footer'); ?>
    <!-- end FOOTER --> 
  </section>
