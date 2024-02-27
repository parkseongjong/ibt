<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    .input-area {
        margin:30px auto;
    }
    .input-area input{
        width:45%;
        margin-left: 8px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'feecalculator']);  ?>"><?=__("Investment Profits Setting");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'feecalculator']);  ?>"><?=__("Investment Profits Setting");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <?php echo $this->element('Admin/deposit_application_menu2');?>
                    <?php echo $this->Form->create(null,array('method'=>'post','id'=>'frm')); ?>
                    <div id="" class="mt20 table-responsive" >
                        <a href="javascript:void(0)" class="btn btn-danger m-b-20" onclick="fee_setting('amount')" ><?=__("Amount");?>/<?=__("Fee");?> <?=__("Settings");?></a>
                        <a href="javascript:void(0)" class="btn btn-danger m-b-20" onclick="fee_setting('period')" ><?=__("Period");?>/<?=__("Fee");?> <?=__("Settings");?></a>
                        <a href="javascript:void(0)" class="btn btn-danger m-b-20" onclick="fee_setting('stage')" > <?=__("Stage");?> <?=__("Settings");?></a>
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea; font-size: 16px;">
                            <tr>
                                <th style="color:#fff"><?=__("Select Stage");?></th>
                                <th style="color:#fff"><?=__("days");?></th>
                                <th style="color:#fff"><?=__("Earned Data (Amount)");?></th>
                                <th style="color:#fff"><?=__("Number of people to be counted");?></th>
                            </tr>
                            </thead>
                            <tbody id="transferHistoryList">
                            <tr>
                                <td>
                                    <select id="investment_number" name="investment_number" class="form-control">
                                        <option value=""><?=__("Select Stage");?></option>
                                        <?php
                                        foreach($stage_list as $sl){
                                            ?>
                                            <option value="<?=$sl->stage;?>"><?=$sl->stage;?><?=__("Stage");?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" id="days" name="days" value="" placeholder="<?=__("Cycle")?>" class="form-control"></td>
                                <td><input type="text" id="earned_data" name="earned_data" value="" placeholder="<?=__("Earned Data (Amount)")?>" class="form-control"></td>
                                <td><input type="text" id="count_of_people" name="count_of_people" value="" placeholder="<?=__("the number of people")?>" class="form-control"></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="text-align: center;">
                            <button type="button" class="btn btn-info" onclick="submit_chk()"><?=__("Add");?></button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="img_area">
            <table class="table">
                <thead>
                <tr>
                    <th id="type_th"><?=__("Amount");?></th>
                    <th><?=__("Fee");?></th>
                    <th><?=__("Delete");?></th>
                </tr>
                </thead>
                <tbody id="modal_tbody">
                <tr>
                    <th>1</th>
                    <th>0.12</th>
                </tr>
                </tbody>
            </table>
            <div class="input-area">
                <input type="hidden" id="type" name="type" value="">
                <input type="text" id="contents_value" name="contents_value" value="" class="" placeholder="">
                <input type="text" id="fee" name="fee" value="" class="">
            </div>
            <div class="btn-area">
                <button type="button" id="" onclick="add_fee_setting()" class="btn btn-primary"><?=__("Add");?></button>
            </div>
        </div>
    </div>
</div>
<div id="myModalStage" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="">
            <table class="table">
                <thead>
                <tr>
                    <th><?=__("Stage");?></th>
                    <th><?=__("Status");?></th>
                    <th><?=__("Created");?></th>
                    <th>코인명</th>
                    <th><?=__("Run");?></th>
                </tr>
                </thead>
                <tbody id="modal_stage_tbody">
                <tr>
                    <th>1</th>
                    <th>0.12</th>
                    <th>0.12</th>
                </tr>
                </tbody>
            </table>
            <div class="input-area">
                <?=__('Stage');?>
                <select id="stage" name="stage" style="height: 33px;margin : 5px;">

                </select>
                <select id="stage_coin" name="stage_coin" style="height: 33px;margin : 5px;">
                    <option value="TP3">TP3</option>
                    <option value="MC">MC</option>
                </select>
                <button type="button" id="" onclick="add_stage()" class="btn btn-primary"><?=__("Add");?></button>
            </div>
            <div class="btn-area">
                <!--<button type="button" id="" onclick="add_stage()" class="btn btn-primary">추가하기</button>-->
            </div>
        </div>
    </div>
</div>
<script>
    /* modal popup */
    var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    var span2 = document.getElementsByClassName("close")[1];
    var modal_stage = document.getElementById('myModalStage');
    span.onclick = function() {
        modal.style.display = "none";
        modal_stage.style.display = "none";
    }
    span2.onclick = function() {
        modal.style.display = "none";
        modal_stage.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == modal_stage) {
            modal_stage.style.display = "none";
        }
    }

    function submit_chk(){
        if($('#investment_number').val() == ''){
            alert('<?=__("Please select an investment stage");?>');
            return;
        }
        if($('#days').val() == ''){
            alert('<?=__("Please enter the period to be paid");?>');
            $('#days').focus();
            return;
        }
        if($('#earned_data').val() == ''){
            alert('<?=__("Please enter the accumulated data to be calculated");?>');
            $('#earned_data').focus();
            return;
        }
        if($('#count_of_people').val() == ''){
            alert('<?=__("Please enter the number of people to count");?>');
            $('#count_of_people').focus();
            return;
        }
        if(confirm('<?=__("Do you want to enter data?");?>')){
            calc();
        }
    }

    function calc(){
        $('#frm').submit();
    }

    function manual_payment(){
        if(confirm('<?=__("Do you want to pay manually?");?>')){
            $.ajax({
                type: 'post',
                url: '/tech/deposit-application/callajaxcalc',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {
                },
                dataType : "json",
                success:function(resp) {
                    //console.log(resp);
                    if(resp.success == 'false'){
                        alert(resp.message);
                    } else if(resp.success == 'true'){
                        location.reload();
                    }
                },
                error:function(request,status,error){
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    }
    function cancel(id){
        if(confirm('<?=__("Are you sure you want to cancel?");?>')){
            $.ajax({
                type: 'post',
                url: '/tech/deposit-application/cancelsetting',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {
                    "id" : id,
                },
                dataType : "json",
                success:function(resp) {
                    if(resp.success == 'false'){
                        alert(resp.message);
                    } else if(resp.success == 'true'){
                        location.reload();
                    }
                },
                error:function(request,status,error){
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    }
    function fee_setting(type){
        $('#type').val(type);
        $.ajax({
            type: 'post',
            url: '/tech/deposit-application/getAmountPeriodList',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data: {
                "type" : type,
            },
            dataType : "json",
            success:function(resp) {
                var html = '';
                if(type == 'stage'){
                    $('#stage').empty();
                    var lastElement = 1;
                    $.each(resp,function(key,value){
                        var isLastElement = key == resp.length -1;
                        if(isLastElement){
                            lastElement = value.stage+1;
                        }
                        var id = value.id;
                        var stage = '';
                        var status = '';
                        var btn = '';
                        
                        if(value.stage == 0){ stage = '<?=__("Stop Stage");?>'; }
                        else { stage = value.stage + '<?=__("Stage");?>';}
                        if(value.status == 'Y'){
                            status = '<?=__("Ongoing");?>';
                            btn = '<?=__("Stop");?>';
                        } else if(value.status == 'N') {
                            status = '<?=__("Stand By");?>';
                            btn = '<?=__("Proceed");?>';
                        }
                        var created = value.created != null ? value.created.split("+")[0].replace("T"," ") : '' ;
                        var coin = value.coin_name;

                        html += '<tr>';
                        html += '<td>'+stage+'</td>';
                        html += '<td>'+status+'</td>';
                        html += '<td>'+created+'</td>';
                        html += '<td>'+coin+'</td>';
                        html += '<td><button type="button" class="btn btn-xs btn-warning" onclick="stage_change('+id+',\''+value.status+'\')">'+btn+'</button></td>';
                        html += '</tr>';
                    });
                    for(var i = lastElement; i < lastElement+5; i++){
                        $('#stage').append($('<option>', {
                            value: [i],
                            text : [i]+'<?=__("Stage");?>'
                        }));
                    }
                    $('#modal_stage_tbody').html(html);
                    $('#myModalStage').css('display','block');
                } else if(type == 'amount' || type == 'period') {
                    $('#type_th').text(type);
                    $.each(resp,function(key,value){
                        var id = value.id;
                        if(type == 'amount'){
                            var contents = numberWithCommas(value.amount) + ' <?=__("WON");?>';
                        } else if (type == 'period'){
                            var contents = value.period + ' <?=__("days");?>';
                        }
                        var fee = value.fee;

                        html += '<tr>';
                        html += '<td>'+contents+'</td>';
                        html += '<td>'+fee+'%</td>';
                        html += '<td><button type="button" class="btn btn-xs btn-warning" onclick="delete_setting('+id+')"><?=__("Delete");?></button></td>';
                        html += '</tr>';
                    });
                    $('#modal_tbody').html(html);
                    $('#myModal').css('display','block');
                }
            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function add_fee_setting(){
        if($('#type').val() == ''){
            alert('<?=__("Please refresh and try again");?>');
            return;
        }
        if($('#contents_value').val() == ''){
            alert('<?=__("Please enter a value");?>');
            $('#contents_value').focus();
            return
        }
        if($('#fee').val() == ''){
            alert('<?=__("Please enter a fee");?>');
            $('#fee').focus();
            return;
        }

        $.ajax({
            type: 'post',
            url: '/tech/deposit-application/addFeeSetting',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            data: {
                "type" : $('#type').val(),
                "contents_value" : $('#contents_value').val(),
                "fee" : $('#fee').val(),
            },
            //dataType : "json",
            success:function(resp) {
                $('#contents_value').val('');
                $('#fee').val('');
                fee_setting($('#type').val());
            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });


    }
    function delete_setting(id){
        if($('#type').val() == ''){
            alert('<?=__("Please refresh and try again");?>');
            return;
        }
        if(confirm('<?=__("Are you sure that, you want to delete this?");?>')){
            $.ajax({
                type: 'post',
                url: '/tech/deposit-application/deleteFeeSetting',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {
                    "type" : $('#type').val(),
                    "id" : id,
                },
                //dataType : "json",
                success:function(resp) {
                    fee_setting($('#type').val());
                },
                error:function(request,status,error){
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    }
    function stage_change(id,status){
        if(confirm('<?=__("Do you really want to change?");?>')){
            $.ajax({
                type: 'post',
                url: '/tech/deposit-application/stagechange',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {
                    "id" : id,
                    "status" : status
                },
                //dataType : "json",
                success:function(resp) {
                    fee_setting('stage');
                },
                error:function(request,status,error){
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    }

    function add_stage(){
        if(confirm('<?=__("Do you really want to add?");?>')){
            $.ajax({
                type: 'post',
                url: '/tech/deposit-application/addstage',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {
                    "stage" : $('#stage').val(),
                    "stage_coin" : $('#stage_coin').val(),
                },
                //dataType : "json",
                success:function(resp) {
                    fee_setting('stage');
                },
                error:function(request,status,error){
                    console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    }
    function get_inumber_list(value){
        $('#search_frm').submit();
    }
</script>
