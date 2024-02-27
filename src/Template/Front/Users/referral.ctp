<?php echo $this->element('Front/profile_sidebar'); ?>
<section>
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>Referral </h3>
                  <div class="row">
                    <div class="col-lg-12">

                
					  <div class="col-lg-10">
                       <div class="inner_content_w3_agile_info">
						  <span class="referral-part">
							<form class="form-horizontal form-label-left dib">
							<div class="item form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Earn by referring new members</label>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<input id="referral-link-header" class="gui-input gui-input-ref form-control" placeholder="Address" name="address" value="<?= BASEURL.$user->referral_code; ?>" style="min-width: 300px" readonly="" type="text">
							<a onclick="copyToClipboard();" style="cursor:pointer;text-decoration:none;" class="field-icon field-icon-reff" title="Copy your referral link">
							<i class="fa fa-link copy-link-ref"> </i>Copy</a>
							<span id="link_copied" style="display:none;">Referral Link Copied</span>
							</div>
							
							</div>
							</form>
							
							<form class="form-horizontal form-label-left dib">
							<div class="item form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Referral Code</label>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<input id="referral-link-header-code" class="gui-input gui-input-ref form-control" placeholder="Address" name="address" value="<?= $user->referral_code; ?>" style="min-width: 300px" readonly="" type="text">
							<a onclick="copyToClipboardCode();" style="cursor:pointer;text-decoration:none;" class="field-icon field-icon-reff" title="Copy your referral code">
							<i class="fa fa-link copy-link-ref" > </i>Copy
							</a>
							<span id="code_copied" style="display:none;">Referral Code Copied</span>
							</div>
							</div>
							</form>
						</span>
								<div class="clearfix"></div>
								<div class="agile-tables">
									<div class="w3l-table-info agile_info_shadow table-responsive">
										<h3 class="w3_inner_tittle two">Referral List</h3>
										<table id="table-two-axis" class="two-axis table">
											<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email</th>
												<th>Register Date</th>
											   
											</tr>
											</thead>
											<tbody>
											<?php
											$count= 1;
											
											 foreach($listing->toArray() as $k=>$data){
												
												if($k%2==0) $class="odd";
												else $class="even";
											?>
											<tr class="odd">
												<td><?=$count;?></td>
												<td><?=$data['username']?></td>
												<td><?=$data['email']?></td>
												
												<td><?=$data['created']->format('M d, Y')?></td>
												
											 </tr>
										   <?php $count++; } ?>
											<?php  if(count($listing->toArray()) < 1) {
												echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
										   } ?>	
											</tbody>
										</table>
										 <?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'referral',)));
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
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
	    <script>
function copyToClipboard() {
  var copyText = document.getElementById("referral-link-header");
  copyText.select();
  document.execCommand("Copy");
  $("#link_copied").show();
}

function copyToClipboardCode() {
  var copyText = document.getElementById("referral-link-header-code");
  copyText.select();
  document.execCommand("Copy");
  $("#code_copied").show();
 
}
</script>