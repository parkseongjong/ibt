<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr>
        <th>S No.</th>
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
    $count= $serial_num;
    foreach($listing->toArray() as $k=>$data){
        //pr($data);die;
        if($k%2==0) $class="odd";
        else $class="even";
        ?>
        <tr class="<?=$class?>">
            <td> <?=$count?></td>
            <td> <?=$data['ticket_id']?></td>
            <td><?=$data['title']?></td>
            <td><?=$data['subject']['subject']?></td>
            <td><?=$data['created']->format('d M Y');?> </td>
            <td id="status_<?php echo $data['ticket_id']; ?>"><?=($data['status']=='C'?"Closed" : "Running");?> </td>
            <td>
                <a data-toggle="modal" data-dismiss="modal"  data-target="#chat_div_<?php echo $k; ?>"><button class="btn  btn-primary ">view</button></a>
                <?php if($data['status']=='R'){?>
                    <button id="closeButton<?=$data['ticket_id']?>"  class="btn btn-danger closeTicket" data-id="<?php echo $data['ticket_id']; ?>">close</button>&nbsp;&nbsp;
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
                                            if($v['user_type']=='U'){
                                                echo '<li class="right clearfix"><span class="chat-img pull-right">
                                                        <img width="50" src="'.$image.'"  class="img-circle">
                                                    </span>
                                                        <div class="chat-body clearfix">
                                                            <div class="header">
                                                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'.$this->Utility->timefunc($v['created']->format('Y-m-d H:i:s')).'</small>
                                                                <strong class="pull-right primary-font">'.$data['user']['name'].'</strong>
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
                                                                <strong class="primary-font">'.$data['user']['name'].'</strong> <small class="pull-right text-muted">
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
<script>
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
</script>
