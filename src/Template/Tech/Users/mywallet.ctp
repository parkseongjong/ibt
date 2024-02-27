<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mywallet']);  ?>"><?= __('Users Wallet');?> </a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?> </a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mywallet']);  ?>"><?= __('Users Wallet');?> </a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
		<div class="inner_content_w3_agile_info">
            <div class="agile-tables">
				<div class="w3l-table-info agile_info_shadow">
				<div class="clearfix"></div>
					<form method="get" id="frm" class="form-horizontal form-label-left input_mask">
						<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_id',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
							<!--                         <div class="col-md-1 col-sm-1 col-xs-12">-->
							<!--                          <button type="submit" class="btn btn-success" onclick="showList();">Filter</button>-->
							<!--                        </div>-->
						</div>
					</form>
					<div id="transferList" class="table-responsive">
					<?= $this->Flash->render() ?>
						 <table class="two-axis table" id="searchData">
							<thead style="background: #d3ccea;    font-size: 16px;">
							<tr >
								<th style="color:#fff"><?= __('Coin Name')?></th>
								<th style="color:#fff"><?= __('Trading Account')?></th>
								<th style="color:#fff"><?= __('Reserved')?></th>
								<th style="color:#fff"><?= __('Transfer')?></th>
								<th style="color:#fff"><?= __('Main Account')?></th>
							</tr>
							<thead>
							<tbody>
							<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;">
								<td colspan="2">&nbsp;</td>
								<td ><?= __('Please select user');?></td>
								<td colspan="2">&nbsp;</td>
							</tr>
								<?php
								
								/* foreach($getCoinList as $singleCoin) { 
								
								$actionPage = 'withdrawal' ;
								$getPrice = $this->Custom->getBalance($singleCoin['id'],$currentUserId);
								$totalBalance = $getPrice['withdrawBalance']+$getPrice['pendingBalance']+abs($getPrice['reserveBalance']); */
								?>
									<!--<tr>
										<td><?php //echo '<strong>'.$singleCoin['short_name']."</strong> ".$singleCoin['name']; ?></td>
										<td><?php //echo number_format((float)$getPrice['principalBalance'],8); ?></td>
										<td>
											<span style="cursor:pointer;" onClick="transferAmount('<?php //echo $singleCoin['short_name'] ?>','<?php //echo $singleCoin['id'] ?>','trading')" class="fa fa-arrow-right"></span> 
											<br/>
											<span style="cursor:pointer;" onClick="transferAmount('<?php //echo $singleCoin['short_name'] ?>','<?php //echo $singleCoin['id'] ?>','main')" class="fa fa-arrow-left" ></span></td>
										<td><?php //echo number_format((float)$getPrice['withdrawBalance'],8) ?></td>
										<td><?php //echo number_format((float)abs($getPrice['reserveBalance']),8) ?></td>
										
									</tr>-->
								
								<?php //} ?>
							
								</tbody>
							</table>
                          </div>
                    <div id="transferHistory" class="mt10 table-responsive">
                        <?= $this->Flash->render() ?>
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
                            <tr >
                                <th style="color:#fff"><?= __('User ID')?></th>
                                <th style="color:#fff"><?= __('Name')?></th>
                                <th style="color:#fff"><?= __('Phone Number')?></th>
                                <th style="color:#fff"><?= __('Annual Member')?></th>
                                <th style="color:#fff"><?= __('Coin')?></th>
                                <th style="color:#fff"><?= __('Amount')?></th>
                                <th style="color:#fff"><?= __('Transaction Type')?></th>
                                <th style="color:#fff"><?= __('Date & Time')?></th>

                            </tr>
                            <thead>
                            <tbody id="transferHistoryList">
                            <!-- <tr id="ajax_history_tr" style="vertical-align: center;alignment: center;">
                                <td colspan="6" class="blank">
                                    <?//=__('No transaction details') ?>
                                    <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" />
                                </td>
                            </tr> -->
                            <?php
                            $count= 1;

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['user_id'], 1, '고객 지갑 로그 조회 (이름, 전화번호)');
                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>">
                                    <td><?= $data['user_id']; ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
                                    <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
                                    <td><?php if($data['user']['annual_membership'] == 'Y'){echo "✔";} else { echo "✗"; } ?></td>
                                    <td><?= $data['cryptocoin']['short_name']; ?></td>
                                    <td><?= number_format((float)abs($data['amount']),2);?></td>
                                    <td><?php
                                        $transType = $data['type'];
                                        if($transType == 'transfer_to_trading_account'){
											echo __('Main Account → Trading Account');
										}
										if($transType == 'transfer_from_trading_account'){
											echo __('Trading Account → Main Account');
										} ?>
                                    </td>
                                    <td><?=$data['created_at']->format('Y-m-d H:i:s');?> </td>
                                </tr>
                                <?php $count++; } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        <?php $this->Paginator->options(array('url' => array('controller' => 'users', 'action' => 'mywallet')));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__("First"));
                        if($paginator->hasPrev()){
                            //echo $paginator->prev(__("Prev"));
                        }
                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 9));
                        // for the 'next' button
                        if($paginator->hasNext()){
                            //echo $paginator->next(__("Next"));
                        }
                        // the 'last' page button
                        echo $paginator->last(__("Last"));
                        echo "</div>";

                        ?>
                    </div>
		</div>
		</div>
		</div>

	</section>
	</div>
		


  <!-- Modal -->
<div id="myModalDeposit" class="modal fade" role="dialog" >
  <div class="modal-dialog" style='color:#000;' >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="modal_coin_name"></span> <?= __('Transfer to')?> <span id="wallet_name"> </span> <?= __('account') ?></h4>
      </div>
      <div class="modal-body" style="text-align:center;">
		<form action="#" autocomplete="off" id="deposit_modal_form" enctype="multipart/form-data">
		<input type="hidden" class="form-control" id="user_id" name="user_id">
		<input type="hidden" class="form-control" id="coin_id" name="coin_id">
		<input type="hidden" class="form-control" id="transfer_to" name="transfer_to">
		
		
		<div class="form-group">
		  <label for="email"><?= __('Amount: ')?></label>
		  <input type="text" class="form-control" required placeholder="<?= __('Enter Amount')?>" name="amount" id="amounts">
		</div>
		

		<button type="submit" class="btn btn-default" id="btnSubmit"><?= __('Submit')?></button>
		<img id="model_qr_code_flat" style="display:none;" src="/ajax-loader.gif" />
		 <div id="get_resp" style="display:none;"></div>
		
	  </form>
      </div>
      
    </div>

  </div>
</div>


  

  <script>
  function transferAmount(coinName,coinId,transferTo){
	
	  $("#modal_coin_name").html(coinName);
	  $("#wallet_name").html(transferTo);
	  $("#coin_id").val(coinId);
	  $("#transfer_to").val(transferTo);
	  $('#myModalDeposit').modal('show');
	  // if(coinName === "TP3") {
      //     $("#amounts").on('change', function (e) {
      //         if ($(this).val() < 1000 || $(this).val() > 10000 && $(this).val() !== '') {
      //             e.preventDefault();
      //             alert('Sorry! You can only transfer at least 1000 or at most 10,000 at a time');
      //             $(this).val(1000);
      //         }
      //     });
      // }
	  // if(coinName === "CTC"){
      //     $("#amounts").on('change', function (e) {
      //         if ($(this).val() < 100 || $(this).val() > 1000 && $(this).val() !== '') {
      //             e.preventDefault();
      //             alert('Sorry! You can only transfer at least 100 or at most 1000 at a time');
      //             $(this).val(100);
      //         }
      //     });
      // }
  }

  /*function transferHistory() {
      $.ajax({
          //url : '/front2/wallet/transferHistory',
          url : '<?//php echo $this->Url->Build(['controller'=>'users','action'=>'transferHistory']) ?>',
          type : 'get',
          dataType : 'json',
          success : function(resp){
              // my depositOrderList data
              var html = '';
              if($.isEmptyObject(resp)){
                  html = html + '<tr>';
                  html = html + "<td colspan=7><?//= __('There is no transaction history.')?></td>";
                  html = html + '</tr>';
              }
              else {
                  $.each(resp,function(key,value){
                      var coin = (getCoinName(value.cryptocoin_id));
                      var userId = value.user_id;
                      var username = value.user.name;
                      var phone = value.user.phone_number;
                      var annualMem = value.user.annual_membership;
                      var transAmount = value.amount;
                      if(transAmount < 0){
                          transAmount = transAmount * -1;
                      }
                      var transType = (value.type).toString().replace(/[_]/g, " ");
                      var walletAddress = value.wallet_address;
                      var fees = value.fees;
                      var splitDateTime = value.created_at;
                      var splitDateTimes = splitDateTime.split("+");
                      var getdateTime = splitDateTimes[0];
                      var newSplitTime = getdateTime.split("T");
                      // var getdateTime = getdateTime.replace("T"," ");
                      // var setColor = (value.extype=="buy") ? "blue " : "red";
                      html = html + '<tr>';
                      html = html + '<td>'+userId+'</td>';
                      html = html + '<td>'+username+'</td>';
                      html = html + '<td>'+phone+'</td>';
                      html = html + '<td>'+annualMem+'</td>';
                      html = html + '<td>'+coin+'</td>';
                      html = html + '<td class="left">'+numberWithCommas(parseFloat(transAmount).toFixed(2))+'</td>';
                      html = html + '<td>'+transType+'</td>';
                      html = html + '<td>'+newSplitTime[0]+',  '+newSplitTime[1]+'</td>';

                      //html = html + '<td>'+ucfirst(value.extype)+'</td>';

                      html = html + '</tr>';
                  });
              }
              $("#transferHistoryList").html(html);
          }
      });
  } */

  function showList() {
      document.getElementById('transferList').style.display = "block";
  }
  
  function getCoinList(userId){
		$("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td><td colspan="2">&nbsp;</td></tr>');
		$.ajax({
			url: "<?php echo $this->Url->build(['controller'=>'Users','action'=>'mywalletajax']); ?>/"+userId,
			dataType: 'JSON',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			success: function(resp) {
				//$("#model_qr_code_flat").hide();
				var html = '';
				$.each(resp.data.coinlist,function(resKey,respVal){
				
					var leftArrowClick = "transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','trading')";
					var rightArrowClick = "transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','main')";
					html = html + '<tr>';
					html = html + '<td><strong>'+respVal.coinShortName+'</strong> '+respVal.coinName+'</td>';
                    html = html + '<td>'+respVal.tradingBalance+'</td>';
                    html = html + '<td>'+respVal.reserveBalance+'</td>';
					html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightArrowClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left"  onClick="'+leftArrowClick+'"></span></td>';
                    html = html + '<td>'+respVal.principalBalance+'</td>';
					html = html + '</tr>';
				});
				$('tbody').html(html);
				$("#transferHistory").hide();
				/* $('#searchData').DataTable({
					language: {
						"url" : "https://www.coinibt.io/datatable_language/<?= __('datatable_language')?>.json"
					},
					"bSort": false
				}); */
			},
			error: function (e) {
				$("#ajax_coin_tr").hide();
				//$("#model_qr_code_flat").hide();
			}
		});
  }
  
  
  
	$(document).ready(function(){
		
		$("#transferList").hide();
        //transferHistory();
        user_search_select2('user_name'); /* user name search */
        $("#user_name").change(function(){
			var getUserId = $(this).val();
			if(getUserId ==""){
				$("tbody").html('<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;"><td colspan="2">&nbsp;</td><td >Please Select User </td><td colspan="2">&nbsp;</td></tr>');
				$("#user_id").val("");
				return false;
			}
			$("#user_id").val(getUserId);
			getCoinList(getUserId);
            $("#transferList").show();
            $("#transferHistory").hide();
		});
		$("#flat_date").datepicker({
			format: 'yyyy-mm-dd'
		});

		
		$("#deposit_modal_form").submit(function(event){
			    //stop submit the form, we will post it manually.
			event.preventDefault();
			$("#btnSubmit").prop("disabled", true);
			$("#model_qr_code_flat").show();
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $this->Url->build(['controller'=>'Users','action'=>'transferToAccount']); ?>",
				data: $("#deposit_modal_form").serialize(),
				dataType: 'JSON',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				success: function (resp) {
					$("#model_qr_code_flat").hide();
					if(resp.status=='true'){
						window.location.reload();
						$("#get_resp").html(resp.message).addClass('alert alert-success').show();
						setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-success').hide(); },5000)
					}
					else if(resp.status=='false'){
						$("#get_resp").html(resp.message).addClass('alert alert-danger').show();
						setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-danger').hide(); },5000)
					}
					$("#deposit_modal_form")[0].reset();
					$("#btnSubmit").prop("disabled", false);
					//getCoinList();
				},
				error: function (e) {
				
					$("#model_qr_code_flat").hide();
					$("#btnSubmit").prop("disabled", false);

				}
			});
		});
		
		
	});

  function getCoinName(coinId){
      var coinName;
      switch (coinId) {
          case 1:
              coinName = 'Bitcoin (BTC)';
              break;
          case 2:
              coinName = 'US Dollar (USDT)';
              break;
          case 17:
              coinName = 'TP3 Token Pay (TP3)';
              break;
          case 18:
              coinName = 'Ethereum (ETH)';
              break;
          case 19:
              coinName = 'Market Coin (MC)';
              break;
          case 20:
              coinName = 'Korean Won (KRW)';
              break;
          case 21:
              coinName = 'CyberTronCoin (CTC)';
              break;
          case 22:
              coinName = 'Ripple (XRP)';
              break;
          case 27:
              coinName = 'Binance Coin (BNB)';
              break;
          default:
              coinName = 'No coin Selected';
      }
      return coinName;
  }

  //jQuery('.table-responsive').on('click','.pagination li a',function(event){
  //    event.preventDefault() ;
  //    var keyy = $('form').serialize();
  //    var urli = jQuery(this).attr('href');
  //    jQuery.ajax({
  //        url: urli,
  //        data: {key:keyy},
  //        type: 'POST',
  //        success: function(data) {
  //            if(data){
  //                jQuery('.table-responsive').html(data);
  //            }
  //        }
  //    });
  //});
  </script>
