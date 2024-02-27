<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
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
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Add new conversion:</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('novalidate','method'=>'post'));
				  ?>
                               
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-3 form-group valid-form">
                                        From Date :
                                         <?php  echo $this->Form->input('from_date',array('class' => 'form-control input-style datepicker','label' =>false,"type"=>"text"));?>
                                        
                                    </div>
                                    <div class="col-md-3 form-group valid-form">
                                        To Date:
                                        <?php  echo $this->Form->input('to_date',array('class' => 'form-control input-style datepicker','label' =>false,"type"=>"text"));?>
                                    </div>

									 <div class="col-md-3 form-group valid-form">
                                        Conversion Rate:
                                        <?php  echo $this->Form->input('rate',array('class' => 'form-control input-style','label' =>false,"type"=>"text"));?>
                                    </div>
                                     <div class="col-md-3 form-group valid-form">
                                        Limits:
                                         <?php  echo $this->Form->input('total_coins',array('class' => 'form-control input-style','label' =>false,"type"=>"text"));?>
                                    </div>


                                    <div class="clearfix"></div>
									  <div class="form-group col-md-12">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary']); ?>
									  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
				  <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">List of conversion rates:</h3>
                            </div>
                            <table id="table-two-axis " class="two-axis table dataTable">
							<thead>
							<tr>
								<th>S No.</th>
								<th>From date</th>
								<th>To date</th>
								<th>Rate</th>
								<th>Total</th>
								<th>Left </th>
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
								<td><?=$data['from_date']->format('d M Y');?> </td>
								<td><?=$data['to_date']->format('d M Y');?> </td>
								
								<td><?=number_format((float)$data['rate'],8);?> </td>											
								<td><?=$data['total_coins']?> </td>											
								<td><?=$data['left_coins']?> </td>					
								
							</tr>
							<?php $count++; } ?>
							<?php  if(count($listing->toArray()) < 1) {
								echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
						   } ?>	
							</tbody>
						</table>
						   <?php $this->Paginator->options(array('url' => array('controller' => 'Settings', 'action' => 'search')));
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
                

                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Referring Amount %:</h3>
                            </div>
                            <div class="  form-body form-body-info" style="display: inline-block;width: 100%;">
                                <form data-toggle="validator" novalidate  method="post" id="form-two">
                                    <?= $this->Flash->render('referral') ?>
                                    <div class="col-md-12 form-group valid-form">
                                        Referring Amount %
                                        <input placeholder=" Referring Amount %" class="form-control" name="referral_amount" required style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="number" value="<?php echo $setting['referral_amount'];?>">
                                    </div>
                                    <div class="form-group col-md-12">
										
                                        <input id="two" class="btn btn-primary btnSubmit" name="update_referring_amount" value="Submit" type="submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    <!--            
			 <div class="w3agile-validation w3ls-validation mt20">
                 <div class="agile-validation agile_info_shadow">
                       <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                           <div class="input-info">
                                <h3 class="w3_inner_tittle two">Conversion:</h3>
                            </div>
                           <div class="  form-body form-body-info" style="display: inline-block;width: 100%;">
                                <form data-toggle="validator" novalidate  method="post" id="form-three">
                                  
                                    <div class="col-md-12 form-group valid-form">
                                        1 BTC Coin (in INR) :
                                        <input placeholder="  1 BTC Coin (in INR)" value="<?php echo $setting['btc_to_inr'];?>" class="form-control" name="btc_to_inr" required style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
                                    </div>

                                  <div class="form-group col-md-12">
                                     <input id="three" class="btn btn-primary btnSubmit" name="update_coin" value="Submit" type="button">
                                 </div>
                               </form>
                           </div>
                       </div>
                   </div>
               </div>
-->

        </div>
		</div>
    </section>
</div>
    <script>
		
	
        $('.btnSubmit').on('click',function (e) {
           // alert($('#update-amount').serialize());
            var id = $(this).attr('id');
            var data = $('#form-'+id).serialize();
            $.ajax({
            type : 'POST',
            url : '<?php echo $this->Url->build(['controller'=>'settings','action'=>'update']); ?>',
            dataType : 'JSON',
            data :data,
               success: function(response){
                if(response=='')
                {
                    new PNotify({
                        title: 'Success',
                        text: 'Status changed successfully!',
                        type: 'success',
                        styling: 'bootstrap3',
                        delay:1200
                    });
                }
                else {

                    $.each(response, function(k, v) {
                        new PNotify({
                            title: '403 Error',
                            text: v,
                            type: 'error',
                            styling: 'bootstrap3',
                            delay:1200
                        });
                        });
                }

               }
            });
            e.preventDefault();
           return false;
        });
    </script>
