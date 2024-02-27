<style>
    .chat {list-style: none; margin: 0;padding: 0; }
    .chat li{margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px dotted #B3A9A9;}
    .chat li.left .chat-body{ margin-left: 60px;}
    .chat li.right .chat-body { margin-right: 60px; }
    .chat li .chat-body p {margin: 0;color: #777777; }
    .panel .slidedown .glyphicon, .chat .glyphicon{margin-right: 5px;}
    .panel-body{ overflow-y: scroll; height: 250px; }
    ::-webkit-scrollbar-track{ -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);background-color: #F5F5F5;}
    ::-webkit-scrollbar {width: 12px;  background-color: #F5F5F5; }
    ::-webkit-scrollbar-thumb{ -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);background-color: #555;}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Support <small>Team</small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Support</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section id="content" class="table-layout">
	  <div class="inner_content_w3_agile_info"> 
		  <span class="referral-part">
              <button type="button" id="generate-support-btn" data-toggle="modal" data-dismiss="modal"  data-target="#generateTicketModel" class="btn  btn-primary "><i class="fa fa-plus fa-lg"></i> Generate new support request</button>
      
      </span>
      </div>
    <div class="inner_content_w3_agile_info referral-part">
           <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">


               <div class="form-group">
                   <div class="col-md-2 col-sm-2 col-xs-12">
                       <?php  echo $this->Form->input('title',array('placeholder'=>'title','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>

                   </div>
                   <div class="col-md-2 col-sm-2 col-xs-12">
                       <?php  echo $this->Form->input('ticket_id',array('placeholder'=>'Ticket Id','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                   </div>
                   <div class="col-md-2 col-sm-2 col-xs-12">
                       <?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>

                   </div>
              <div class="col-md-2 col-sm-2 col-xs-12">
                       <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>

                   </div>
                   <div class="col-md-2 col-sm-2 col-xs-12">
                       <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                   </div>
                   <div class="col-md-1 col-sm-1 col-xs-12">
                       <button type="submit" class="btn btn-success">Filter</button>
                   </div>
               </div>


           </form>
						<div class="clearfix"></div>
      
      					<h3 class="w3_inner_tittle two">Listing</h3>
					<div id="main_wallet_transaction_div" class="mt10 table-responsive">
						
						   
						<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>S No.</th>
                            <th>User</th>
                            <th>Ticket Id</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Start at</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$count= 1;
						
						 foreach($listing->toArray() as $k=>$data){
						  //   pr($data);die;
							if($k%2==0) $class="odd";
							else $class="even";
						?>
                        <tr class="<?=$class?>">
                            <td> <?=$count?></td>
                            <td> <?=$data['user']['name']?></td>
                            <td> <?=$data['ticket_id']?></td>
                            <td><?=$data['title']?></td>
                            <td><?=$data['subject']['subject']?></td>
                            <td><?=$data['created']->format('d M Y');?> </td>
                            <td id="status_<?php echo $data['ticket_id']; ?>"><?=($data['status']=='C'?"Closed" : "Running");?> </td>
                            <td>
								<a data-toggle="modal" data-dismiss="modal"  data-target="#chat_div_<?php echo $k; ?>"><button class="btn btn-sm btn-primary ">view</button></a>
								<?php if($data['status']=='R'){?>
								<button id="closeButton<?=$data['ticket_id']?>"  class="btn btn-sm btn-danger closeTicket" data-id="<?php echo $data['ticket_id']; ?>">close</button>&nbsp;&nbsp;
								<?php } ?>
								
							</td>
                        </tr>
                             <div id="chat_div_<?php echo $k; ?>" class="modal fade" role="dialog">

                                 <div class="modal-dialog login_model">

                                     <!-- Modal content-->

                                     <div class="modal-content">

                                         <div class="modal-header">
                                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                                             <h4 class="modal-title">
                                                 Ticket : <?=$data['ticket_id']?></h4>
                                             <br>Title : <?=$data['title']?><br>Subject : </h4><?=$data['subject']['subject']?>
                                         </div>
                                         <div class="modal-body">

                                             <div class="panel panel-primary">

                                                 <div class="panel-body">
                                                     <ul class="chat" id="chatul_<?=$data['ticket_id']?>">
                                                         <li style="list-style: none;"></li>
                                                     <?php if(!empty($data['ticket_messages']))
                                                     {
                                                         foreach ($data['ticket_messages'] as $k=>$v){
                                                             if($data['user']['image'] != '') $image= $this->request->webroot.'uploads/user_thumb/'.$data['user']['image'];
                                                             else $image= $this->request->webroot.'user200.jpg';
                                                             //pr($v);	die;
                                                             if($v['user_type']=='A'){
                                                                 echo '<li class="right clearfix"><span class="chat-img pull-right">
												<img width="50" src="'.$image.'"  class="img-circle">
											</span>
												<div class="chat-body clearfix">
													<div class="header">
														<small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'.$this->Utility->timefunc($v['created']->format('Y-m-d H:i:s')).'</small>
														<strong class="pull-right primary-font">'.$data['user']['name'].'</strong>
													</div>
													<p class="pull-right"> '.$v['message'].'</p>
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
                                                 <?php if($data['status'] != 'C'){ ?>
                                                     <div class="panel-footer">
                                                         <div class="input-group">
                                                             <input id="msg_<?=$data['ticket_id']?>" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                                                             <span class="input-group-btn">
                                    <button class="btn btn-warning btn-sm" data-id="<?=$data['ticket_id']?>" id="btn-chat">
                                        Send</button>
                                </span>
                                                         </div>
                                                     </div>
                                                 <?php } ?>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
						<?php $count++; } ?>
						<?php  if(count($listing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>	
                        </tbody>
                    </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'Tickets', 'action' => 'search')));
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
  </section>
</div>



<script>
    $('.closeTicket').on('click',function (e) {
    var id = $(this).attr('data-id');
     //  alert(id);
	if(confirm("Do you want to close this ticket?"))
    {
        $.ajax({
            type : 'POST',
            url : '<?php echo $this->Url->build(['controller'=>'tickets','action'=>'updateStatus']); ?>',
            dataType : 'JSON',
            data :{ticket_id:id},
            success: function(response){
                if(response=='ok')
                {
                    new PNotify({
                        title: 'Success',
                        text: 'Ticket status changed to closed!',
                        type: 'success',
                        styling: 'bootstrap3',
                        delay:1200
                    });
                    $('#status_'+id).text('Closed');
                    $('#closeButton'+id).remove();
                }
                else {
                        new PNotify({
                            title: '403 Error',
                            text: response,
                            type: 'error',
                            styling: 'bootstrap3',
                            delay:1200
                        });
                }

            }
        });
       }
      
    });
	$('document').ready(function(){
		$( "#confirm_btn" ).click(function( event ) {
			event.preventDefault();
			var subject = $("#subject-id").val();
			var title = $(".modal-body #title").val();
			var message = $("#message").val();
			var media = $("#media").val().replace(/C:\\fakepath\\/i, '');
			if(subject != '' && title != '' && message != '' )
			{
				$(".overlay-contact").show();
				$.ajax({
					url: '<?php echo $this->Url->build(['controller'=>'tickets','action'=>'addTickets']); ?>',
					dataType: "JSON",
					data: {subject_id: subject, title:title,message:message,media:media},
					type: "POST",
					success: function(output)
					{
						$("#contact_message").html(output.string);
						setTimeout(location.reload.bind(location), 2000);
					}
				});
			}
			else{
				$("#contact_message").html('<div class="alert-danger"><strong>Error! </strong>Please fill the details.</div>');
			}
		});
	});
    $('#btn-chat').click(function () {
        var ticket_id = $(this).attr('data-id');
        var val = $('#msg_'+ticket_id).val();
        $.ajax({
            type: 'post',
            url: '<?php echo $this->Url->build(['controller'=>'Tickets','action'=>'updateMessage']);  ?>',
            data: {
                'message':val,'user_id':<?php echo $authUser['id'];?>,'ticket_id':ticket_id,'user_type':'U',
            },
            dataType : 'JSON',
            success: function (response) {

                if(response.st=='OK')
                {
                    console.log('ul#chatul_'+ticket_id);
                    $('ul#chatul_'+ticket_id).append(response.msg);
                    $('#msg_'+ticket_id).val('');
                }
            }
        });
    });

</script>
<script>
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

</script>
