<style>
.input-class{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> My Profile </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
           <div class="w3agile-validation w3ls-validation ">

              
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                         
                            <div class="  form-body form-body-info">
								<?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data' ,'novalidate','method'=>'post'));
								?>
                                
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-12 form-group valid-form">
										<label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Username :
										</label>
                                       
                                        <div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('username',array('class' => 'form-control input-class','label' =>false,"type"=>"text")); ?>
                                    </div>
                                    </div>
                                     <div class="col-md-12 form-group valid-form">
                                        <label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Name :
										</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('name',array('class' => 'form-control input-class','label' =>false,"type"=>"text")); ?>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-12 form-group valid-form">
                                       <label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Email :</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('email',array('class' => 'form-control input-class','label' =>false,"type"=>"text")); ?>
                                    </div>
                                    </div>
                                    <div class="col-md-12 form-group valid-form">
                                       <label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Phone Number :</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('phone_number',array('class' => 'form-control input-class','label' =>false,"type"=>"text")); ?>
                                    </div>
                                    </div>
                                     <div class="col-md-12 form-group valid-form">
                                        <label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Sponser :</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('sponser',array('class' => 'form-control input-class','label' =>false,"type"=>"text")); ?>
                                    </div>
                                    </div>
                                    
                                    
                                     <div class="col-md-12 form-group valid-form">
                                        <label class="control-label col-md-2" for="name"> <li class="fa fa-user"></li>
                                        Image :</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
											<?php  echo $this->Form->input('image',array('class' => 'form-control col-md-7 col-xs-12','type'=>'file','label' =>false));
											if($user->image != '') echo '<img width="50px" src="'.$this->request->webroot.'uploads/user_thumb/'.$user->image.'"/>';
											 ?>
											
										</div>
									  </div>
									
                                    

                                    <div class="form-group col-md-offset-3">
                                        <input id="one" class="btn btn-primary btnSubmit" name="update_coin" value="Submit" type="submit">
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
