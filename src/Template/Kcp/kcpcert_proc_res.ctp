<script type="text/javascript">
    //var thisOpener = window.opener;
    window.onload=function()
    {
        try
        {
            opener.auth_data( document.form_auth ); // 부모창으로 값 전달, Pass value to parent window
            //thisOpener.auth_data( document.form_auth );
            window.close();// popup close
        }
        catch(e)
        {
            alert(e); // 정상적인 부모창의 iframe 를 못찾은 경우임, If the "iframe" of the parent window is not found
        }
    }
</script>
   
   
<body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
    <form name="form_auth" method="post" id="form_auth">
        <?= $sbParam ?>
    </form>
</body>




<script src="<?php echo $this->request->webroot; ?>assets/html/js/jquery.js"></script> 
<script>
/*
$(document).ready(function(){
	test();
});
function test() {
	if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 || navigator.userAgent.indexOf('ios-web-view') > - 1 ) ){
		parent.location.replace('/front2/Users/signup');
	} else {
		opener.location.replace('/front2/Users/signup');
		window.close();
	}
}
*/
</script>

