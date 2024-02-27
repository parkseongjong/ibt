		<div class="menu_left">
			<h1><?=__('Customer Center') ?></h1>
			<p class="item">
				<a href="/front2/customer/notice" class="notice"><?=__('Notice') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/faq" class="faq"><?=__('FAQ') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/deallimit" class="deallimit"><?=__('Guide to deposit/withdrawal limits by member level') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/reqdoc" class="reqdoc"><?=__('Information on submitting certification data') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/joininfo" class="joininfo"><?=__('Membership Registration') ?></a>
			</p>
			<!-- <p class="item">
				<a href="/front2/document/authinfo" class="authinfo"><?=__('Authentication Method Guide') ?></a>
			</p> -->
			<p class="item">
				<a href="/front2/document/priceinfo" class="priceinfo"><?=__('Fee Information') ?></a>
			</p>
			<p class="item">
				<a href="/front2/customer/info-listed" class="info-listed"><?=__('Listed Info');?></a>
			</p>
			<p class="item">
                <?php if(!empty($_SESSION)){ ?>
                    <a href="/front2/customer/qna"  class="qna"><?=__('1:1 Inquiries') ?></a>
                <?php }else{ ?>
                    <a href="/front2/Users/login"  class="qna"><?=__('1:1 Inquiries') ?></a>
                <?php } ?>

				<!--<a href="http://pf.kakao.com/_rWxdVK/chat" class="qna" target="_blank"><?/*=__('1:1 Inquiries') */?></a>-->
			</p>
        </div>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});

        //link_type();


        function link_type() {
            const ios = 'http://pf.kakao.com/_rWxdVK/chat'; // 웹, IOS 용
            const android = 'kakaolink://pf.kakao.com/_rWxdVK/chat '; // 안드로이드 전용

            const device_check = navigator.userAgent.toLowerCase();
            if(device_check.indexOf('android') !== -1) {//안드로이드일 경우우
                $('.qna').attr('href', android);
            } else {//Web 또는 IOS 일경우
                $('.qna').attr('href', ios);
            }
        }


        </script>