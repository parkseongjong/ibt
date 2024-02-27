<?php echo $this->element('Front/profile_sidebar'); ?>
<style>
.align-middle, .table>tbody>tr>td {
    vertical-align: top;
}
</style>
<section class="gebgbg">
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Unread</a></li>
                    <li><a data-toggle="tab" href="#menu1"> Read</a></li>
                  </ul>
                
                  <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                      <h3>Unread</h3>
                      <div class="table-responsive">   
					  <table class="table" style=" width:1500px">
						<tr>
							<th>Sr. No.</th>
							<th>Issue Type</th>
							<th>Issue</th>
							<th>File</th>
							<th>Created_at</th> 
							<th>Action</th> 
						</tr>
						<?php $i=1; foreach($tickets as $ticket){
						if($ticket['admin_reply']=='Y'){ 
						$issueFile = "&nbsp;";
						if(!empty($ticket['issue_file'])){
							$issueFile = "<img src='".$this->request->webroot."uploads/issue_file/".$ticket['issue_file']."' width=50 />";
						}
						?><tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $ticket['issue_type']; ?></td>
							<td><?php echo $ticket['issue']; ?></td>
							<th><?php echo $issueFile; ?></td>
							<td style="width: 110px;"><?php echo $ticket['date']; ?></td>
							<td style="width: 110px;"><a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'conversation',$ticket['id']]) ?>" >View</a></td>
							</tr>
						<?php $i++; } } ?>
					  </table>
                    </div></div>
                    <div id="menu1" class="tab-pane fade">
                      <h3>Read</h3>
					  <div class="table-responsive">   
					  <table class="table">
                      <tr>
							<th>Sr. No.</th>
							<th>Issue Type</th>
							<th>Issue</th>
							<th>File</th>
							<th>Created_at</th>
							<th>Action</th> 
						</tr>
						<?php $i=1; foreach($tickets as $ticket){
							if($ticket['admin_reply']=='N'){ 
						$issueFile = "&nbsp;";
						if(!empty($ticket['issue_file'])){
							$issueFile = "<img src='".$this->request->webroot."uploads/issue_file/".$ticket['issue_file']."' width=50 />";
						}

						?>
						 <tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $ticket['issue_type']; ?></td>
							<td><?php echo $ticket['issue']; ?></td>
							<th><?php echo $issueFile; ?></td>
							<td style="width: 110px;"><?php echo $ticket['date']; ?></td>
							<td><a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'conversation',$ticket['id']]) ?>" >View</a></td>
							 <tr> 
							<?php $i++; } } ?>
					  </table>
                    </div></div>
                  </div>
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>