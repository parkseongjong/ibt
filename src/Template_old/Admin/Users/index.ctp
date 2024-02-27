<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Users <small>Listing</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
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
                            <?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('name',array('placeholder'=>'Name','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('email',array('placeholder'=>'Email','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
                          <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('phone_number',array('placeholder'=>'Contact Number','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div> 
						 
                     </div>
                     <div class="form-group">
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('unique_id',array('placeholder'=>'Wallet Address','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div> 
						  <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12">
                         <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                       <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                        </div> 
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
					 </div>
                  
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Users</h3>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table table-bordered table-striped">
                        <thead>
                        <tr> 
                            <th>#</th> 
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th> 
                            <th>Phone number</th>
                            <th>Wallet </th>
                            <th>Date Of Registration </th>
                            <th>IP </th>
                            <th>Galaxy coins</th>
                            <th>BTC coins</th>                            
                            <th>Sponser</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;
                        foreach($users->toArray() as $k=>$data){
							
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['name']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['phone_number']; ?></td>                            
                            <td><?php echo $data['unique_id']; ?> </td>
                            <td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
                            <td><?php echo $data['ip_address']; ?></td>    
                            <td><?php echo $data['ZUO']; ?></td>
                            <td><?php echo $data['BTC']; ?></td>
                            <td><?php echo $data['referral_user']['name']; ?></td>
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'search')));
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


