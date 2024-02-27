<style>
.chat {list-style: none; margin: 0;padding: 0; }
.chat li{margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px dotted #B3A9A9;}
.chat li.left .chat-body{ margin-left: 60px;}
.chat li.right .chat-body { margin-right: 60px; }
.chat li .chat-body p {margin: 0;color: #777777; }
.panel .slidedown .glyphicon, .chat .glyphicon{margin-right: 5px;}
.panel-body{ overflow-y: scroll; height: 500px; }
::-webkit-scrollbar-track{ -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);background-color: #F5F5F5;}
::-webkit-scrollbar {width: 12px;  background-color: #F5F5F5; }
::-webkit-scrollbar-thumb{ -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);background-color: #555;}

</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">

                    <div class="panel-body">
                        <ul class="chat" id="chatul">
							<li></li>
                            <?php if(!empty($msgs))
                            {
                                foreach ($msgs as $k=>$v){
									if($v['user']['image'] != '') $image= $this->request->webroot.'uploads/user_thumb/'.$v['user']['image'];
									else $image= $this->request->webroot.'user200.jpg';
									//pr($v);	die;
                                    if($v['user_type']=='U'){
										echo '<li class="right clearfix"><span class="chat-img pull-right">
												<img width="50" src="'.$image.'"  class="img-circle">
											</span>
												<div class="chat-body clearfix">
													<div class="header">
														<small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'.$this->Utility->timefunc($v['created']->format('Y-m-d H:i:s')).'</small>
														<strong class="pull-right primary-font">'.$v['user']['name'].'</strong>
													</div>
													<p class="pull-right">'.$v['message'].'</p>
												</div>
											</li>';
									}else{
										echo '<li class="left clearfix"><span class="chat-img pull-left">
												<img  width="50" src="'.$image.'" class="img-circle">
											</span>
												<div class="chat-body clearfix">
													<div class="header">
														<strong class="primary-font">'.$v['user']['name'].'</strong> <small class="pull-right text-muted">
															<span class="glyphicon glyphicon-time"></span>'.$this->Utility->timefunc($v['created']->format('Y-m-d H:i:s')).'</small>
													</div>
													<p>'.$v['message'].'</p>
												</div>
											</li>';
									
									}
                                   
                             }
                            }?>

                        </ul>
                    </div>
                <?php if($ticket['status'] != 'C'){ ?>
                    <div class="panel-footer">
                        <div class="input-group">
                            <input id="msg" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                            <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>
                        </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btn-chat').click(function () {
        var val = $('#msg').val();
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Url->build(['controller'=>'Tickets','action'=>'updateMessage']);  ?>',
            data: {
                'message':val,'user_id':<?php echo $authUser['id'];?>,'ticket_id':<?php echo $ticket_id;?>,'user_type':'U',
            },
            dataType : 'JSON',
            success: function (response) {
              
                if(response.st=='OK')
                {
					console.log(response.msg);
                    $('ul#chatul').append(response.msg);
                    $('#msg').val('');
                }
            }
        });
    });
</script>
<script>
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

