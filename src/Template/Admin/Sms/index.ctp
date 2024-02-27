<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Settings </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">

            <div class="w3agile-validation w3ls-validation ">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">

                                    <div class="x_content">

                                        <?php echo $this->Form->create('',array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>

                                        <?= $this->Flash->render() ?>
                                        <!--<span class="section page-title">Manage Cms Pages</span> -->
                                        <!--Loading class --> <div id="divLoading"> </div>
                                        <div class="item form-group" id = "cms_main">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Select User <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php  echo $this->Form->input('email',array('class' => 'form-control col-md-7 col-xs-12 multiselect-ui','label' =>false,'type'=>'select','options'=>$users,'multiple'=>'multiple' )); ?>
                                            </div>
                                        </div>
                                        <div class="item form-group" id = "cms_main">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Subject <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php  echo $this->Form->input('subject',array('class' => 'form-control col-md-7 col-xs-12 users','label' =>false,'type'=>'text')); ?>
                                            </div>
                                        </div>
                                            <div class="item form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">SMS Content <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <?php  echo $this->Form->input('sms',array('class' => 'form-control col-md-7 col-xs-12 editor','label' =>false,"type"=>"textarea")); ?>
                                                </div>
                                            </div>

                                            <div class="ln_solid"></div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-3">
                                                    <?php  echo $this->Form->button('Reset', ['type' => 'reset','class'=>'btn btn-primary']); ?>
                                                    <?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success']); ?>
                                                </div>
                                            </div>
                                        </div> <!-- Cms page Details --->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<script>

    $(function() {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true
        });
    });
</script>