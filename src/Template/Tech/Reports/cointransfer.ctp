<?php $getBalanceOfRealToken = json_decode($getBalanceOfRealToken,true); ?>
<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Coin Transfer </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Coin Transfer</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Coin Transfer :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
							<h2 class="w3_inner_tittle two" align="center">HEDGE Balance : <?php echo $getBalanceOfRealToken['hedge']; ?></h2>
							<h2 class="w3_inner_tittle two" align="center">Ether Balance : <?php echo $getBalanceOfRealToken['ether']; ?></h2>
								<?php echo $this->Form->create($this->Url->build(['controller'=>'reports','action'=>'cointransfer']),array('method'=>'post'));
				  ?>
                               <input type="hidden" value="<?php echo $to_user_id; ?>" id="to_user_id" value="" name="to_user_id"/>
                                    <?= $this->Flash->render() ?>
									
									<div class="row">
                                    <div class="col-md-6 form-group valid-form">
                                        UserName:
                                        <?php  echo $this->Form->input('username',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"username","readonly"=>true,'value'=>$getUserDetail->username));?>
                                    </div>
									</div>
									
									<div class="row">
                                    <div class="col-md-6 form-group valid-form">
                                        Email:
                                        <?php  echo $this->Form->input('email',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"email","readonly"=>true,'value'=>$getUserDetail->email));?>
                                    </div>
									</div>
									
									<div class="row">
                                    <div class="col-md-6 form-group valid-form">
                                        Wallet Address:
                                        <?php  echo $this->Form->input('wallet_address',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"wallet_address","readonly"=>true,'value'=>$getUserDetail->token_wallet_address));?>
                                    </div>
									</div>
									
									<div class="row">
                                    <div class="col-md-6 form-group valid-form">
                                        Coin Amount:
                                        <?php  echo $this->Form->input('coin_amount',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"coin_amount","required"=>true,'value'=>$coinToTransfer));?>
                                    </div>
									</div>
									
                                    <div class="clearfix"></div>
									  <div class="form-group col-md-6">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary']); ?>
									  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
				
                


        </div>
		</div>
    </section>
</div>
    
