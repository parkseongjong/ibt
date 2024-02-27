
                      <table class="table table-striped jambo_table bulk_action  two-axis table">
                        <thead>
                          <tr class="headings">
							<th class="column-title">S No.</th>
							<th class="column-title">Module</th>
							<th class="column-title"> <input id="all" class="styled" name="all" type="checkbox">Select All</th>
						                        
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = 1;
							echo $this->Form->input('total_module',array('value'=>count($all_modules->toArray()),"type"=>"hidden"));
							foreach($all_modules->toArray() as $data){ 
								$check =  '';
								if (array_key_exists($data['id'],$all_access)){
									
											$check = "checked";
										
								}
								
								?>
							<tr>
								<td><?= $count?>.</td>
								<td class=" "><?php echo $data['module_name']; ?></td>
								
								<td><?php echo  $this->Form->checkbox($data['id'], ['class'=>'check_box','value' => '1',$check]) ?></td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($all_modules->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No Modules define</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							<?php  echo $this->Form->button('Reset', ['type' => 'reset','class'=>'btn btn-primary']); ?>
							<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success submit-access']); ?>
                        </div>
                      </div>
       
  
  <script>
  $(document).ready(function () {

	$("#all").change(function(){  
	if(this.checked == false){ 
		$('input:checkbox.check_box').prop("checked", false);
    }else{
		$('input:checkbox.check_box').prop("checked", true);
	}
});
	});
	
  </script>         
                    
