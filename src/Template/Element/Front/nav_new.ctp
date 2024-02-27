		<div class="header2">
			<div id="gnb_menu">
				<ul>
					<li><a href="https://www.cybertronchain.com/beta/index.new.php" target="_blank">회사소개</a></li>
					<li><a href="#">연관서비스</a></li>
					<li><a href="http://barrybarries.kr" target="_blank"><img src="/wb/imgs/barrybarries.png" /></a></li>

					<li class="right"><a href="#">KOR</a> | <a href="#">ENG</a></li>
				</ul>
			</div>
			<div id="top_menu">
				<ul>
					<li class="site_logo"><a href="/index2.html"><img src="/wb/imgs/logo_coinibt.png" /></a></li>

					<li class="sub_menus"><a href="/front/exchangeNew/index/BTC/NTR">거래소</a></li>
					<li class="sub_menus"><a href="#">간편구매</a></li>
					<li class="sub_menus"><a href="#">투자/대출</a></li>
					<li class="sub_menus"><a href="#">자산 입출금</a></li>
					<li class="sub_menus"><a href="#">자산조회</a></li>
					<li class="sub_menus"><a href="/front/customer/notice">고객센터</a></li>
<?php if(!empty($_SESSION['Auth']['User'])) { ?>
					<li class="right">
						<input type="button" value="로그아웃" class="login button btn" onclick="goLogout()" />
					</li>
					<li class="right">
						<div style="line-height:44px;"><a href="<?php echo $this->Url->build(['controller'=>'usersNew','action'=>'profile']) ?>"><?php echo ucfirst($_SESSION['Auth']['User']['username']); ?></a></div>
					</li>
<?php } else { ?>
					<li class="right">
						<input type="button" value="회원가입" class="join button btn" onclick="goJoin()" />
					</li>
					<li class="right">
						<input type="button" value="로그인" class="login button btn" onclick="goLogin()" />
					</li>
<?php } ?>
				</ul>
			</div>
		</div>

<script>
$("document").ready(function(){
});
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122322473-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122322473-1');
</script>
