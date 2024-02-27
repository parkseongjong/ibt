
  <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Annual Membership</th>
                            <th>User Level</th>
                            <th>Category</th>
                            <th>Id Type</th>
                            <th>Id Number</th>
                            <th>Id Document Front</th>
                            <th>Id Document Back</th>
                            <th>Id Document Status</th>
                            <th>Scan Copy</th>
                            <th>Scan Copy Status</th>
                                                      
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						
						$statusArr = ['P'=>"Pending",'R'=>"Rejected",'A'=>"Approved"];
                        $count = $serial_num;
						$webroot = $this->request->webroot;
                        foreach($users->toArray() as $k=>$data){
						$kyArr = ['N'=>'Not Uploaded','P'=>'Pending','C'=>'Completed','R'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
						
						$idDocumnetRejectedReason = ($data['id_document_status'] =="R" && !empty($data['id_document_reject_reason'])) ? " ( ".$data['id_document_reject_reason']." )" : "";
						$scanCopyRejectedReason = ($data['scan_copy_status'] =="R" && !empty($data['scan_copy_reject_reason'])) ? " ( ".$data['scan_copy_reject_reason']." )" : "";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
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
                            <td><?php echo $data['user_level']; ?></td>
                            <td><?php echo $data['category']; ?></td>
                            <td><?php echo $data['id_type']; ?></td>
                            <td><?php echo $data['id_number']; ?></td>
                            <td>
							<?php 
								if(!empty($data['id_document_front'])){
									$idFront = $data['id_document_front'];
									echo "<a target='_blank' href='".BASEURL."uploads/id_verification/{$idFront}' ><img src='{$webroot}uploads/id_verification/$idFront' width=50 /></a>";
								} 
							?>
							</td>
							<td>
							<?php 
								if(!empty($data['id_document_back'])){
									$idBack = $data['id_document_back'];
									echo "<a target='_blank' href='".BASEURL."uploads/id_verification/{$idBack}' ><img src='{$webroot}uploads/id_verification/$idBack' width=50 /></a>";
								} 
							?>
							</td>
							 <td>
								<?php echo $statusArr[$data['id_document_status']].$idDocumnetRejectedReason; ?><br/>
								<a href="javascript:void(0);" onclick="change_status(<?php echo $data['id'] ?>,'id_document');">Change</a>
							 </td>
							 <td>
							<?php 
								if(!empty($data['scan_copy'])){
									$scanCopy = $data['scan_copy'];
								echo "<a target='_blank' href='".BASEURL."uploads/id_verification/{$scanCopy}' ><img src='{$webroot}uploads/id_verification/$scanCopy' width=50 /></a>";
								} 
							?>
							<td>
								<?php echo $statusArr[$data['scan_copy_status']].$scanCopyRejectedReason; ?><br/>
								<a href="javascript:void(0);" onclick="change_status(<?php echo $data['id'] ?>,'scan_copy');">Change</a>
							</td>
                            
							
							
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'kyclistsearch')));
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

      function change_status(rowId,type){
          $("#status_id").val(rowId);
          $("#status_type").val(type);
          $("#myModal").modal('show');
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
              success: function (data) {

              }
          });
      }

      $("#update_status_op").change(function(){
          var getVal = $(this).val();

          if(getVal=="R"){
              $(".reason").show();
          }
          else {
              $(".reason").hide();
          }
      });

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

