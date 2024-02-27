function removeZeros(num){
    return (+num).toFixed(2).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/, '$1');
}

function thousands_separators(num)
{
    let num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function isNumberKey(txt, evt) {
    let charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode === 46) {
        //Check if the text already contains the . character
        return txt.value.indexOf('.') === -1;
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}

function confirm_alert() {

    let language = window.navigator.userLanguage || window.navigator.language;

    if(language === "ko-KR"){
        return confirm("서버 점검 안내 \n2020년12월 29일 18시부터 \n2020년 12월 30일 18시까지");
    } else {
        return confirm("Service temporary unavailable \nFrom 18:00 on December 29, 2020 \nTill 18:00 December 30, 2020");
    }//works IE/SAFARI/CHROME/FF
}

/* get language */
function getlang(){
    let cookie = getCookie('Language');
    if(cookie === 'ko_KR'){
        return 'kr';
    } else {
        return 'en';
    }
}

function __(str) {
    if (typeof(i18n) != 'undefined' && i18n[str]) {
        if(getlang() === 'kr')
            return i18n[str];
        else
            return str;
    }
    return str;
}

function checkPass(str){
    let re = /^[0-9]{6,}$/;
    return re.test(str);
}

function checkName(str){
    let reg = /^[a-zA-Z ]*$/;
    return reg.test(str);
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function only_number(obj) {
    $(obj).keyup(function(){
        $(this).val($(this).val().replace(/[^0-9]/g,""));
    });
}

function getCoinName(coinId){
    let coinName;
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

/* removeComma */
function removeComma(str) {
    return parseInt(str.replace(/,/g, ""));
}

/* secret key copy */
function copyTxt(){
    let copyText = document.getElementById("key");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
}

function ucfirst(str) {
    if (str != null) {
        str = str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
            return letter.toUpperCase();
        });
    } else {
        str = '';
    }
    return str;
}

function validateInputAddresses(address) {
    return (/^(0x){1}[0-9a-fA-F]{40}$/i.test(address));
}

function countDecimals(val) {
    let text = val.toString()
    // verify if number 0.000005 is represented as "5e-6"
    if (text.indexOf('e-') > -1) {
        let [base, trail] = text.split('e-');
        let deg = parseInt(trail, 10);
        return deg;
    }
    // count decimals for number in representation like "0.123456"
    if (Math.floor(val) !== val) {
        return val.toString().split(".")[0].length || 0;
    }
    return 0;
}

function goLogin() {
    document.location.href = "/front2/Users/login";
}

function goJoin() {
    document.location.href = "/front2/Users/signup";
}

function goJoin2() {
    alert("특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다2.");
    return;
}

var currentLang = getCookie("Language");
if (currentLang === undefined) {
    let userLang = navigator.language || navigator.userLanguage;
    let setDefaultLang = (userLang === "ko-KR") ? "ko_KR" : "en_US";
    changeLanguage(setDefaultLang);
}

function changeLanguage(val) {
    setCookie('Language', val, 365);
    document.location.reload();
}

function setCookie(name, val, exp) {
    let d = new Date();
    d.setTime(d.getTime() + exp * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + val + "; path=/; expires=" + d.toUTCString() + ";";
}

function getCookie(cookieName){
    let cookieValue=null;
    if(document.cookie){
        let array=document.cookie.split((escape(cookieName)+'='));
        if(array.length >= 2){
            let arraySub=array[1].split(';');
            cookieValue=unescape(arraySub[0]);
        }
    }
    return cookieValue;
}

window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'UA-122322473-1');

function redirectToApp() {
    if (detectMob()) {
        goToApp();
    } else {
        $("#barry").prop({
            "href": "https://barrybarries.kr",
            "target": "_blank"
        });
    }
}

function goToApp() {

    let scheme = "barrybarries";
    let h = 'com.cybertronchain.barrybarries';
    let ios_id = "id1537941110";
    let openURL = "barrybarries" + window.location.pathname + window.location.search + window.location.hash;
    let iOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    let Android = /Android/.test(navigator.userAgent);
    let newLocation;
    if (iOS) {
        newLocation = scheme + "://" + scheme;

    } else if (Android) {
        newLocation = scheme + '://' + h;
    } else {
        newLocation = scheme + "://" + openURL;
    }
    console.log(newLocation)
    window.location.replace(newLocation);
}

function showAlert() {
    alert("Sign-up is currently disabled, due to on-going testing.");
}

function confirm_alert1() {
    let language = getCookie("Language");
    if (language === "ko_KR") {
        //return confirm("서버 점검 안내 \n 14시부터 \n22시까지");
        return confirm("코인 출금은 현재 점검중으로 이용할 수 없습니다. 이용에 불편을 드려 죄송합니다.");
    } else {
        //return confirm("Service temporary unavailable today \nFrom 14:00 \nTill 22:00");
        return confirm("Checking the server");
    } //works IE/SAFARI/CHROME/FF
}

function confirm_alert2() {
    let language = window.navigator.userLanguage || window.navigator.language;
    if (language === "ko-KR") {
        return confirm("서버점검중입니다.");
    } else {
        return confirm("Checking the server");
    } //works IE/SAFARI/CHROME/FF
}

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
    let toMatch = [
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
function go_asset_withdrawal(type){
	//confirm_alert1();
	//return;
	setCookie('mycoins_type', type, 365);
	location.href = "/front2/assets/mycoins";
}