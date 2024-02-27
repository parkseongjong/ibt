<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
.select2-container .select2-selection--single {box-sizing: border-box;cursor: pointer;display: block;height: 35px;user-select: none;-webkit-user-select: none;}
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Admin Fees Settings'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?> </a></li>
            <li class="active"><?= __('Admin Fees Settings'); ?> </li>
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

                            <div class="  form-body form-body-info" style="display: inline-block;width: 100%;">
                                <?php echo $this->Form->create('',array('method'=>'post',"id"=>"form-two"));?>
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-12 form-group valid-form">
										<div class="col-md-4">
											<?= __('Deposit Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="deposit_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['deposit_fee'];?>">
										</div>
										
										<div class="col-md-4">
											<?= __('Withdrawal Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="withdrawal_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['withdrawal_fee'];?>">
										</div>
										
										<div class="col-md-4">
											<?= __('Main Account → Trading Account Transfer Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="main_to_trading_transfer_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['main_to_trading_transfer_fee'];?>">
										</div>
										
										<div class="col-md-4">
                                            <?= __('Trading Account → Main Account Transfer Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="trading_to_main_transfer_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['trading_to_main_transfer_fee'];?>">
										</div>
										
										<div class="col-md-4">
                                            <?= __('Buy/Sell Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="buy_sell_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['buy_sell_fee'];?>">
										</div>
										
										<div class="col-md-4">
                                            <?= __('Loan Deposit Fees %');?>
										</div>
										<div class="col-md-8">
											<input placeholder=" Referring Amount %" class="form-control" name="loan_deposit_fee" required style="width:10%;border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['loan_deposit_fee'];?>">
										</div>
                                    </div>
                                    <div class="form-group col-md-12">
										
                                        <input id="two" class="btn btn-primary btnSubmit" name="update_referring_amount" value="<?= __('Submit');?>" type="submit">
                                    </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
    </section>
</div>
