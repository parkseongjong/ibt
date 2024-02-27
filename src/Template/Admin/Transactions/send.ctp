<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Send <small><?php echo $coinNameStatic; ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Send <?php echo $coinNameStatic; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two">Send <?php echo $coinNameStatic; ?> Coin :</h3>
                        <?= $this->Flash->render(); ?>
                    </div>
                    <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                        <?php echo $this->Form->create($transaction,array('method'=>'post'));?>
                        <div class="col-md-4 form-group valid-form">
                            Enter  User Wallet Address :
                            <input id="btc" placeholder=""  class="form-control required" name="wallet_address" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">

                        </div>


                        <div class="col-md-4 form-group valid-form">
                            Value :
                            <input id="btc" placeholder="0.0 <?php echo $coinNameStatic; ?>" class="form-control required" name="amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">


                        </div>
						
						<div class="col-md-4 form-group valid-form">
                            Date :
                            <input id="payment_date" readonly class="form-control datepicker required" name="payment_date" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">


                        </div>
                      
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
                            <input id="mySubmit" class="btn btn-primary" value="Submit" type="submit">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
			
            <!-- <div class="row agile-tables">
                <div class="w3l-table-info agile_info_shadow ">
                
                    <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						 
						  <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  //echo $this->Form->input('name',array('placeholder'=>'User name','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
						 <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  //echo $this->Form->input('unique_id',array('placeholder'=>'Wallet','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                        <?php  //echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                         <?php // echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                       <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  //echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                        </div> 
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>
							
						
                     </div>
                    
                  
				</form>-->
		
                   <h3 class="w3_inner_tittle two">Transactions</h3>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>S No</th>
                            <th>HC Coins</th>
							<th>User</th>
                            <th>Wallet Address</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;

                        foreach($listing->toArray() as $k=>$data){
                            if($k%2==0) $class="odd";
                            else $class="even";
                            ?>
                            <tr class="<?=$class?>">
                                <td> <?=$count?></td>
                                <td><?=$data['coin']?></td>
                                <td><?=$data['user']['username']?> </td>
                                <td><?=$data['user']['unique_id']?> </td>
                                <td><?php if(!empty($data['admin_send_date'])) { echo $data['admin_send_date']->format('d M Y'); } else { echo " - ";}?> </td>
                            </tr>
                            <?php $count++; } ?>
                        <?php  if(count($listing->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
                        }  ?>
                        </tbody>
                    </table>
                    <?php  $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'send_search')));
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

