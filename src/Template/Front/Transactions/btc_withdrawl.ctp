<div class="content-wrapper dashboardCon">
<?php 
$curerntDate = time(); 
$launchDate = strtotime("2018-01-20 13:30:00");
if($launchDate > $curerntDate) { 
?>
<div class="outerTempCon">
    <div class="innerTempCon text-center">
      <h3>Thanks for Joining Hedgeconnect.co! </h3>
      <br />
      <p>This functionality currently unavailable.</p>
	</div>
</div>

<?php } ?>
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Btc <small>Withdrawal</small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Btc Withdrawal</li>
    </ol>
  </section>
  <!-- Main content -->
  <section id="content" class="table-layout">
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="widget widget-card dynamic inverse-mode bg-dark-grey">
            <div class="widget-card-content">
              <h4 class="widget-title"><b>WITHDRAWAL</b></h4>
            </div>
           <!-- <div class="alert alert-alert alert-danger m-r-10 m-l-10"> <a class="close" data-dismiss="alert"></a>You must have bought at least 50 BTC from ICO or have at least 50 BTC in your HedgeConnect Wallet before a withdrawal is possible. </div>--> 
            <!--<div class="alert alert-alert alert-info m-r-10 m-l-10">  <a class="close" data-dismiss="alert"></a>We charge an extra fee of 0.001 BTC for a single withdrawal</div>.--> <?=$this->Flash->render();?>
            <div class="widget-card-content p-b-5">
              <div class="row">
                <div class="col-md-8 col-xs-12">
                  <?php echo $this->Form->create($this->Url->build(['prefix'=>'front','controller'=>'transactions' , 'action'=>'btcWithdrawl']),array('method'=>'post'));?>
                    <input type="hidden" name="request_type" required="" id="request_type" value="transaction" class=" form-control">
                    <div class="form-group">
                      <label class="control-label" >Withdrawal (BTC)<span class="text-danger">*</span></label>
                      <input type="text" name="btc_amount" required="" id="id_withdrawal" placeholder="btc amount" class=" form-control">
                    </div>
                    <!--<div class="form-group">
                      <input name="ga_code" autofocus="" maxlength="254" class=" form-control" id="id_ga_code" placeholder="2FA Code" type="text">
                    </div>
                    <div class="form-group p-t-20">
                      <label class="control-label" >Bitcoin Wallet<span class="text-danger">*</span></label>
                      <select name="processors" id="id_processors" class=" form-control">
                        <option value="" selected="">---------</option>
                        <option value="" selected="">---------</option>
                        <option value="" selected="">---------</option>
                        <option value="" selected="">---------</option>
                      </select>
                    </div>-->
                    <div class="form-group">
                      <input type="text" name="target_wallet_address" id="id_target_wallet_address" class=" form-control" placeholder="wallet address">
                    </div>
                    <!--<a id="withdraw-a" class="btn btn-primary m-b-10" data-toggle="modal" href="#withdrawal-modal" >Withdraw</a>-->
					<input id="withdraw-a" class="btn btn-primary m-b-10" value="Withdraw" type="submit">
                  <?php echo $this->Form->end(); ?>
                </div>
                <div class="col-md-4 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">Bitcoin Wallet - Available Balance</div>
                  </div>
                  <div class="widget-stats-right">
                    <div class="widget-stats-value f-s-25"> <?=number_format((float)$getUserBtcAmt,8);?> BTC </div>
                    <!--<div class="widget-desc">4th January 2018 00:59</div>-->
                  </div>
                  </a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      <div class="inner_content_w3_agile_info">
        <div class="clearfix"></div>
        <!-- /blank -->
        <div class="blank_w3ls_agile">
          <div class="blank-page agile_info_shadow">
            <div class="panel panel-inverse endless_page_template">
              <div class="panel-heading">
                <h4 class="panel-title">WITHDRAWAL HISTORY</h4>
              </div>
              <div class="panel-body">
                <p class="desc">Navigate through your withdrawals</p>
                <div class="table-responsive">
                  <table class="table table-inverse m-b-0">
                    <thead>
                      <tr>
					    <th>S&nbsp;No.</th>
                        <th>BTC AMOUNT</th>
                        <th>Wallet Address</th>
						<th>Status</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
					
					<?php
					
						$count= 1;
							
						 foreach($listing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?=$class?>">
                      <td><?=$count?></td>
                      <td><?=number_format((float)abs($data['btc_coins']),8);?></td>
                      <td><?php echo $data['wallet_address']; ?></td>
                      <td><?php echo $data['status']; ?></td>
                      <td><?=$data['created_at']->format('d M Y H:i:s');?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									   } ?>
					
                    </tbody>
                  </table>
				  
				   <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'btcWithdrawlSearch')));
						echo "<div class='pagination' style = 'float:right'>";
	 
						// the 'first' page button
						$paginator = $this->Paginator;
						echo $paginator->first("First");

						// 'prev' page button, 
						// we can check using the paginator hasPrev() method if there's a previous page
						// save with the 'next' page button
						if($paginator->hasPrev()){
						echo $paginator->prev("Prev");
						}

						// the 'number' page buttons
						echo $paginator->numbers(array('modulus' => 2));

						// for the 'next' button
						if($paginator->hasNext()){
						echo $paginator->next("Next");
						}

						// the 'last' page button
						echo $paginator->last("Last");

						echo "</div>";
								
				?> 
				  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <br />
    <br />
  </section>
</div>
<script>
$(window).load(function(){
                //$('#onload').modal('show');
				
            });
</script>