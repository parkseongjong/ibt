<style>
    .input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?=__("Add Tmp Coin Address");?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"><?=__("Add Tmp Coin Address");?></li>
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
                                <h3 class="w3_inner_tittle two"><?=__("Add Tmp Coin Address");?> :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                                <form action="/tech/tmp-coin-address/add" method="POST" id="frm" enctype="multipart/form-data">
                                    <div class="col-md-12 col-md-offset-3">
                                        <?= $this->Flash->render(); ?>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6 btn-group btn-group-toggle" data-toggle="buttons">
                                            <?=__("Type");?> : <br>
                                            <label for="coin_type1" class="btn btn-success" >
                                                <input type="radio" id="coin_type1" name="coin_type" value="btc_address" class="" checked ><?= __('BTC Address');?>
                                            </label>
                                            <label for="coin_type2" class="btn btn-danger"  style="margin-left:5px;">
                                                <input type="radio" id="coin_type2" name="coin_type" value="eth_address" class=""  ><?= __('ETH Address');?>
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6 form-group valid-form">
                                            <?=__("Upload CSV");?>:
                                            <input type="file" id="csv_file" name="csv_file" value="" class="form-control input-style ">
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-12" style="margin-top:15px;">
                                            <button type="button" id="add_btn" class="btn btn-primary" onclick="submit_check()"><?=__("Add");?></button>
                                            <button type="button" id="refresh_btn" class="btn btn-primary" onclick="user_check()"><?=__("Check Address Usage");?></button>
                                            <button type="button" id="fill_btn" class="btn btn-primary" onclick="empty_user_fill()"><?=__("Fill an Addressless User");?></button>
                                        </div>
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
<script>
    function user_check(){
        if($('input[name="coin_type"]:checked').val() == ''){
            alert('사용 여부 수정 체크');
            return;
        }
        $('#refresh_btn').hide();
        $.ajax({
            type: 'post',
            url: '/tech/tmp-coin-address/usercheck',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            data: {
                'coin_type' : $('input[name="coin_type"]:checked').val(),
            },
            success:function(resp) {
                alert('update count : '+ resp);
                location.reload();
                //console.log(resp);
            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    }
    function submit_check(){
        if($('#csv_file').val() == ''){
            $('#csv_file').focus();
            return;
        }
        if($('input[name="coin_type"]:checked').val() == ''){
            $('input[name="coin_type"]').focus();
            return;
        }
        $('#add_btn').hide();
        $('#frm').submit();
    }
    function empty_user_fill(){
        if($('input[name="coin_type"]:checked').val() == ''){
            $('input[name="coin_type"]').focus();
            return;
        }
        $('#fill_btn').hide();
        $('#frm').attr('action','/tech/tmp-coin-address/emptyuserfill');
        $('#frm').submit();
        $('#frm').attr('action','');
    }

</script>
