<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Btc <small>Transactions</small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Btc Transactions</li>
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
              <h4 class="widget-title"><b>RECEIVE BITCOINS</b></h4>
            </div>
            <div class="widget-card-content p-b-5">
              <div class="row">
                <div class="col-md-4 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">BTC - Available Balance</div>
                  </div>
                  <div class="widget-stats-right" style="max-width:100%">
                    <div class="widget-stats-value f-s-25"> <?php echo number_format((float)$totalBtcCoin,8) ?> BTC </div>
                    <!--<div class="widget-desc"><?php //echo date("Y-m-d H:i:s",1515178214); ?></div>-->
                  </div>
                  </a>
					<form method="post" accept-charset="utf-8" action="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'buybtc']);  ?>">
						<input id="deposit-btn" class="btn btn-primary m-b-30" value="Deposit" type="submit">
					</form>
                  <!--<button id="deposit-btn" type="button" class="btn btn-primary m-b-30" data-toggle="modal" href="#deposit-modal">Deposit </button>-->
                </div>
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
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="panel panel-inverse endless_page_template">
                <div class="panel-heading">
                  <h4 class="panel-title">Transactions HISTORY</h4>
                </div>
                <div class="panel-body">
                  <p class="desc">Navigate through your transactions</p>
                  <div class="table-responsive">
                    <table class="table table-inverse m-b-0">
                      <thead>
                        <tr>
                          <th>ID</th>
						  <th>Transaction Id</th>
                          <th>Amount</th>
						  <th>Transaction Type</th>
						  <th>Remark</th>
						  <th>STATUS</th>
                          <th>DATE</th>
                        </tr>
                      </thead>
					  <?php if(!empty($btcTrans)) {
						  $i=1;
						foreach($btcTrans->toArray() as $single) {	
						?>
                      <tbody>
						<tr>
                          <td><?php echo $i; ?></td>
						  <td><?php echo $single['trans_id']; ?></td>
                          <td><?php echo number_format((float)abs($single['btc_coins']),8) ?> BTC</td>
                          <td><?php echo ucfirst($single['trans_type']); ?></td>
						  <td><?php echo ucfirst($single['coin_type']); ?></td> 
						  <td><?php echo ucfirst($single['status']); ?></td>
                          <td><?php echo date("M d, Y h:i A",strtotime($single['created_at'])); ?></td> 
                        </tr>
                      </tbody>
						<?php $i++; }
						}
						?>
                    </table>
					
					<?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'btcsearch')));
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
      </div>
    </div><br />
<br />

  </section>
</div>

<script>
$('document').ready(function(){
	
	jQuery('.table-responsive').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
					url: urli,
					data: {key:keyy},
					type: 'POST',
					success: function(data) {
						if(data){
							
							jQuery('.table-responsive').html(data);
							
						}
					}
		});
		
	});
	
});
</script>
</scrip>
