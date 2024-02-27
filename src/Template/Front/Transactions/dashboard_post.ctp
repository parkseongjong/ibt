<section class="ml0 my-wallet">
    <section class="main-content">
      
      <h3>Scan & pay </h3>
      <div class="row">

		<div class="col-md-12"> 
		  
			<div align="center">
				<p class="m-b-15">This is permanent wallet address. To deposit, pay it to this address.</p>
				<br>
				<p class="m-b-15">After doing payment, please wait for 1 hour to reflect in your dashboard. Transaction verification takes time. </p>
				<br>
				<p>Address :<strong><?php echo $wallertAddress; ?></strong></p>
				
				
				<p><img  src="<?php echo $qrImage; ?>"></p>
				<em>Scan the code &amp; to make the payment</em>
				<p></p>
				<h2>&nbsp;</h2>
			</div>
		  
        </div>
        
      </div>
      <!-- Orders Book -->
      
    </section>
    <!-- FOOTER -->
    <?php echo $this->element('Front/footer'); ?>
    <!-- end FOOTER --> 
  </section>












