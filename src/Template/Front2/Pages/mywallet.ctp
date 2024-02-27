<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

.table > thead > tr > th { padding: 14px 8px;}
.form-control{background-color:#fff!important;}
.table > thead > tr > th  {
    text-align: center;
}
@media only screen and (max-width: 767px) {
.table>thead>tr>th{    font-size: 11px;    padding-right: 18px!important;    vertical-align: middle!important;}
}
</style>
<?php
// 변수에 이전페이지 정보를 저장
$prevPage = $_SERVER['HTTP_REFERER'];

//회사 IP만 오픈
/*if($_SERVER['REMOTE_ADDR'] != "119.196.13.33"){
    echo "<script>";
    echo "alert('점검중입니다.');";
    echo "history.back();";
    echo " </script>";

}*/


?>
<link href="<?php echo $this->request->webroot?>datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="<?php echo $this->request->webroot?>datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div class="containers containers2"  style="">

	<div class="profile_box" style="max-width:100%;">

		<?php echo $this->element('Front2/profile_menu'); ?>

		<div class="person_info">
			<div class="table-responsive">
							  <?= $this->Flash->render() ?>
                             <table class="table table-striped" id="searchData">
								<thead style="background: #d3ccea;    font-size: 16px;">
								<tr >
									<th style="color:#000"><?= __('Coin Name')?></th>

                                    <th style="color:#000">storage box</th>
                                    <th style="color:#000">Safe</th>
									<th style="color:#000"><?= __('Trading Account')?></th>
                                    <th style="color:#000"><?= __('Reserved')?></th>
									<th style="color:#000"><?= __('Transfer')?></th>
									<th style="color:#000"><?= __('Main Account')?></th>

								</tr>
								<thead>
								<tbody>
								<tr id="ajax_coin_tr" style="vertical-align: center;alignment: center;">
									<td colspan="2">&nbsp;</td>
									<td ><img id="model_qr_code_flat" src="/ajax-loader.gif" /></td>
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
		</div>

	

		



	</div>

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
		<?php echo $this->Form->create('',array('method'=>'post','id'=>'deposit_modal_form','enctype'=>'multipart/form-data','autocomplete'=>'off')); ?>
		<input type="hidden" class="form-control" id="coin_id" name="coin_id">
		<input type="hidden" class="form-control" id="transfer_to" name="transfer_to">
		
		
		<div class="form-group">
		  <label for="email"><?= __('Amount: ')?></label>
		  <input type="number" class="form-control" required placeholder="<?= __('Enter Amount')?>" name="amount" id="amounts">
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
  
  function getCoinList(){
		$("#ajax_coin_tr").show();
		$.ajax({
			url: "<?php echo $this->Url->build(['controller'=>'Wallet','action'=>'mywalletajax']); ?>",
			dataType: 'JSON',
			success: function(resp) {
				//$("#model_qr_code_flat").hide();onClick="'+rightArrowClick+'",onClick="'+leftArrowClick+'"
				var html = '';
				$.each(resp.data.coinlist,function(resKey,respVal){
				
					var leftArrowClick = "transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','trading')";
					var rightArrowClick = "transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','main')";

                    var rightsaveClick ="transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','savetrading')";
                    var leftsaveClick = "transferAmount('"+respVal.coinShortName+"','"+respVal.coinId+"','save')";

					html = html + '<tr>';
					html = html + '<td><strong>'+respVal.coinShortName+'</strong> '+respVal.coinName+'</td>';
                    html = html + '<td>'+respVal.tradingSave+'</td>';
                    html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightsaveClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left" onClick="'+leftsaveClick+'" ></span></td>';
                    html = html + '<td>'+respVal.tradingBalance+'</td>';
                    html = html + '<td>'+respVal.reserveBalance+'</td>';
                    html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightArrowClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left" onClick="'+leftArrowClick+'" ></span></td>';
                    html = html + '<td>'+respVal.principalBalance+'</td>';
					html = html + '</tr>';
				});
				$("#ajax_coin_tr").hide();
				$('tbody').html(html);
				$('#searchData').DataTable({
    language: {
        "url" : "https://www.bitsomon.com/datatable_language/<?= __('datatable_language')?>.json"
    },
	"bSort": false
});
			},
			error: function (e) {
				$("#ajax_coin_tr").hide();
				//$("#model_qr_code_flat").hide();
			}
		});
  }
  
  
  
	$('document').ready(function(){
		getCoinList();

		$("#flat_date").datepicker({
			format: 'yyyy-mm-dd'
		});

		//changed .submit to .one
		$("#deposit_modal_form").one('submit',function(event){
			    //stop submit the form, we will post it manually.
			event.preventDefault();
			$("#btnSubmit").prop("disabled", true);
			$("#model_qr_code_flat").show();
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $this->Url->build(['controller'=>'Wallet','action'=>'transgetToAccount']); ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: $("#deposit_modal_form").serialize(),
				dataType: 'JSON',
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

  function confirm_alert3() {
      var language = getCookie("Language");
      if (language === "ko-KR") {
          return confirm("서비스 점검중입니다.");
      } else {
          return confirm("Service under maintenance.");
      } //works IE/SAFARI/CHROME/FF
  }
  
  </script>
