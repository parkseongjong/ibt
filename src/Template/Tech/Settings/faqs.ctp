<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'faqs']);  ?>"> <?= __('FAQs Management');?> </a></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i>  <?= __('Home');?></a></li>
			<li class="active"><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'faqs']);  ?>"> <?= __('FAQs Management');?> </a></li>
		</ol> 
	</section>
	<section id="content" class="table-layout">
		<div class="inner_content_w3_agile_info">
			<div class="agile-validation agile_info_shadow">
				<div class="clearfix"></div>
				<?php echo $this->Form->create('',array('method'=>'post'));?> <!-- for ajax post token -->
				<?php echo $this->Form->end();?>
				<?= $this->Flash->render() ?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div id="divLoading"></div><!--Loading class -->
							<div class="x_content">
								<a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'addfaqs']) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?= __('Create FAQ');?> </a>
								<section class="main-content">
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
											<div class="table-responsive">   
												<table class="table" style=" width:1500px">
													<tr>
														<th>#</th>
														<th> <?= __('Category');?></th>
														<th> <?= __('Subject');?></th>
														<th> <?= __('Contents');?></th>
														<th> <?= __('Language');?></th>
														<th> <?= __('Action');?></th>
													</tr>
												<?php 
													$i=1; foreach($BoardFaq->toArray() as $ticket){
														$issueFile = "&nbsp;";
														if(!empty($ticket['file'])){
															$issueFile = "<img src='".$this->request->webroot."uploads/board/".$ticket['file']."' width=50 />";
														}
												?>
													<tr id="faq_row_<?= $ticket['id']; ?>">
														<td><?php echo $i; ?></td>
														<td><?php echo __($ticket['category']); ?></td>
														<td><?php echo __($ticket['subject']); ?></td>
														<td><?php echo __($ticket['contents']); ?></td>
														<td><?php echo $ticket['lang']; ?></td>
														<td class=" last">
															<div class="dropdown">
																<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> <?= __('Action');?>
																	<span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'editfaqs',$ticket['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i>  <?= __('Edit');?> </a></li>
																	<li><a href="javascript:void(0)" onclick="delete_section(<?php echo $ticket['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i>  <?= __('Delete');?> </a></li>
																</ul>
															</div>
														</td>
													</tr>
												<?php $i++; }  ?>
												</table>
												<?php $this->Paginator->options(array('url' => array('controller' => 'Settings', 'action' => 'faqs')));
													echo "<div class='pagination' style = 'float:right'>";
													// the 'first' page button
													$paginator = $this->Paginator;
													echo $paginator->first(__("First"));
													// 'prev' page button, 
													// we can check using the paginator hasPrev() method if there's a previous page
													// save with the 'next' page button
													if($paginator->hasPrev()){
														echo $paginator->prev(__("Prev"));
													}

													// the 'number' page buttons
													echo $paginator->numbers(array('modulus' => 9));

													// for the 'next' button
													if($paginator->hasNext()){
														echo $paginator->next(__("Next"));
													}

													// the 'last' page button
													echo $paginator->last(__("Last"));

													echo "</div>";
												?>
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

	function delete_section(id){
		bootbox.confirm("<?= __('Are you sure that, you want to delete this?');?>", function(result) {
			if(result === true){
				jQuery.ajax({
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'Settings','action'=>'deleteFAQ']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
					data: {'id':id},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#faq_row_"+id).remove();
							new PNotify({
								title: '<?= __('Success!');?>',
								text: '<?= __('Record deleted successfully!');?>',
								type: 'success',
								styling: 'bootstrap3',
								delay:1200
							});

						}if(data === 'forbidden'){

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