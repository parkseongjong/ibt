<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Referral Setting </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Referral Setting</li>
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
                                <h3 class="w3_inner_tittle two">Add/Edit Referral :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('method'=>'post'));
				  ?><input type="hidden" id="referal_id" value="" name="id"/>
                               
                                    <?= $this->Flash->render() ?>
                                   
									 <div class="col-md-3 form-group valid-form">
                                        Referral Percent:
                                        <?php  echo $this->Form->input('referal_percent',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"referal_percent","required"=>true));?>
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
                                <h3 class="w3_inner_tittle two">List of Referrals:</h3>
                            </div>
                            <table id="table-two-axis " class="two-axis table dataTable">
							<thead>
							<tr>
								<th>S No.</th>
								<th>Referral percent</th>
								<th class="column-title no-link last"><span class="nobr">Action</span>
							</tr>
							</thead>
							<tbody>
							<?php
							$count= 1;
								
							 foreach($listing->toArray() as $k=>$data){
								if($k%2==0) $class="odd";
								else $class="even";
							?>
							<tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
								<td> <?=$count?></td>
								<td class="referal_percent_<?= $data['id']; ?>"><?php echo $data['referal_percent'];?> </td>											
								<td class=" last">
									<a href="javascript:void(0)" onclick="edit_section(<?php echo $data['id'] ?>)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									
								</td>				
								
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
                


        </div>
		</div>
    </section>
</div>
    <script>
		
	function edit_section(id){
    
		$("#referal_id").val(id);
		var referal_percent_old_value  =  $(".referal_percent_"+id).text();
		referal_percent_old_value = referal_percent_old_value.trim();
		$("#referal_percent").val(referal_percent_old_value);
		$("#referal_percent").focus();
		
	}
	


    </script>
