<?php echo $this->element('Front/profile_sidebar'); ?>
<style>
.align-middle, .table>tbody>tr>td {
    vertical-align: top;
}




.container
{
  width:450px;
  cursor:default;
  margin:20px auto;
  max-height:350px;
  overflow-y:scroll;
}
.container::-webkit-scrollbar 
{
width: 3px;
max-width: 3px;
height: auto;
max-height: 8px;
}
.container::-webkit-scrollbar-thumb 
{
background: #f0f0f0;
border-radius: 5px;
max-width: 3px;
}
.container::-webkit-scrollbar-track {
background: #9F6905;
  border-radius:5px;
}
.Area
{
  margin:0 auto;
  width:400px;
  background-color:rgba(240, 240, 240, 0.2);
  display:table;
  padding:5px;
  border-radius:5px;
  margin-bottom:10px;
}
.L
{
  float:left;
}
.Area img
{
  width:50px;
  height:50px;
  border-radius:50%;
  border:2px solid #f0f0ff;
  padding:5px;    
}
.Area img:hover
{
    -moz-box-shadow: 0 5px 5px rgba(223, 120, 8, 1);
-webkit-box-shadow: 0 5px 5px rgba(223, 120, 8, 1);
box-shadow: 0 5px 5px rgba(223, 120, 8, 1);
       -webkit-transition: all 1.5s;
    -moz-transition: all 1.5s;
    transition: all 1.5s;
  cursor:pointer;
}
.R
{
    float:right;
}
.text
{
  color: #000000;
font-family: tahoma;
font-size: 13px;
  font-weight:lighter;
line-height: 30px;
  width:300px;
  border:1px solid #f0f0f0;
  background-color:rgba(255, 255, 255, 0.6);
  margin-top:10px;
  border-radius:5px;
  padding:5px;  
}
.Area textarea
{
  font-size:12px;
  width:90%;
  max-width:90%;
  min-width:90%;
  padding:5%;
  border-radius:5px;
  border:1px solid #f0f0f1;
  max-height:50px;
  height:100px;
  min-height:100px;
  background-color:#333;
  color:#fff;
  outline:none;
  border:1px solid transparent;
  resize:none;
}
.Area textarea:focus
{
  color:#333;
  border:1px solid #ccc;
     -webkit-transition: all 1.5s;
    -moz-transition: all 1.5s;
    transition: all 1.5s;
  background-color:#fff;
}
.Area .note
{
  color:#9F6905;
  font-size:10px;
}
.R .tooltip
{
  font-size:10px;
  position:absolute;
  background-color:#fff;
  padding:5px;
  border-radius:5px;
  border:2px solid #9F6905;
  margin-left:70px;
  margin-top:-70px;
  display:none;
  color:#545454;
}
.R .tooltip:before
{
    content: '';
    position: absolute;
    width: 1px;
    height: 1px;
    border: 5px solid transparent;
    border-right-color: #9F6905;
    margin-top: 10px;
    margin-left: -17px;
}
.R:hover .tooltip
{
  display:block;
}

.L .tooltip
{
  font-size:10px;
  position:absolute;
  background-color:#fff;
  padding:5px;
  border-radius:5px;
  border:2px solid #9F6905;
  margin-left:70px;
  margin-top:-70px;
  display:none;  
  color:#545454;
}
.L .tooltip:before
{
    content: '';
    position: absolute;
    width: 1px;
    height: 1px;
    border: 5px solid transparent;
    border-right-color: #9F6905;
    margin-top: 10px;
    margin-left: -17px;
}
.L:hover .tooltip
{
  display:block;
}
a
{
  text-decoration:none;
}


.Area input[type=submit]
{
  font-size:12px;
  padding:5px;
  border-radius:5px;
  border:1px solid #f0f0f1;
  background-color:#333;
  color:#fff;
  outline:none;
  border:1px solid transparent;
  float:left;
}
.Area input[type=submit]:hover
{
  color:#fff;
  border:1px solid #ccc;
     -webkit-transition: all 1.5s;
    -moz-transition: all 1.5s;
    transition: all 1.5s;
  background-color:#9F6905;
} 
.validation
{
  float:left;
  background-color:#ccc;
  border-radius:5px;
  padding:5px;
  font-size:12px;
  line-height:14px;
  height:0px;
  margin-left:5px;
  display:none;
}
br
{
  clear:both;
  height:20px;
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
                  <div class="header">Conversation</div>
					<div class="container" id="myboxchat">
					  <?php foreach($tickets as $ticket) {
							$mainClass = ($ticket['user_id']==1) ? "L" : "R";
							$msgClass = ($ticket['user_id']==1) ? "R" : "L";
							if($ticket['user_id']==1){
								$imgUrl = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRrEyVlaWx0_FK_sz86j-CnUC_pfEqw_Xq_xZUm5CMIyEI_-X2hRUpx1BHL";
							}
							elseif(!empty($user->image)){
								$imgUrl = $this->request->webroot."uploads/user_thumb/".$user->image;
							}
							else{
								$imgUrl = $this->request->webroot."user200.jpg";
							}
						  ?>
					  <div class="Area">
						<div class="<?php echo $mainClass; ?>">
						  <a href="javascript:void(0);">
						<img src="<?php echo $imgUrl; ?>"/>
							<div class="tooltip">Sami Massadeh - 28 Years<br/>Doctor <br/>Jordan</div></a>
						</div>
						<div class="text <?php echo $msgClass; ?> text<?php echo $msgClass; ?>"><?php echo $ticket['message']; ?>
						</div>
					  </div>
					  <?php } ?>
					  
						<?php echo $this->Form->create($user,array('id'=>'formsupport','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
						<input type="hidden" name="support_id" value="<?php echo $support_id; ?>" />
						<div class="Area">
						<textarea required name="message"></textarea>
						  <br/><br/>
						  <input type="submit" value="SEND"/>
						 
						  <br/>
						
					  </div>
					  <?php echo $this->Form->end();?>
					  
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
  
  <script>
  function clickX()
{
  $(".validation").animate({ 'height': '16px' }, 500).show();
}

$(document).ready(function(){
	$('#myboxchat').scrollTop($('#myboxchat')[0].scrollHeight);
})
  </script>