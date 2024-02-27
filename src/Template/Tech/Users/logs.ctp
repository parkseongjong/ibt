<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Login Logs <small>Logs</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Logs</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
                <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
					 <div class="clearfix"></div>
            <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						 
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('name',array('placeholder'=>'Name','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
                         <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('ip',array('placeholder'=>'IP address','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                              <?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                        </div> 
						  <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php  echo $this->Form->input('end_date',array('placeholder'=>'End date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                      <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>
                        
						 
                     </div>
                      
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Users</h3>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>                        
                            <th>Ip Address</th>
                            <th>Date Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;
                        foreach($logs->toArray() as $k=>$data){
							
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['name']; ?></td>
                            <td><?php echo $data['ip_address']; ?></td>
                            <td><?php echo date('d M Y g:i A',strtotime($data['created'])); ?></td>
                        </tr>
                        <?php $count++;} ?>
                        <?php  if(count($logs->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'log_search')));
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
    </section>
</div>

<script>
$(document).ready(function() {
        
			$('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

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


