<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> KYC Documents </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">KYC Documents</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>

            <div class="w3agile-validation w3ls-validation ">
                <div class="agile-validation agile_info_shadow">
                    <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                        <div class="input-info">
                            <h3 class="w3_inner_tittle two">KYC Documents :</h3>
                        </div>
                        <div class="  form-body form-body-info">
                            <?php echo $this->Form->create('',array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>

                            <?= $this->Flash->render() ?>
                            <div class="item form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Adhar Card <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    $disable ='';
                                    $disable=($adhar_card['status']=='Y')?'disabled':'';
                                    echo $this->Form->input('file_name',array($disable,'class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"file")); ?>
                                    <?php  echo $this->Form->input('type',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"hidden",'value'=>'A')); ?>
                                    <span id="contact_message"></span>
                                </div>

                                <div class="col-md-4">
                                    <?php if($adhar_card['status'] =='Y'){ ?>

                                   <button type="button" class="btn btn-success"> <i class="fa fa-check"></i>Approved</button>
                                    <?php }else{ ?>
                                        <?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success','id'=>'adhar-card']); ?>
                                    <?php } ?>
                                </div>
                            </div>

                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $( "#adhar-card" ).click(function( event ) {
        event.preventDefault();
        var type = $("#type").val();
        var file_name = $("#file-name").val().replace(/C:\\fakepath\\/i, '');
        if(file_name != '')
        {
            $(".overlay-contact").show();
            $.ajax({
                url: '<?php echo $this->Url->build(['controller'=>'kyc','action'=>'addDoc']); ?>',
                dataType: "JSON",
                data: {file_name: file_name, type:type},
                type: "POST",
                success: function(output)
                {
                    $("#contact_message").html(output.string);

                }
            });
        }
        else{
            $("#contact_message").html('<div class="alert-danger"><strong>Error! </strong>Please select a file.</div>');
        }
    });
</script>