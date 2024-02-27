 <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr class="headings" style="text-align: center;vertical-align: middle">
                            <th  style="text-align: center;vertical-align: middle">#</th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Username'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Name'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Email'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Annual Membership'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Annual Membership Date'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Membership Expiry Date'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('User Level'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Disable Deposit'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Phone Number'); ?></th>
                            <th  style="text-align: center;vertical-align: middle"><?= __('Date of Registration'); ?> </th>

                            <th class="column-title"  style="text-align: center;vertical-align: middle"><?= __('Status'); ?> </th>
                            <th class="column-title no-link last"  style="text-align: center;vertical-align: middle"><span class="nobr"><?= __('Action'); ?></span>

                        </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							foreach($users->toArray() as $k=>$data){
							$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
						
							if($k%2==0) $class="odd";
							else $class="even";
							?>
							<tr class="<?=$class?>">
								<td><?=$count?></td>
								<td><?php echo $data['username']; ?></td>
								<td><?php echo $data['name']; ?></td>
								<td><?php echo $data['email']; ?></td>
                                <td><?php if ($data['annual_membership'] == 'Y'){
                                        echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'Y','checked'=>true,'onChange'=>'membership_change('.$data['id'].');']);
                                    } else {
                                        echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'N','checked'=>false,'onChange'=>'membership_change('.$data['id'].');']);
                                    }
                                    ?></td>
                                <td id="ann_mem_date_<?= $data['id'] ?>"><?php if(!empty($data['annual_membership_date'])) {echo $data['annual_membership_date']->format('d M Y H:i:s');} else {echo "";} ?></td>
                                <td id="mem_expire_<?= $data['id'] ?>"><?php if(!empty($data['membership_expires_at'])) {echo $data['membership_expires_at']->format('d M Y H:i:s');} else {echo "";}  ?></td>
                                <td><?php echo $data['user_level']; ?></td>
                                <td><?php if ($data['deposit'] == 'Y'){
                                        echo $this->Form->checkbox('deposit',['id'=>'deposit_'.$data['id'].'','value'=>'Y','checked'=>true,'onChange'=>'deposit_change('.$data['id'].');']);
                                    } else {
                                        echo $this->Form->checkbox('deposit',['id'=>'deposit_'.$data['id'].'','value'=>'N','checked'=>false,'onChange'=>'deposit_change('.$data['id'].');']);
                                    }
                                    ?></td>
                                <td><?php echo $data['phone_number']; ?></td>
							 								
								<td><?php echo $data['created']; ?></td>
								<td class=" ">
									<input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['enabled']; ?>" />
									<a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?php echo $data['id'] ?>)">
									<?php  if($data['enabled'] == 'Y'){
										echo '<button type="button" class="btn btn-success btn-xs">Active</button>'; 
									}else{
										echo '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
									} ?></a>
								</td>
								<td class=" last">
								<div class="dropdown">
								  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Show Action
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu">
<!--									<li><a href="--><?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'translist',$data['id']]); ?><!--"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> All ETH Transactions </a></li>-->
<!--									<li><a href="--><?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],2]); ?><!--"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ETH Transactions </a></li>-->
<!--									<li><a href="--><?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],3]); ?><!--"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> RAM Transactions </a></li>-->
<!--									<li><a href="--><?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],4]); ?><!--"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ADMC Transactions </a></li>-->
									<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a></li>
									<li><a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a></li>
<!--									<li><a onclick="checkConfrim('--><?php //echo md5($data['username']) ?><!--//');" href="javascript:void(0);"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Impersonate </a></li> -->
								  </ul>
								</div>
							</td>
							</tr>
							<?php $count++;
							} ?>
							
							<?php  if(count($users->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'search')));
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

 <script>

     function checkConfrim(getdata){
         if(confirm("Are You Really want to impersonate ?")){
             url = '/front/users/impersonate/'+getdata;

             window.open(url, '_blank');
         }
         else {
             return false;
         }

     }
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

     function export_f(v) {
         $('#export').val(v);
         $("form").submit();
         $('#export').val('');
     }


     function delete_section(id){
         bootbox.confirm("Are you sure?", function(result) {
             if(result == true){
                 jQuery.ajax({
                     //url: 'delete',
                     url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'deleteProgram']); ?>',
                     data: {'id':id},
                     type: 'POST',
                     success: function(data) {
                         if(data == 1){
                             jQuery("#user_row_"+id).remove();
                             new PNotify({
                                 title: 'Success',
                                 text: 'Record Delete successfully!',
                                 type: 'success',
                                 styling: 'bootstrap3',
                                 delay:1200
                             });

                         }if(data == 'forbidden'){

                             new PNotify({
                                 title: '403 Error',
                                 text: 'You donot have permission to access this action.',
                                 type: 'error',
                                 styling: 'bootstrap3',
                                 delay:1200
                             });

                         }
                     },
                     error: function (request) {
                         new PNotify({
                             title: 'Error',
                             text: 'This record is being referenced in other place. You cannot delete it.',
                             type: 'error',
                             styling: 'bootstrap3',
                             delay:1200
                         });

                     },
                 });
             }
         });
     }


     function membership_change(id) {
         if ($("#membership_" + id).prop('checked') === true) {
             var value = "Y";
         } else if ($("#membership_" + id).prop('checked') === false) {
             var value = "N";
         }
         $.ajax({
             type: 'post',
             url: '<?= $this->Url->build(['controller' => 'reports', 'action' => 'updateMembership']);  ?>',
             data: {'id':id,'annual_membership': value},
             success: function (resp) {
                 if (resp.success === "false") {
                 } else {
                     var result = JSON.parse(resp)
                     if(result.data.timeNow !== null || result.data.timeNow !== undefined || result.data.timeNow !== '' || result.data.expiry !== null
                         || result.data.expiry !== undefined || result.data.expiry){
                         $("#ann_mem_date_" + id).html(result.data.timeNow);
                         $("#mem_expire_" + id).html(result.data.expiry);
                     } else {
                         alert("Null");
                     }

                 }
             }
         });
     }

     function deposit_change(id) {
         if ($("#deposit_" + id).prop('checked') === true) {
             var value = "Y";
         } else if ($("#deposit_" + id).prop('checked') === false) {
             var value = "N";
         }
         $.ajax({
             type: 'post',
             url: '<?= $this->Url->build(['controller' => 'reports', 'action' => 'updatedeposit']);  ?>',
             data: {'id':id,'deposit': value},
             success: function (data) {

             }
         });
     }

     function change_user_status(id){
         var status = $("#user_status_"+id).val();
         if(status == 'Y'){
             var ques= "Do you want change the status to DEACTIVE";
             var status = "N";
             var change = '<button type="button" class="btn btn-danger btn-xs">Deactive</button>'
         }else{
             var ques= "Do you want change the status to ACTIVE";
             var status = "Y";
             var change = '<button type="button" class="btn btn-success btn-xs">Active</button>';
         }


         bootbox.confirm(ques, function(result) {
             if(result == true){
                 jQuery.ajax({
                     url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'status']); ?>',
                     data: {'id':id,'status':status},
                     type: 'POST',
                     success: function(data) {
                         if(data == 1){
                             jQuery("#status_id_"+id).html(change);
                             jQuery("#user_status_"+id).val(status);
                             new PNotify({
                                 title: 'Success',
                                 text: 'Status changed successfully!',
                                 type: 'success',
                                 styling: 'bootstrap3',
                                 delay:1200
                             });

                         }
                         if(data == 'forbidden'){

                             new PNotify({
                                 title: '403 Error',
                                 text: 'You donot have permission to access this action.',
                                 type: 'error',
                                 styling: 'bootstrap3',
                                 delay:1200
                             });

                         }
                     }
                 });
             }
         });


     }


 </script>