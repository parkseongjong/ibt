<style>
li.sub_menus.active a {
  color: #6738ff !important;
}
</style>
<div class="header2">
  <div id="gnb_menu">
    <ul>
      <!--                    <li id="back"><a href="javascript: history.go(-1);">-->
      <!--</a> </li>-->
      <!-- <li><a id="back" href="javascript: history.go(-1);">&nbsp;&nbsp;&nbsp;</a><a href="https://www.coinibt.io/front2/document/cominfo"><?= __('About Us') ?></a></li> -->
      <?php if (!empty($_SESSION['Auth']['User'])) { ?>
				<?php if ($_SESSION['Auth']['User']['annual_membership'] == "Y") { ?>
					<li>
						<input type="button" class="member left" value="<?= __('Annual Member') ?>" />
					</li>
				<?php } else { ?>
					<li style="display: inline-block;">
						<input type="button" class="member left" value="<?= __('General Member') ?>" />
					</li>
				<?php } ?>
			<?php } ?>
      <!--<li><a href="#"><?/*= __('Related shop') */?></a></li>
      <li><a id="barry" href="javascript:redirectToApp()"><img src="/wb/imgs/barrybarries.png" /></a></li>-->
      
      <?php if (!empty($_SESSION['Auth']['User'])) { ?>
          <?php if ($_SESSION['Auth']['User']['annual_membership'] == "Y") { ?>
              <li>
                  <input type="button" class="member" value="<?= __('Annual Member') ?>" />
              </li>
          <?php } else { ?>
              <li style="display: inline-block;">
                  <input type="button" class="member" value="<?= __('General Member') ?>" />
              </li>
          <?php } ?>
      <?php } else { ?> <?php } ?>


      <!-- <li class="right" style="display: inline-block;"><a href="javascript:" onclick="changeLanguage('ko_KR')">KOR</a> | <a href="javascript:" onclick="changeLanguage('en_US')">ENG</a></li> -->
    </ul>
  </div>
  <div id="top_menu">

    <!-- <div class="site_logo"><a href="/"><img src="/wb/imgs/logo_coinibt2.png" /></a> -->
    <div class="site_logo"><a href="/"><img src="/wb/imgs/smbit/smbit_logo.jpg" style="width: 80px; height: 80px;" /></a>
    </div>
    <ul>
      <?php
		$active = "";
		$active1 = "";
		$active2 = "";
		$active3 = "";
		$active4 = "";
		$active5 = "";
		$active6 = ""; // howtouse
	 
		if(isset($this->request->params['prefix']) && $this->request->params['prefix']== 'front2'){
			$controller_name = strtolower($this->request->params['controller']);
			$action_name = $this->request->params['action'];
			if (!empty($controller_name) && !empty($action_name)) {
				if ($controller_name == 'exchange') {
					$active = "active";
				} else if ($controller_name == 'investment') {
					$active1 = "active";
				} else if ($controller_name == 'assets') {
					$active2 = "active";
				} else if ($controller_name == 'wallet') {
					$active3 = "active";
				} else if ($controller_name == 'customer') {
					$active4 = "active";
				} else if ($controller_name == 'document') {
					// if($action_name == 'priceinfo'){
					// 	$active5 = "active";
					// } else {
					// 	$active4 = "active";
					// }

          $active4 = "active";
				} else if ($controller_name == 'howtouse') {
					$active6 = "active";
				}
			}
		}
	 ?>
      <li class="sub_menus <?php echo $active; ?>"><a href="/front2/exchange/index/TP3/USDT"><?= __('Exchange') ?></a></li>
      <!--/front2/exchange/index/TP3/KRW?exchage-->
      <li class="sub_menus <?php echo $active1; ?>" style="display: block">
        <!--<a href="/front2/investment" >--><a href="/front2/investment"><?= __('Deposit Loan') ?></a>
      </li>
      <li class="sub_menus <?php echo $active2; ?>" style="display: block">
        <!--<a href="/front2/assets/mycoin"><a href="/front2/assets/mycoin"><?= __('Asset Withdrawal') ?></a>-->
		<a href="javascript:void(0)" onclick="go_asset_withdrawal('nav')"><?= __('Asset Withdrawal') ?></a>
      </li>
      <li class="sub_menus <?php echo $active3; ?>">
        <!--<a href="/front2/wallet">--><a href="/front2/wallet"><?= __('Asset Inquiry') ?></a>
      </li>
      <li class="sub_menus <?php echo $active4; ?>">
        <!--<a href="/front2/customer/notice" >--><a href="/front2/customer/notice"><?= __('Customer Center') ?></a>
      </li>
      <!-- <li class="sub_menus <?php echo $active5; ?>">
        <a href="/front2/document/priceinfo"><?= __('Fee Information') ?></a>
      </li> -->
      <li class="sub_menus <?php echo $active6; ?>">
        <!--<a href="/front2/howtouse/normaluser">--><a href="/front2/howtouse/normaluser" ><?= __('Howtouse') ?></a>
      </li> <!-- /front2/howtouse/normaluser-->
    </ul>


    <?php if (!empty($_SESSION['Auth']['User'])) { ?>
    <div class="btn_right_man">
      <div class="btn_right">
        <input type="button" value="<?= __('Logout') ?>" class="logout button btn" onclick="goLogout()" />
      </div>
      <div class="btn_right">
        <div class="btn-right-username" style="line-height:44px">
          <a href="<?php echo $this->Url->build(['prefix' => 'front2', 'controller' => 'users', 'action' => 'profile']) ?>" class="profile-username">
            <div class="profile-img-wrap"><img class="profile-img" src="/wb/imgs/profile-default.png" alt="profile"></div>
            <div class="nav-user-name">
              <?php echo ucfirst($_SESSION['Auth']['User']['name']); ?>
            </div>
          </a>
          <!-- <?php if ($_SESSION['Auth']['User']['annual_membership'] == "Y") { ?>
            <input type="button" class="member" value="<?= __('Annual Member') ?>" />
          <?php } else { ?>
            <input type="button" class="member" value="<?= __('General Member') ?>" />
          <?php } ?> -->
        </div>
      </div>
    </div>

    <?php } else { ?>
    <div class="btn_right_man">
      <div class="btn_right">
          <?php
          if($_SERVER["REMOTE_ADDR"] == '112.171.120.140' || $_SERVER["REMOTE_ADDR"] == '62.122.142.18' || $_SERVER['REMOTE_ADDR'] == '62.122.136.246'){
          ?>
        <input type="button" value="<?= __('Sign Up') ?>" class="join button btn" onclick="goJoin();" />
          <?php }else {?>
              <input type="button" value="<?= __('Sign Up') ?>" class="join button btn" onclick="goJoin();" />
          <?php } ?>
        <!--onclick="goJoin()" -->
      </div>
      <div class="btn_right">
        <input type="button" value="<?= __('Login') ?>" class="login button btn" onclick="goLogin();" />
        <!--goLogin()-->
      </div>

    </div>
    <?php } ?>

  </div>
  <div class="floating-home-button">
      <a href="/">
          <img src="/wb/imgs/home.svg" alt="Home">
      </a>
  </div>
</div>
<?php


	//$access_ip = ['211.44.188.4','122.176.83.150'];
	$access_ip = ['211.44.188.4'];
	$this_ip = $this->get_client_ip();

	if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front2') {
		//if($this->request->params['action'] == 'login' || $this->request->params['action'] == 'signup'){
			//if(!in_array($this_ip, $access_ip)){
				//echo '<script>$(function(){modal_alert("알림","COIN IBT입금 계좌 변경으로 인해<br> 21년 05월 11일 17시까지 긴급 점검중에 있습니다.<br> 불편을 드려 죄송합니다.");})</script>';
			//}
		//}
	}
?>

<script>
var isMobile = {
  Android: function() {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function() {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function() {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function() {
    return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function() {
    return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
  },
  any: function() {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};

function detectMob() {
  const toMatch = [
    /Android/i,
    /webOS/i,
    /iPhone/i,
    /iPad/i,
    /iPod/i,
    /BlackBerry/i,
    /Windows Phone/i
  ];

  return toMatch.some(function chk_match(toMatchItem) {
    return navigator.userAgent.match(toMatchItem);
  });
}
/* if the device is not ios hide the download button */


$(document).ready(function() {
  $(".sub_menus li").click(function() {
    $(".sub_menus li").css("color", "#6738ff");
    $(this).css("color", "#000000");
  });

  checkFloatingHomeButtonVisible();
});

function redirectToApp() {
  if (detectMob()) {
    goToApp();
  } else {
    $("#barry").prop({
      "href": "https://barrybarries.kr",
      "target": "_blank"
    });
    //$("#barry").prop("href", "https://play.google.com/store/apps/details?id=com.cybertronchain.barrybarries");
  }
}

function goToApp() {

  var scheme = "barrybarries";
  var h = 'com.cybertronchain.barrybarries';
  var ios_id = "id1537941110";
  var openURL = "barrybarries" + window.location.pathname + window.location.search + window.location.hash;
  var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
  var Android = /Android/.test(navigator.userAgent);
  var newLocation;
  if (iOS) {
    newLocation = scheme + "://" + scheme;
    /*
    setTimeout( function () {
    	if ( ( new Date() ).getTime() - visiteTm < 3000 ) { // app store
    		newLocation = "https://itunes.apple.com/app/" + ios_id;
    	}
    } ,2500 );
    setTimeout( function () { // app
    	newLocation = scheme + "://";
    } ,0 );
    */
  } else if (Android) {
    //newLocation = "intent://" + openURL + "#Intent;scheme=" + scheme + ";package=com.cybertronchain." + scheme + ";end";
    newLocation = scheme + '://' + h;
  } else {
    newLocation = scheme + "://" + openURL;
  }
  console.log(newLocation)
  window.location.replace(newLocation);
  //window.onload = goToApp;
}
// window.onload = redirectToApp;

// if(isMobile.iOS()){
//     $("#barry").prop("href", "barrybarries://barrybarries");
//   //  https://apps.apple.com/us/app/ctc-wallet/id1527694686
// }else {
//     $("#barry").prop("href", "market://details?id=com.cybertronchain.barrybarries");
// }

function confirm_ip(){
	var client_ip = '<?=$this_ip;?>';
	return true;
	//if(client_ip  == '108.162.245.167' || client_ip  == '211.44.188.4' || client_ip  == '122.176.83.150'){
	if(client_ip  == '211.44.188.4'){
		return true;
	} else {
		modal_alert('알림','현재 입금 계좌 변경으로 인해 21년 05월 11일 17시까지 긴급점검중에 있습니다. 불편을 드려 죄송합니다.');
		return false;
	}
}

/**
 * 모바일에서 화면 하단에 보이는 홈 버튼을 chart가 나오는 거래소에서는 안보이게 처리합니다.
 * @author bwpark
 */
function checkFloatingHomeButtonVisible() {
    const currentLocation = document.location.href;
    const isExchangePage = currentLocation.replace('://', '').includes('/exchange/');
    console.log('currentLocation2? ', isExchangePage);
    if (isExchangePage) {
        const floatingHomeButton = document.querySelector('.floating-home-button');
        if (floatingHomeButton === undefined) return;

        floatingHomeButton.classList.add('off');
    }
}
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122322473-1"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
  dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'UA-122322473-1');

function goLogin() {
    document.location.href = "/front2/Users/login";
}

function goLogout() {
  document.location.href = "<?php echo $this->Url->build(['controller' => 'users', 'action' => 'logout']); ?>";
}

function goJoin() {
	document.location.href = "/front2/Users/signup";
}

function goJoin2() {
    alert("특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다.");
    return;
}
var currentLang = getCookie("Language");
//var currentLang = "ko_KR";
console.log(currentLang);
if (currentLang == undefined) {
  var userLang = navigator.language || navigator.userLanguage;
  var setDefaultLang = (userLang == "ko-KR") ? "ko_KR" : "en_US";
  //var setDefaultLang = (userLang == "ko-KR") ? "ko_KR" : "en_US";
  //setDefaultLang = "ko_KR";
  changeLanguage("@@@"+setDefaultLang);
}

function changeLanguage(val) {
  val = "ko_KR";
  setCookie('Language', val, 365);
  document.location.reload();
}

function setCookie(name, val, exp) {
  var d = new Date();
  d.setTime(d.getTime() + exp * 24 * 60 * 60 * 1000);
  document.cookie = name + "=" + val + "; path=/; expires=" + d.toUTCString() + ";";
}

/*function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}*/
function getCookie(cookieName){
    var cookieValue=null;
    if(document.cookie){
        var array=document.cookie.split((escape(cookieName)+'='));
        if(array.length >= 2){
            var arraySub=array[1].split(';');
            cookieValue=unescape(arraySub[0]);
        }
    }
    return cookieValue;
}
</script>