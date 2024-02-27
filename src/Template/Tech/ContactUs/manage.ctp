<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    .upload_img:hover{cursor: pointer;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'contact-us','action'=>'manage']);  ?>"><?= __('Contact Queries Management');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'contact-us','action'=>'manage']);  ?>"><?= __('Contact Us');?></a></li>
        </ol>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="clearfix"></div>
				<?php echo $this->Form->create('',array('method'=>'post'));?>
				<?php echo $this->Form->end();?>
                <?= $this->Flash->render() ?>
                <div class="row">
                    <form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask" id="frm">
                        <div class="form-group">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('id',array('placeholder'=>'Sr No.','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('id'))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('email',array('placeholder'=>__('Email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('email'))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('username',array('placeholder'=>__('Username'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('username'))); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>$this->request->query('start_date'))); ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('end_date'))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div id="divLoading"> </div><!--Loading class -->
                            <div class="x_content">
                                <section class="main-content">
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            <h3></h3>
                                            <div class="table-responsive">
                                                <table class="table" >
                                                    <tr>
                                                        <th>#</th>
														<th><?= __('Name');?></th>
                                                        <th><?= __('Phone Number');?></th>
                                                        <th><?= __('Email');?></th>
                                                        <th><?= __('Issue Type');?></th>
                                                        <th><?= __('Issue');?></th>
                                                        <th><?= __('Date & Time');?></th>
                                                        <th><?= __('Attachment');?></th>
                                                        <th><?= __('Response');?></th>
                                                        <th><?= __('action');?></th>
														<th><?= __('reply time');?></th>
                                                    </tr>
                                                    <?php $i=1; foreach($ContactUs->toArray() as $ticket){
														$this->add_system_log(200, $ticket['users_id'], 1, '고객 1대1문의 조회 (이름, 전화번호, 메일)');
                                                        $issueFile = "&nbsp;";
                                                        if(!empty($ticket['file'])){
                                                            $issueFile = "<img src='".$this->request->webroot."uploads/board/".$ticket['file']."' width=50 class='upload_img'/>";
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?= $ticket['id']; ?></td>
															<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $ticket['users_id']; ?>)" class="text-dark"><?= $this->masking('N', $ticket['user']['name']); ?></a></td>
                                                            <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $ticket['users_id']; ?>)" class="text-dark"><?= $this->masking('P', $ticket['user']['username']); ?></a></td>
															<td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $ticket['users_id']; ?>)" class="text-dark"><?= $this->masking('E', $ticket['user']['email']); ?></a></td>
                                                            <td><?= __($ticket['subject']); ?></td>
                                                            <td><?= __($ticket['contents']); ?></td>
                                                            <td style="width: 110px;"><?= $ticket['created']->format('Y-m-d H:i:s'); ?></td>
                                                            <td><?= $issueFile; ?></td>
                                                            <td id="reply_<?= $ticket['id'];?>"><?= $ticket['reply']; ?></td>
                                                            <td><?php
                                                                if(empty($ticket['reply'])){
                                                                    echo '<span class="btn btn-success" onclick="update_reply('.$ticket['id'].','.$ticket['users_id'].')">'.__('Reply').' </span>';
                                                                }else{
                                                                    echo "<button disabled='disabled' class=' btn btn-success ' >".__('Replied')." </button>";
                                                                }
                                                                ?></td>
															<td style="width: 110px;"><?= $ticket['reply_time'] != null ? $ticket['reply_time']->format('Y-m-d H:i:s') : ''; ?></td>
                                                        </tr>
                                                        <?php $i++; }  ?>
                                                </table>
												<?php 
													$searchArr = [];
													foreach($this->request->query as $singleKey=>$singleVal){
														$searchArr[$singleKey] = $singleVal;
													}
													$this->Paginator->options(array('url' => array('controller' => 'ContactUs', 'action' => 'manage')+$searchArr));
													echo "<div class='pagination' style = 'float:right'>";

													$paginator = $this->Paginator;
													echo $paginator->first(__("First"));

													if($paginator->hasPrev()){
														//echo $paginator->prev(__("Prev"));
													}
													// the 'number' page buttons
													echo $paginator->numbers(array('modulus' => 9));
													// for the 'next' button
													if($paginator->hasNext()){
														//echo $paginator->next(__("Next"));
													}
													// the 'last' page button
													echo $paginator->last(__("Last"));
													echo "</div>";
												?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="myModal" class="modal">
                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <span class="close">&times;</span>
                                            <div id="img_area">
                                                <img id="modal_img" src="" width=100%;>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $(document).ready(function() {
        $('#start-date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d'
        });
        $('#end-date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d'
        });
        /* $(".show_tx").click(function(){
          $(this).next().toggle('slow');
      }) */
    });
    function showClick(newthis){
        $(newthis).next().toggle('slow');
    }

	function update_reply(id,user_id){
		$.confirm({
            title: '<?= __("Reply");?>',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<input type="text" placeholder="<?= __('Reply');?>" class="reply form-control" required />' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: '<?= __('Submit');?>',
                    btnClass: 'btn-blue',
                    action: function () {
                        var name = this.$content.find('.reply').val();
                        if(!name){
                            $.alert('<?= __('Please enter reply');?>');
                            return false;
                        }
                        $.ajax({
                            url:'<?php echo $this->Url->build(["controller"=>"ContactUs","action"=>"updatereply"]) ?>',
							beforeSend: function(xhr){
								xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
							},
                            type:'post',
                            data:{
								"reply" : this.$content.find('.reply').val(),
								"id" : id,
								"user_id" : user_id
							},
                            dataType:'JSON',
                            success:function(resp){
                                //toastr.success("<?= __('Success!');?>")
                                window.location.reload();
                            }
                        });
                    }
                },
                cancel: function () {
                    //close
                },
            },
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.

                });
            }
        });
	}
    /* img blow up */
    var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    $(function(){
        $('.upload_img').on('click',function(){
            var src = $(this).attr('src');
            $('#modal_img').attr('src',src);
            $('#myModal').css('display','block');
        });
    })

</script>

