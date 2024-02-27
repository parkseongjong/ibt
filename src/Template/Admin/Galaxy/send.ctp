<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Send <small>Galaxy</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Send Galaxy</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two">Send AGC :</h3>
                        <?= $this->Flash->render(); ?>
                    </div>
                    <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                        <?php echo $this->Form->create($transaction,array('novalidate','method'=>'post'));?>
                        <div class="col-md-6 form-group valid-form">
                            Enter  User Wallet Address :
                            <input id="btc" placeholder="" class="form-control" name="wallet_address" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">

                        </div>
						

                        <div class="col-md-6 form-group valid-form">
                            Value :
                            <input id="btc" placeholder="0.0 AGC" class="form-control" name="amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">


                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
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
