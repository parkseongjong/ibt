<style>

.width table tr th{background:#999;}
.width table tr td{border-bottom:1px solid #999; border-right:1px solid #999;}
.width table tr td:first-child{border-left:1px solid #999;}
.date{width:100%; display:inline-block; font-size:20px; font-weight:bold; line-height:17px;  text-align:center; 
 

 
</style>

 <div class="right_col" role="main">
          <div class="">

            <div class="page-title">
				<?= $this->Flash->render() ?>
              <div class="title_left">
                <h3>
                      Bulk Update
                     
                  </h3>
              </div>
			</div>

            <div class="clearfix"></div>

            <div class="row">
				

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 
                  <div class="x_content">
					  <?= $this->Flash->render() ?>
					  
					   
					  <?php echo $this->Form->create(null,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>
					  
					  <div class="width">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr style="height: 30px;">
							<th>&nbsp;</th>
							 <th>Mon</th>
							<th>Tue</th>
							<th>Wed</th>
							<th>Thu</th>
							<th>Fri</th>
						  </tr>
						  <?php foreach($date_arr as $date){
							  
						   ?>
						  <tr style="margin-top:10px">
							  <td width="10%"><div class="date"><?=$date?></div></td>
						  <?php for($i=0;$i<5;$i++){ 
							if($i!=0){
								$date = date('Y-m-d', strtotime('+1 day', strtotime($date))); 
							}
							if($date>date('Y-m-d')){
								echo '<td></td>';
							}else{
						  ?>
						   
						   
							<td width="18%">
								<div class="value_data">
									<?php 
										
										$key = array_search($date, $record_date); 
										//var_dump($key);
										if($key===false && $key !== 0 ){
										//if($key=='' && $key != 0 ){
											$first =$second=$third='';
										}else{
											$first =$record[$key]['first_value'];
											$second=$record[$key]['second_value'];
											$third=$record[$key]['third_value'];
										}
										
										
											
									?>
									<input type="text" name="<?=$date?>[1]" value="<?=$first?>" />
									<input type="text" name="<?=$date?>[2]"  value="<?=$second?>" />
									<input type="text" name="<?=$date?>[3]" value="<?=$third?>" />
								</div>		
							</td>
							<?php }} ?>
							
							
						  </tr>
						  
						  <?php } ?>
						  
						</table>
						</div>
 
					  
					   
					  
					  
					  
					   
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success']); ?>
                        </div>
                      </div>
                    </form>
					
				  
                  </div>
                </div>
              </div>
              
		</div>
		
		
	 </div>
</div> 
 <?php
		echo $this->Html->script(
				array(
					'Admin/moment.min.js',
					'Admin/daterangepicker.js',
					
				));
				echo $this->fetch('script');
	?>
<script>
$(document).ready(function() {
	$("#alldays").change(function(){  
		if(this.checked == false){ 
			$('input:checkbox.days').prop("checked", false);
		}else{
			$('input:checkbox.days').prop("checked", true);
		}
	});

        
	$('#start-date').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		format: 'YYYY-MM-DD',
		maxDate:0

	});
	$('#end-date').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		format: 'YYYY-MM-DD',
		maxDate:0

	});
});

</script>
