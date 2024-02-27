<div class="content-container">
	<div class="content-inner">
		<div class="content-title"> COIN IBT 회원탈퇴가
			<br /> 안전하게 완료되었습니다. </div>
		<div class="content-subtitle"> 그동안 COIN IBT 서비스를 이용해 주셔서 감사드립니다.
			<br /> 보다 나은 서비스 제공을 위해 최선을 다하겠습니다. </div>
		<button class="shortcut-button" onClick="onClickGoHomeButton()"> Coin IBT 홈 바로가기 </button>
	</div>
</div>

<script>
function onClickGoHomeButton() {
  location.href="/";
}
</script>

<style>
.wrapper {
  background-color: #fff;
  height: initial;
}
.content-container {
  width: 100%;
  height: 100%;
  color: #000;
}
.content-container .content-inner {
  height: 100%;
  padding: 180px 0;
  text-align: center;
}
.content-container .content-inner .content-title {
  font-size: 40px;
  line-height: 1.5;
  font-weight: 700;
  margin-bottom: 40px;
}
.content-container .content-inner .content-subtitle {
  font-size: 24px;
  color: #535353;
  line-height: 1.5;
  margin-bottom: 120px;
}
.content-container .content-inner .shortcut-button {
  width: 450px;
  padding: 15px 0;
  background-color: #fff;
  color: #6738ff;
  border: 2px solid #6738ff;
  text-align: center;
  font-size: 22px;
  cursor: pointer;
}

@media (max-width: 990px) {
  .wrapper {
  }
  .content-container {
  }
  .content-container .content-inner {
  }
  .content-container .content-inner .content-title {
    font-size: 24px;
  }
  .content-container .content-inner .content-subtitle {
    font-size: 16px;
    padding: 0 5px;
  }
  .content-container .content-inner .shortcut-button {
    width: 200px;
    font-size: 16px;
  }
}
</style>