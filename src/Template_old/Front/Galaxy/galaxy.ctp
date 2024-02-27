<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Buy <small>Galaxy</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Buy Galaxy</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two">Buy Galaxy Coin :</h3>
                       <?= $this->Flash->render(); ?>
                    </div>
                    <?php if(!isset($btc)) echo ' <div class="panel panel-danger"><div class="panel-body">You have no btc to convert into galaxy coin</div></div>';
                    else echo ' <div class="panel panel-success"><div class="panel-body">'.$btc['btc'].' BTC = '.$btc['galaxy'].' Galaxy</div></div>';
                    
                    ?>
                    
                    <div class="form-body form-body-info"  style="display: inline-block;width: 100%;">
						<?php echo $this->Form->create($transaction,array('novalidate','method'=>'post'));?>
                      
							<div class="form-group">
								
							<div class="col-md-6 form-group valid-form">
								Enter galaxy value :
                               <input onchange="convert_btc(this.value)" id="btc" placeholder="0.0 galaxy" class="form-control" name="amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
                               <span id="btc_value"></span>
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
 <script>
function convert_btc(coin){
	if(coin>0){
		
		jQuery.ajax({ 
			url: '<?php echo $this->Url->build(['controller'=>'galaxy','action'=>'bitcoin_value']);  ?>',
			data: {coin:coin},
			type: 'POST',
			success: function(data) {
				if(data){
					
					$("#btc_value").text(data);
					
				}
			}
		});
		
	}else{
		$("#btc_value").text('');
	}
}
				
 </script>
