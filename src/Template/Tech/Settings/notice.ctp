<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'notice']);  ?>"><?= __('Notices Management');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'notice']);  ?>"><?= __('Notices Management');?></a></li>
        </ol>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
			<?php echo $this->Form->create('',array('method'=>'post'));?> <!-- for ajax post token -->
			<?php echo $this->Form->end();?>
                <div class="clearfix"></div>
                <?= $this->Flash->render() ?>
                <div class="row">
                    <form id="frm" method="get" class="form-horizontal form-label-left input_mask">
                        <div class="form-group">
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <?php  echo $this->Form->input('id',array('placeholder'=>__('Sr No.'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
                        </div>
                        <a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'addnotice']) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?= __('Create Notice');?> </a>
                    </form>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div id="divLoading"> </div><!--Loading class -->
                            <div class="x_content">
                                <section class="main-content">
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            <div class="table-responsive">
                                                <table class="table" style=" width:1500px">
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?= __('Subject');?></th>
                                                        <th><?= __('Contents');?></th>
                                                        <th><?= __('Attachments');?></th>
                                                        <th><?= __('Date & Time');?></th>
                                                        <th><?= __('action');?></th>
                                                    </tr>
                                                    <?php foreach($BoardNotice->toArray() as $ticket){
                                                        $issueFile = "&nbsp;";
                                                        if(!empty($ticket['file'])){
                                                            $issueFile = "<img src='".$this->request->webroot."uploads/board/".$ticket['file']."' width=50 />";
                                                        }
                                                        ?>
                                                        <tr id="notice_row_<?= $ticket['id']; ?>">
                                                            <td><?php echo $ticket['id']; ?></td>
                                                            <td><?php echo h($ticket['subject']); ?></td>
                                                            <td><?php echo h($ticket['contents']); ?></td>
                                                            <td><?php echo $issueFile; ?></td>
                                                            <td style="width: 110px;"><?php echo $ticket['created']; ?></td>
                                                            <td class=" last">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('action');?>
                                                                        <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'editnotice',$ticket['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?= __('Edit');?> </a></li>
                                                                        <li><a href="javascript:void(0)" onclick="delete_section(<?php echo $ticket['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> <?= __('Delete');?> </a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php }  ?>
                                                </table>
                                                <?php $this->Paginator->options(array('url' => array('controller' => 'Settings', 'action' => 'notice')));
													echo "<div class='pagination' style = 'float:right'>";
													$paginator = $this->Paginator;
													echo $paginator->first(__("First"));
													if($paginator->hasPrev()){
														echo $paginator->prev(__("Prev"));
													}
													echo $paginator->numbers(array('modulus' => 9));
													if($paginator->hasNext()){
														echo $paginator->next(__("Next"));
													}
													echo $paginator->last(__("Last"));
													echo "</div>";
                                                ?>
                                            </div>
                                        </div>
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
		datepicker_set('start-date');
		datepicker_set('end-date');

    });
    function delete_section(id){
        bootbox.confirm("<?= __('Are you sure that, you want to delete this?');?>", function(result) {
            if(result === true){
                jQuery.ajax({
                    //url: 'delete',
                    url: '<?php echo $this->Url->build(['controller'=>'Settings','action'=>'deleteNotice']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
                    data: {'id':id},
                    type: 'POST',
                    success: function(data) {
                        if(data == 1){
                            jQuery("#notice_row_"+id).remove();
                            new PNotify({
                                title: '<?= __('Success!');?>',
                                text: '<?= __('Record deleted successfully!');?>',
                                type: 'success',
                                styling: 'bootstrap3',
                                delay:1200
                            });

                        }
						if(data == 'forbidden'){

                            new PNotify({
                                title: '<?= __('403 Error');?>',
                                text: '<?= __('You do not have permission to perform this action');?>',
                                type: 'error',
                                styling: 'bootstrap3',
                                delay:1200
                            });

                        }
                    },
                    error: function (request) {
                        new PNotify({
                            title: '<?= __('Error');?>',
                            text: '<?= __('This record is being referenced at other place. So, you cannot delete it.');?>',
                            type: 'error',
                            styling: 'bootstrap3',
                            delay:1200
                        });

                    },
                });
            }
        });
    }
</script>