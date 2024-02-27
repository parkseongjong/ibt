<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="description" content="Coin IBT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more.">
<meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading">
<meta name="author" content="">
<link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon2.ico" type="image/x-icon" />
<title>COIN IBT Exchange</title>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/font-awesome.css" />
<!-- <link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/animate+animo.css" /> -->
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/csspinner.min.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/app.css?id=<?php echo time(); ?>" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_customer.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/bootstrap/dist/css/bootstrap.min.css'; ?>" />
<script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script> 
<script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script> 
<script src="<?php echo $this->request->webroot ?>assets/html/js/modernizr.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/html/js/fastclick.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>
<body class="animate-border divide">
<div id="overlayLoader" class="" style="transform: translateY(-100%);">
  <div id="preloader" class="" style="opacity: 0.1; transform: translateY(-80px);"> <span></span> <span></span> </div>
</div>
<section class="wrapper">
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
		}
		.a_table_center table th{  width: 11%; 
			padding: 10px;
			}
		.a_table_center table td{ 
			padding: 2px;
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
			width: 50%;
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
		.pagination>li>a, .pagination>li>span {
			position: relative;
			float: left;
			padding: 4px 11px;
			margin-left: -1px;
			line-height: 1.42857143;
			color: #337ab7;
			text-decoration: none;
			background-color: #fff;
			border: 1px solid #ddd;
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
			padding: 15px;
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
			.a_table_center table td{ 
				padding: 10px;
			}
		}

		@media (min-width:992px) {
			.modal-lg {
				width: 900px
			}
		}
		.red {color:red;}
		.blue {color:blue;}
	</style>
	<div class="" style="margin-top:5px">
		<div class="profile_box">
			<div class="service_info application">
				<div class="a_table_center">
					<table class="list" border="1">
						<thead style="position: sticky">
							<tr>
								<th>투자금액</th>
								<th>투자기간</th>
								<!--<th>투자신청일</th>-->
								<th>금액</th>
								<th>종류</th>
								<th>생성일</th>
							</tr>
						</thead>
						<tbody id="">
						<?php 
							if(count($log_list) < 1 ) {
								echo "<tr><td colspan='5'>수익금이 없습니다.</td></tr>";
							} else {
								foreach($log_list as $l){
									$type = '';
									$color = '';
									if($l->type == 'S'){
										$type = '입금';
										$color = 'blue';
									} else if($l->type == 'W'){
										$type = '출금';
										$color = 'red';
									}
									$created = $l->created->format('c');
									$dt = new DateTime($created, new DateTimeZone('Asia/Seoul'));
									$dt->setTimezone(new DateTimeZone("KST"));
									$created = $dt->format('Y-m-d H:i:s');

									$date = date('Y-m-d');
									if(explode(' ',$created)[0] == $date){
										$created = explode(' ',$created)[1];
									}
						?>
									<tr>
										<td><?=number_format($l->alist['quantity']);?> <?=$l->alist['unit']?></td>
										<td><?=$l->alist['service_period_month'];?> 일</td>
										<!--<td><?=$l->alist['created'];?></td>-->
										<td><?=number_format($l->amount);?> <?=$l->alist['unit']?></td>
										<td class="<?=$color;?>"><?=$type?></td>
										<td><?=$created;?></td>
									</tr>
						<?php } 
							}
						?>
						</tbody>
					</table>
					<?php $this->Paginator->options(array('url' => array('controller' => 'Investment', 'action' => 'mywalletlog')));
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
                        echo $paginator->numbers(array('modulus' => 2));

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
</body>
</html>
