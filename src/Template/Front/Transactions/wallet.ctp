<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?=$display_type?> <small>wallet</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$display_type?> wallet</li>
        </ol>
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
                        <p><strong>3N6siNALmVDTDDPbJggY7LmhtMijbUki4N</strong></p>
                        <p><img width="100" src="<?php echo $this->request->webroot;?>address-wallet.jpg"></p>
                        <em>Scan the code &amp; to make the payment</em>
                        <p></p>
                        <h2>&nbsp;</h2>
                    </div>
                </div>
            </div>
		
        </div>
		  <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two">Buy BTC Coin :</h3>
                       <?= $this->Flash->render(); ?>
					</div>
					<div class="form-body form-body-info">
						<?php echo $this->Form->create($transaction,array('novalidate','method'=>'post'));?>
                      
							<div class="form-group">
								
							<div class="col-md-6 form-group valid-form">
								Enter  <?=$display_type;?> Value :
                               <input id="btc" placeholder="0.0 <?=$display_type?>" class="form-control" name="amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
							</div>


                            <div class="col-md-6 form-group valid-form">
                                Transaction id :
                                <input id="btc" placeholder="transaction id" class="form-control" name="transaction_id" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
                            </div>
									
						    <div class="clearfix"></div>
                            <div class="form-group col-md-12">
                                <input id="mySubmit" class="btn btn-primary" value="Submit" type="submit">
                            </div>
                        </form>
                    </div>
                
                 </div>
            </div>
            <div class="clearfix"></div>
              
        </div>
   
    </section>
   </div>
