<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Queries <small>Detail</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Contact Us</li>
        </ol>
    </section>
    <section id="content" class="table-layout">
		<div class="inner_content_w3_agile_info">
			<div class="agile-validation agile_info_shadow">
            <div class="clearfix"></div>
            <?= $this->Flash->render() ?>

            <div class="row">
				<form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('email',array('placeholder'=>'Email','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('name',array('placeholder'=>'Name','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
                          
						 <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('status',array('empty'=>'Select status','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array('0'=>'Not Replied','1'=>'Replied'))); 
							 ?>
                       </div>     
                        </div>
                     <div class="form-group">
						  <div class="col-md-4 col-sm-4 col-xs-12">
                        <?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12">
                         <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                         <div class="col-md-2 col-sm-2 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>
					 </div>
                  
				</form>
		
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="divLoading"> </div><!--Loading class -->
                  <div class="x_content">
					
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action two-axis table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S.No. </th>
                            <th class="column-title">Person Name. </th>
                            <th class="column-title">Email </th>
                            <th class="column-title">Phone Number </th>
                             <th class="column-title">Query</th>
                            <th class="column-title">Is replied</th>
                             <th class="column-title">Sent At</th>
                            <th class="column-title no-link last"><span class="nobr">Reply</span>
                            </th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = 1;
							//pr($Reports);die;
							foreach($ContactUs->toArray() as $data){
								//pr($data);die;
								if($data['status']==0){
									$status="No";$button="danger";$text="Reply";
								}else{
									$status="Yes";$button="success";$text="View Reply";
								}
							 ?>
							<tr id ="user_row_<?= $data['id']; ?>">
								<td><?= $count?>.</td>
								<td><?= $data['name']?>.</td>
								<td><?= $data['email']?>.</td>
								<td><?= $data['phone']?>.</td>
								
								
								<td class=" "><?= substr($data['message'],0,50).'...'; ?> </td>
								
								
								<td class=" "><span class='btn btn-<?=$button?> btn-xs'><?=$status?></span></td>
								<td class=" "><?=date('j M Y g:i A',strtotime($data['created']->format('Y-m-d H:i:s'))); ?> </td>
								
								<td class=" last">
									<a href="<?php echo $this->Url->build(['controller'=>'ContactUs','action'=>'detail',$data['id']]); ?>" class="btn btn-<?=$button?> btn-xs"><i class="fa fa-eye"></i> <?=$text?></a>
									
									
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($ContactUs->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <?php $this->Paginator->options(array('url' => array('controller' => 'ContactUs', 'action' => 'search')));
					echo "<div class='pagination' style = 'float:right'>";
 
					// the 'first' page button
					$paginator = $this->Paginator;
					echo $paginator->first("First");

					// 'prev' page button, 
					// we can check using the paginator hasPrev() method if there's a previous page
					// save with the 'next' page button
					if($paginator->hasPrev()){
					echo $paginator->prev("Prev");
					}

					// the 'number' page buttons
					echo $paginator->numbers(array('modulus' => 2));

					// for the 'next' button
					if($paginator->hasNext()){
					echo $paginator->next("Next");
					}

					// the 'last' page button
					echo $paginator->last("Last");

					echo "</div>";
							
					?>
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
$(document).ready(function() {
        
			$('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				startDate: '-3d'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				startDate: '-3d'

			});


      });

		
		jQuery('.table-responsive').on('click','.pagination li a',function(event){
			event.preventDefault() ;
			var keyy = $('form').serialize();
			var urli = jQuery(this).attr('href');
			jQuery.ajax({ 
						url: urli,
						data: {key:keyy},
						type: 'POST',
						success: function(data) {
							if(data){
								
								jQuery('.table-responsive').html(data);
								
							}
						}
			});
			
		});

	</script>

