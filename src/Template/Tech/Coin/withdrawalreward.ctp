<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
.open > .dropdown-menu {
    display: block;
    height: 400px;
    overflow-y: scroll;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Withdrawal Rewards Management'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"> <?= __('Withdrawal Rewards Management'); ?> </li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two"><?= __('Withdrawal Rewards'); ?></h3>
                    </div>
                    <?php echo $this->Form->create($add,array('method'=>'post','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left input_mask','style'=>'padding:10px;')); ?>
                        <div class="form-group">
                            <div class="col-md-7 col-xs-12 col-md-offset-3">
                                <?= $this->Flash->render() ?>

                            <!-- Token Button List -->
                                <div class="token-button-list-checkbox-container">
                                    <label for=""><?= __('View in scroll form') ?></label>
                                    <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
                                </div>
                                <div class="btn-group-toggle" data-toggle="buttons" role="group">
                                    <?php foreach($coinList as $k=>$data) {
										echo $this->Form->button($data, array('value' => $k, 'id'=>$k,'name' => 'btn_submit','type'=>'button', 'class' => 'btn btn-secondary','style'=>'margin-right:5px;'));
									} ?>
                                </div>
                                <!-- /Token Button List -->

                                <div class="col-md-7 col-xs-12 form-group valid-form">
                                    <?= __('Coin Name: ') ?>
                                    <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control input-style col-md-7 col-xs-12 required','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,"required"=>true));?>
                                </div>

                                <div class="clearfix"></div>
									
                                <div class="col-md-7 col-xs-12 form-group valid-form">
                                    <?= __('Username: ') ?>
                                    <?php  echo $this->Form->input('user_ids',array('id'=>'user_name','class' => 'form-control input-style col-md-7 col-xs-12 required','label' =>false,"type"=>"select","required"=>true, "multiple"=>true, "placeholder"=>__('Please select user'),'style' => 'width:100%; display: inline-block;'));?>
                                </div>
									
                                <div class="clearfix"></div>
                                <div class="col-md-7 col-xs-12 form-group valid-form">
                                    <?= __('Amount: ') ?>
                                    <?php  echo $this->Form->input('amount',array('class' => 'form-control input-style col-md-7 col-xs-12 required','label' =>false,"type"=>"text","id"=>"amount","required"=>true));?>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group col-md-12">
                                    <?php  echo $this->Form->button(__('Submit'), ['type' => 'submit','class'=>'btn btn-primary']); ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        user_search_select2('user_name'); /* user name search */
        tokenButtonList();

        $('.btn-secondary').click(function (){
            let coin = $(this).val();
            $("#coin_first_id").val(coin);
            $('.btn-secondary').not(this).removeClass("active").css({'outline':'none'});
            $(this).toggleClass('active').css({'outline':'solid','outline-style':'groove','outline-color':'#f7f7f7','outline-width':'1px'});
        });
    });
</script>
