<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> SCAN & PAY </h1>
        
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
		 <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>

            <!-- /blank -->
            <div class="blank_w3ls_agile">
                <div class="blank-page agile_info_shadow">
                    <div align="center">
                        <p class="m-b-15">This is permanent wallet address. To deposit, pay it to this address.</p>
                        <br>
                        <br>
                        <br>
						<p>Invoice No. : <strong><?php echo $transId; ?></strong></p>
						<h3 class="text-center"> <strong><?php echo $BtcCoin; ?> BTC</strong></h3>
                        <p>Address :<strong><?php echo $wallertAddress; ?></strong></p>
                        <p><img  src="<?php echo $this->request->webroot.$qrImage;?>"></p>
                        <em>Scan the code &amp; to make the payment</em>
                        <p></p>
                        <h2>&nbsp;</h2>
                    </div>
                </div>
            </div>
		
        </div>
        
    </section>
 </div>
  <script>
	    $(document).ready(function() {
	  $('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});


      });
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
		
  </script>

