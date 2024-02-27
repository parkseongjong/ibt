<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="#"> <?= __('Users Balance');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="#"><?= __('Users Balance');?></a></li>
        </ol>
    </section>
	<?php
		$getUserTransactions = $this->Custom->getAllUserBalance($coinShortName);
		$main_balance  = $getUserTransactions['principalBalance'];
		$trading_balance  = $getUserTransactions['withdrawBalance'];
		$getTotalBuyAndSell  = $getUserTransactions['getTotalBuyAndSell'];
		$investment_amount = 0;
		if($coin_id == 17){
			$investment_amount = $this->Custom->getAllUserInvestmentAmount();
		} else if ($coin_id == 20){
			$investment_amount = $this->Custom->getAllUserInvestmentWalletAmount();
		}
		//echo $main_balance . '<br>' . $trading_balance . '<br>' . abs($getTotalBuyAndSell) . '<br>' . $investment_amount;

		$total_balance = $main_balance + $trading_balance + abs($getTotalBuyAndSell) + $investment_amount;
	?>
    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
                <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
					<div class="clearfix"></div>
					<form method="get" id="frm">
						<!--<input type="hidden" id="minus_check" name="minus_check" value="">-->
						<!--<input type="hidden" name="export" id="export" value="csv" />-->
						<div class="form-group">
							<div class="col-md-3 col-sm-3 col-xs-12">
								<input type="text" id="search_value" name="search_value" value="<?= $this->request->query('search_value'); ?>" placeholder="이름, 폰번호, 유저번호로 검색" class="form-control col-md-7 col-xs-12">
							</div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<button type="submit" class="btn btn-success"><?= __('Search');?></button>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<select id="order_value" name="order_value" class="form-control" onchange="sort()">
									<option value="totalBalance" <?php if($this->request->query('order_value') == 'totalBalance'){echo "selected";}?>><?= __('Total Balance');?></option>
									<option value="erc20Balance" <?php if($this->request->query('order_value') == 'erc20Balance'){echo "selected";}?>><?= __('Main Balance ERC20');?></option>
									<option value="principalBalance" <?php if($this->request->query('order_value') == 'principalBalance'){echo "selected";}?>><?= __('Main Balance E-Coin');?></option>
									<option value="withdrawBalance" <?php if($this->request->query('order_value') == 'withdrawBalance'){echo "selected";}?>><?= __('Trading Balance');?></option>
									<option value="reserveBalance" <?php if($this->request->query('order_value') == 'reserveBalance'){echo "selected";}?>><?= __('Reserve Balance');?></option>
									<?php if($coin_id == 17){?>
											<option value="investment_amount" <?php if($this->request->query("order_value") == "investment_amount"){echo "selected";}?>>투자 금액</option>
									<?php } else if($coin_id == 20) { ?> 
											<option value="investment_wallet_amount" <?php if($this->request->query('order_value') == 'investment_wallet_amount'){echo "selected";}?>>투자 수익금</option>
									<?php }?>
								</select>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<select id="sort_value" name="sort_value" class="form-control" onchange="sort()">
									<option value="DESC" <?php if($this->request->query('sort_value') == 'DESC'){echo "selected";}?>>높은 순</option>
									<option value="ASC" <?php if($this->request->query('sort_value') == 'ASC'){echo "selected";}?>>낮은 순</option>
								</select>
							</div> 
							<div class="col-md-3 col-sm-3 col-xs-12">
	<!-- 							<button type="button" class="btn btn-success" onclick="get_minus_list()"><?= __('마이너스');?></button> -->
								<input type="text" readonly value="합 : <?= number_format($total_balance,2) . ' ' .$coinShortName;?>" class="form-control col-md-7 col-xs-12" >
							</div>
						</div>
					</form>
				<script>
					function get_minus_list(){
						$('#minus_check').val('Y');
						$('#frm').submit();
					}
					function sort(){
						$('#frm').submit();
					}
				</script>
					<div class="clearfix"></div>
                    <div class="table-responsive m-t-15">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
							<tr style="text-align: center;vertical-align: middle">
								<th style="text-align: center;vertical-align: middle">#</th>
								<th style="text-align: center;vertical-align: middle"><?= __('Name');?></th>
								<th style="text-align: center;vertical-align: middle"><?= __('Email');?></th>
								<th style="text-align: center;vertical-align: middle"><?= __('Phone number');?></th>
								<th style="text-align: center;vertical-align: middle"><?= __('Total Balance');?></th>
                                <th style="text-align: center;vertical-align: middle"><?= __('Main Balance ERC20');?></th>
                                <th style="text-align: center;vertical-align: middle"><?= __('Main Balance E-Coins');?></th>
								<th style="text-align: center;vertical-align: middle"><?= __('Trading Balance');?></th>
								<th style="text-align: center;vertical-align: middle"><?= __('Reserve Balance');?></th>
								<?php if($coin_id == 20){
									echo '<th style="text-align: center;vertical-align: middle">투자 수익금</th>';
								}?>
								<?php if($coin_id == 17){
									echo '<th style="text-align: center;vertical-align: middle">투자 금액</th>';
								}?>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1; 
                        foreach($users->toArray() as $k=>$data){
							$this->add_system_log(200, $data['id'], 1, '고객 토탈 밸런스 조회 (이름, 메일, 번호, '.$coinShortName.')');
						?>
						
                        <tr class="" id="user_row_<?= $data['id']; ?>" style="text-align: center;vertical-align: middle">
                            <td><?=$data['id'];?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['phone_number']); ?></a></td>
                            <td><?= number_format($data['totalBalance'],4); ?></td>
                            <td><?= number_format($data['erc20Balance'],4); ?></td>
							<td><?= number_format($data['principalBalance'],4); ?></td>
<!--                            <td>--><?//= number_format($data['eTokenBalance'],4); ?><!--</td>-->
							<td><?= number_format($data['withdrawBalance'],4); ?></td>
							<td><?= number_format(abs($data['reserveBalance']),4); ?></td>
							<?php if($coin_id == 20){
								echo '<td>'.number_format($data['investment_wallet_amount'],2).'</td>';
							}?>
							<?php if($coin_id == 17){
								echo '<td>'.number_format($data['investment_amount']).'</td>';
							}?>
                        </tr>
						
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'usercoinbalancefast',$coinShortName)+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first("처음");

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            //echo $paginator->prev("이전");
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 9));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            //echo $paginator->next("다음");
                        }

                        // the 'last' page button
                        echo $paginator->last("마지막");

                        echo "</div>";

                    ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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

        
	function export_f(v) {
		$('#export').val(v);
		$("#frm").submit();
		$('#export').val('');
	}
</script>


