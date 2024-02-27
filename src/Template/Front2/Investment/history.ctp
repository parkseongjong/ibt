<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
<style>

    .a_table_center{ width: 100%; float:none; max-height: 10%;}
    .a_table_center table{ max-height: 200px;   width: 100%;  text-align:center;     border-color: #d3ccea;
        border: 1px solid #d3ccea;}
    .a_table_center table thead{background: #6738ff; color:#fff;display: table; /* to take the same width as tr */
        width: 100%; /* - 17px because of the scrollbar width */}
    .a_table_center table tbody {
        display: block; /* to enable vertical scrolling */
         /* e.g. */
        max-height: 500px;
        overflow-y: scroll; /* keeps the scrollbar even if it doesn't need it; display purpose */
    }
    .a_table_center table th{  width: 11%; 
        padding: 10px;
        }
 .a_table_center table td{ 
        padding: 10px;
        }
	.a_table_center table th:nth-child(1), .a_table_center table th:nth-child(2), .a_table_center table th:nth-child(3) ,.a_table_center table th:nth-child(4),.a_table_center table th:nth-child(5),.a_table_center table th:nth-child(6),.a_table_center table th:nth-child(7){    width: 11%;}
	.a_table_center table td:nth-child(1), .a_table_center table td:nth-child(2), .a_table_center table td:nth-child(3) ,.a_table_center table td:nth-child(4),.a_table_center table td:nth-child(5),.a_table_center table td:nth-child(6),.a_table_center table td:nth-child(7){    width: 10%;}
    .a_table_center tr{																																																														  
        display: table; /* display purpose; th's border */
        width: 100%;
        box-sizing: border-box
    }
    .a_table_center table tr:nth-of-type(odd) {
        background-color: rgb(211 204 234/0.12);
    }
/*.a_table_center table thead:after {
    content: "";
    background: #744afc;
    width: 23px;
    height: 91%;
    position: absolute;
    right: -18px;
    z-index: 9;
    top: 0px;
    border-top: 1.52px solid #d3ccea;
    border-bottom: 1.52px solid #878396;
}*/
@media only screen and (max-width: 990px) {
	.a_table_center{    width: 100%; height:10%;}
	.tab_menu li {    width: 49.5%!important;}
	.a_table_center table th:nth-child(1),.a_table_center table td:nth-child(1), .a_table_center table td:nth-child(2), .a_table_center table th:nth-child(2),.a_table_center table th:nth-child(3), .a_table_center table td:nth-child(3), .a_table_center table th:nth-child(4), .a_table_center table td:nth-child(4) , .a_table_center table th:nth-child(5), .a_table_center table td:nth-child(5), .a_table_center table th:nth-child(6), .a_table_center table td:nth-child(6), .a_table_center table th:nth-child(7), .a_table_center table td:nth-child(7){  
		width: 11%;
		word-break: break-all;}
    }
	.application .myBtn button{
		width: 48%;
		height: 50px;
		font-size: 20px;
		font-weight: 600;
		line-height: 2.1;
		color: #6738ff;
		border-radius: 5px;
		border: solid 1px #6738ff;
		background-color: #ffffff;
		cursor: pointer;
	}

	.application .myBtn button:hover:enabled {
		background-color: #6738ff;
		color: white;
	}

	.application .myBtn button:active:enabled {
		background-color: white;
		color: black;
		border: 2px solid #6738ff
	}

	.application .myBtn button:disabled{
		background-color: #d3ccea;
		color: white;
		border: 0;
	}
	
</style>
<style>
    .modal-open {
        overflow: hidden
    }
    .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0
    }
    .modal.fade .modal-dialog {
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        -o-transform: translate(0, -25%);
        transform: translate(0, -25%);
        -webkit-transition: -webkit-transform .3s ease-out;
        -o-transition: -o-transform .3s ease-out;
        transition: -webkit-transform .3s ease-out;
        transition: transform .3s ease-out;
        transition: transform .3s ease-out, -webkit-transform .3s ease-out, -o-transform .3s ease-out
    }

    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0)
    }

    .modal-open .modal {
        overflow-x: hidden;
        overflow-y: auto
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 10px
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        background-clip: padding-box;

        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 6px;
        -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        outline: 0
    }

    .modal-content .service_contents td div input {
        box-sizing: border-box;
        height: 100%;
        font-size: 20px;
        padding: 12px 20px;
        margin: 10px 20px;
        display: inline-block;
        border: 1px solid #ccc;

    }



    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        background-color: #000
    }

    .modal-backdrop.fade {
        filter: alpha(opacity=0);
        opacity: 0
    }

    .modal-backdrop.in {
        filter: alpha(opacity=50);
        opacity: .5
    }

    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5
    }

    .modal-header .close {
        margin-top: 2px;
        float: right;
    }

    .modal-title {
        margin: 0;
        line-height: 1.42857143;
        font-size: large;
        font-weight: bold;
    }

    .modal-body {
        position: relative;
        padding: 10px;
        align-content: center;
        align-items: end;
        text-align: center;
    }

    .modal-footer {
        padding: 15px;
        text-align: center;
        border-top: 1px solid #e5e5e5
    }

    .modal-footer .myBtn button{
        width: 30%;
        height: 40px;
        font-size: 18px;
        font-weight: 600;
        line-height: 2.1;
        color: #6738ff;
        border-radius: 5px;
        border: solid 1px #6738ff;
        background-color: #ffffff;
        cursor: pointer;
        alignment: center;
    }

    .modal-footer .myBtn button:hover:enabled {
        background-color: #6738ff;
        color: white;
    }

    .modal-footer .myBtn button:active:enabled {
        background-color: white;
        color: black;
        border: 2px solid #6738ff
    }

    .application .myBtn button:disabled{
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
	.application .myBtn #authBtn {
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    /*.modal-footer .btn-group .btn+.btn {*/
    /*    margin-left: -1px*/
    /*}*/

    /*.modal-footer .btn-block+.btn-block {*/
    /*    margin-left: 0*/
    /*}*/

    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    @media (min-width:768px) {
        .modal-dialog {
            width: 50%;
            margin: 30px auto
        }
		.modal-dialog-auth {
			width: 800px;
		}
        .modal-content {
            -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
        }
        .modal-sm {
            width: 300px
        }
    }

    @media (min-width:992px) {
        .modal-lg {
            width: 900px
        }
    }
</style>
<div class="container">
    <div class="profile_box">
        <?php echo $this->element('Front2/investment_menu'); ?>
        <div class="service_info application">
		<?php echo $this->Form->create(null,array('method'=>'post')); ?>
		<?php echo $this->Form->end(); ?>
			<div class="a_table_center" style="margin-bottom:30px;">
                <table class="list" border="1">
                    <thead style="position: sticky">
						<tr>
							<th>수익 합계</th>
                            <th>종목</th>
						</tr>
                    </thead>
					<tbody id="">
						<tr>
							<td><?= !empty($my_profits->amount) ? number_format($my_profits->amount) : 0 ;?></td>
							<td>KRW</td>
						</tr>
						<input type="hidden" id="amount" value="<?= !empty($my_profits->amount) ? $my_profits->amount : 0 ;?>" >
					</tbody>
                </table>
            </div>
            <div class="a_table_center">
                <table class="list" border="1">
                    <thead style="position: sticky">
						<tr>
							<!--<th><?=__('Previous Balance')?></th> -->
							<th><?=__('Application quantity')?></th>
							<th><?=__('Service usage period')?></th>
                            <th><?=__('Status')?></th>
							<!--<th><?=__('Application Date')?></th>
							<th><?=__('Approval Date')?></th>-->
							<th><?=__('Application Date')?></th>
							<th>수익</th>
						</tr>
                    </thead>
					<tbody id="depositHistoryList">
						<tr>
							<td colspan="8"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
						</tr>
					</tbody>
                </table>
            </div>
			<div><p style="color:red;display:none;" id="error_text">10,000 KRW 이상부터 출금이 가능합니다.</p></div>
			<div class="myBtn" style="margin-top:30px;">
				<button id="btn" type="button" class="button" onclick="openDialogs()" data-toggle="modal" data-target="myModal" >수익금 출금</button>
				<button id="" type="button" class="button" onclick="open_log_modal()" data-toggle="modal" data-target="my_profits_log">수익 기록</button>
			</div>
        </div>
     </div>
</div>
<div class="modal fade" id="myModal" role="dialog" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="modalLabel">인출하기</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="a_table_left">
					수익금은 전체 인출만 가능합니다.<br>
					<?= !empty($my_profits->amount) ? number_format($my_profits->amount) : 0 ;?> 원을 메인 계정으로 인출하시겠습니까?
					
					<!--
                    <table class="service_contents">
                        <tbody>
							<tr>
								<td><div><input type="text" id="withdraw_value" name="withdraw_value" value="" placeholder="인출금액을 입력해주세요" style="width:100%" onkeydown="only_number(this)"></div></td>
							</tr>
                        </tbody>
                    </table>
					-->
					<div><p style="color:red;display:none;" id="error_text2">10,000 KRW 이상부터 출금이 가능합니다.</p></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="myBtn">
					<button type="button" id="submit_btn" name="submit_btn" class="button" onclick="withdraw()"><?=__('Withdrawal')?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="my_profits_log" role="dialog" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="modalLabel">수익 로그</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <iframe src="/front2/investment/mywalletlog"  width="100%" height="500px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    function depositHistory() {
        $.ajax({
            url : '/front2/investment/depositHistory',
            type : 'get',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            dataType : 'json',
            success : function(resp){
                var html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan='8'><?= __('No deposit found') ?></td>";
                    html = html + '</tr>';
                }
                else {
                    $.each(resp,function(key,value){
						var tp3Balance = value.previous_balance != null ? (value.previous_balance).toFixed(4) : 0 ;
                        var quantity = numberWithCommas(value.quantity);
                        var servicePeriod = value.service_period_month + " <?= __('days')?>";
                        var created = value.created.split("+")[0].replace("T"," ");
						var approval_date = value.approval_date != null ? value.approval_date.split("+")[0].replace("T"," ") : '' ;
						var cancelled_date = value.cancelled_date != null ? value.cancelled_date.split("+")[0].replace("T"," ") : '' ;
                        var status = value.status;
						var amount_received = value.amount_received != null ? numberWithCommas(value.amount_received) : 0;
                        var type = value.unit;

                        html = html + '<tr>';
                        //html = html + '<td class="right">'+numberWithCommas(parseFloat(tp3Balance).toFixed(2))+'</td>';
                        html = html + '<td class="left">'+quantity + type +'</td>';
                        html = html + '<td>'+servicePeriod+'</td>';
                        if(status == 'P'){
                            html = html + '<td style="color:orange;">'+'<?= __("Pending2"); ?>'+'</td>';
                        } else if (status == 'C'){
                            html = html + '<td style="color:red;">'+'<?= __("Cancelled"); ?>'+'</td>';
                        } else if (status == 'A'){
                            html = html + '<td style="color:green;">'+'<?= __("Approved"); ?>'+'</td>';
                        } else {
                            html = html + '<td>'+'<?= __(''); ?>'+'</td>';
                        }
                        html = html + '<td class="left">'+created+'</td>';
						//html = html + '<td class="left">'+approval_date+'</td>';
						//html = html + '<td class="left">'+cancelled_date+'</td>';
						html = html + '<td class="left">'+amount_received+' KRW</td>';
                        html = html + '</tr>';
                    });
                }
                $("#depositHistoryList").html(html);
            }
        });
    }

    $(document).ready(function(){
        depositHistory();
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

	function openDialogs(){
		//modal_alert('알림','서비스 점검중입니다.');
		//return
		if($('#amount').val() < 10000){
			$('#error_text').show();
			return;
		}
		$('#error_text').hide();
		$("#myModal").modal('show');
    }

	function withdraw(){
		//modal_alert('알림','서비스 점검중입니다.');
		//return
		if($('#amount').val() < 10000){
			$('#error_text2').show();
			return;
		}
		$('#error_text2').hide();
		$.ajax({
			beforeSend: function(xhr){
				$('#submit_btn').hide();
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type: 'post',
			url: '/front2/investment/withdraw',
			data: {
				//"withdraw_value" : $('#withdraw_value').val(),
			},
			dataType : "json",
			success:function(resp) {
				if(resp.status == 'fail'){
					$('#error_text2').html(resp.message).show();
					$('#submit_btn').show();
				} else if(resp.status == 'success'){
					location.reload();
				}
			}, 
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
		

	}
	function only_number(obj) {
		$(obj).keyup(function(){
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
	}
	function open_log_modal(){
		$("#my_profits_log").modal('show');
	}
</script>