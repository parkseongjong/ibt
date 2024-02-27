<style type="text/css">
.cate_tag {
}
.cate_tag a {
	font-size: 18px;
	font-weight: bold;
	font-style: normal;
	line-height: 2.67;
	text-align: left;
	color: #c0c0c0;
	margin-left: 30px;
}
.cate_tag a.on {
	color: #000;
	padding-bottom: 4px;
	border-bottom: 1px solid #000;
}

.cate_title { background:#fcfcfe; padding: 30px; }
.reqdoc_tab h1 {
	font-size: 20px;
	font-weight: bold;
	font-style: normal;
	line-height: 2.4;
	color: #585858;
}
.reqdoc_tab h2 {
	font-size: 18px;
	font-weight: bold;
	font-style: normal;
	line-height: 2.67;
	color: #4b4b4b;
	margin-top:90px;
}
.reqdoc_tab h2 span {
	font-weight: normal;
	margin-left: 20px;
}
.reqdoc_tab h3 {
	font-size: 18px;
	font-weight: bold;
	font-style: normal;
	line-height: 2.67;
	color: #6738ff;
}
.reqdoc_tab h3 span {
	font-weight: normal;
	color: #4b4b4b;
	margin-left: 20px;
}
.reqdoc_tab .cate_desc {
	font-size: 16px;
	font-weight: normal;
	font-style: normal;
	line-height: 1.88;
	text-align: left;
	color: #585858;
}
.reqdoc_tab ul.person { list-style-type:none; overflow:hidden; margin-bottom:60px }
.reqdoc_tab ul.person li {
	float:left;
	font-size: 18px;
	font-weight: normal;
	line-height: 1.67;
	color: #4b4b4b;
}
.person_card thead th {
	font-weight:bold;
	color:#fff;
	width:33%;
	padding:18px 16px !important;
	background: #240978 !important;
	border-left: solid 1px #dddddd;
}
.person_card tbody td {
	width:33%;
	text-align: center !important;
	border-left: solid 1px #dddddd;
}
.person_card p {
	font-size: 18px;
	font-weight: normal;
	line-height: 1.67;
	color: #4b4b4b;
}

.email_ex { padding-right: 70px }
.email_ex p {
	font-size: 16px;
	font-weight: normal;
	line-height: 1.5;
	color: #4b4b4b;
}
.email_ex table { margin: 30px 0 80px 20px }
.email_ex tbody th {
	background:#fbfbfc !important;
	border-bottom: solid 1px #efefef;
	font-size: 16px !important;
	font-weight: normal !important;
	line-height: 1.5;
	text-align: center !important;
	color: #1e1e1e !important;
}
.email_ex tbody td {
	border-bottom: solid 1px #efefef !important;
}
</style>

<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__('Information on submitting certification data') ?></li>
			</ul>

			<div class="cate_tag">
				<a href="/front2/document/reqdoc/1" <?php if($tabpos=='1') echo "class='on'"?>>#<?=__('Reset cell phone number') ?></a>
				<!--a href="/front2/document/reqdoc/2" <?php if($tabpos=='2') echo "class='on'"?>>#개명절차</a-->
				<a href="/front2/document/reqdoc/3" <?php if($tabpos=='3') echo "class='on'"?>>#<?=__('Reset account number') ?></a>
				<!--a href="/front2/document/reqdoc/4" <?php if($tabpos=='4') echo "class='on'"?>>#코인 오입금 정정요청</a-->
				<a href="/front2/document/reqdoc/5" <?php if($tabpos=='5') echo "class='on'"?>>#<?=__('Coin Withdrawal Confirmation') ?></a>
			</div>

			<?php echo $this->element('Front/reqdoc_'.$tabpos); ?>

		</div>
		<div class="cls"></div>

	</div>

</div>
