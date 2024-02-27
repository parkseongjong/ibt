
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawallistold']);  ?>">Users Deposit Amount Old List </a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawallistold']);  ?>">Users Deposit Amount Old List </a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">
                        <div class="form-group">
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_id',array('empty'=>'Select Record Number','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$userFindList,'id'=>"search_users")); ?>
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </form>

                    <div id="transferList" class="table-responsive">
                        <?= $this->Flash->render() ?>
                        <table class="two-axis table" id="searchData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('User ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Annual Member')?></th>
                                <th style="color:#fff"><?= __('Currency')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>

                            </tr>
                            <thead>
                            <tbody>
                            <tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;">
                                <td colspan="2">&nbsp;</td>
                                <td >Please Select User</td>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div id="transferHistory" class="mt10 table-responsive">

                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('#')?></th>
                                <th style="color:#fff"><?= __('User ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Annual Member')?></th>
                                <th style="color:#fff"><?= __('Currency')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>
                                <th style="color:#fff"><?= __('Status')?></th>


                            </tr>

                            <thead>
                            <tbody id="transferHistoryList">

                            <?php
                            $count= 1;

                            foreach($listing->toArray() as $k=>$data){

                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>">
                                <tr class="<?=$class?>">
                                    <td> <?php echo $data['id']; ?></td>
                                    <td> <?php echo $data['user_id']; ?></td>
                                    <td> <?php echo $data['user']['name']; ?></td>
                                    <td> <?php echo $data['user']['phone_number']; ?></td>
                                    <td> <?php if($data['user']['annual_membership'] == 'Y'){
                                            echo "✔";
                                        } else {
                                            echo "✗";
                                        } ?></td>
                                    <td> <?php echo $data['cryptocoin']['short_name']; ?></td>
                                    <td><?php echo number_format((float)$data['coin_amount'],2);?> </td>

                                    <td><?=$data['created']->format('d M Y H:i:s');?> </td>
                                    <td class=" ">
                                        <input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['status']; ?>" />
                                        <a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?php echo $data['id'] ?>)">
                                            <?php  if($data['status'] == 'completed'){
                                                echo '<button type="button" class="btn btn-success btn-xs">Completed</button>';
                                            }else{
                                                echo '<button type="button" class="btn btn-danger btn-xs">Pending</button>';
                                            } ?></a>
                                    </td>
                                </tr>
                                </tr>
                                <?php $count++; } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        <?php $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'withdrawallistpaginationold')));
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

    </section>
</div>


<script>

    function getUserInfo(id){
        $("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td><td colspan="2">&nbsp;</td></tr>');
        $.ajax({
            url: "<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawallistajaxold']); ?>/",
            type:'POST',
            data: {id:id},
            dataType: 'JSON',
            success: function(resp) {
                if (resp.success === "false") {

                } else {
                   // alert("name: "+resp.data.user.name);
                    var getData = resp.data;
                    var html = '';
                    html = html + '<tr>';
                    html = html + '<td>' + getData.id + '</td>';
                    html = html + '<td>' + getData.user_id + '</td>';
                    html = html + '<td>' + getData.user.name + '</td>';
                    html = html + '<td>' + getData.user.phone_number + '</td>';
                    html = html + '<td>' + getData.user.annual_membership + '</td>';
                    html = html + '<td>' + getData.cryptocoin.short_name + '</td>';
                    html = html + '<td>' + numberWithCommas(getData.coin_amount) + '</td>';
                    var splitDateTime = getData.created;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T"," ");
                    html = html + '<td>' + getdateTime + '</td>';
                    html = html + '</tr>';

                    $('tbody').html(html);
                    $("#transferHistory").hide();
                }
            },
            error: function (e) {

                $("#ajax_coin_tr").hide();
                //$("#model_qr_code_flat").hide();
            }
        });
    }


    $('document').ready(function(){
        $("#transferList").hide();
        $("#search_users").select2();
        $("#search_users").change(function(){
            var getUserId = $(this).val();
            if(getUserId ==""){
                $("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td >Please Select Record Number </td><td colspan="2">&nbsp;</td></tr>');
                $("#user_id").val("");
                return false;
            }
            $("#user_id").val(getUserId);
            getUserInfo(getUserId);
            $("#transferList").show();
            $("#transferHistory").hide();
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
	});


    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }


    function change_user_status(id){
        var status = $("#user_status_"+id).val();
        if(status == 'completed'){
            var ques= "Do you want change the status to Pending";
            var status = "pending";
            var change = '<button type="button" class="btn btn-danger btn-xs">Pending</button>'
        }else{
            var ques= "Do you want change the status to Completed";
            var status = "completed";
            var change = '<button type="button" class="btn btn-success btn-xs">Completed</button>';
        }


        bootbox.confirm(ques, function(result) {
            if(result == true){
                jQuery.ajax({
                    url: '<?php echo $this->Url->build(['controller'=>'transactions','action'=>'status']); ?>',
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
                                text: 'You do not have permission to access this action.',
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
