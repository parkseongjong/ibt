<div class="content-container">
  <div class="content-inner">
    <div class="content-top-area">
      <div class="content-top-title">
        휴면 해제 결과
      </div>
    </div>
    <div class="content-bottom-area">
      <div class="content-bottom-title">
        휴면 해제 결과
      </div>

      <div class="content-bottom-content">
        휴면 계정 해제 요청이 정상적으로 처리 되었습니다. <br />
        휴면 해제 완료 후 로그인이 필요합니다.
      </div>

      <div class="content-bottom-buttons">
        <button type="button" class="login-button" onClick="onClickLoginButton()">
          로그인
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function onClickLoginButton() {
	location.href = "/front2/users/login";
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
  padding: 100px 0 300px;
}
.content-container .content-inner .content-top-area {
  text-align: center;
  padding-bottom: 50px;
}
.content-container .content-inner .content-top-area .content-top-title {
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 40px;
}
.content-container .content-inner .content-bottom-area {
  width: 900px;
  border-top: 1px solid #e5e5e5;
  padding-top: 90px;
  margin: 0 auto;
}
.content-container .content-inner .content-bottom-area .content-bottom-title {
  padding-left: 12px;
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 50px;
}
.content-container .content-inner .content-bottom-area .content-bottom-content {
  border-top: 2px solid #9a9a9a;
  border-bottom: 2px solid #9a9a9a;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px 0;
  margin-bottom: 135px;
  font-size: 18px;
  text-align: center;
}
.content-container .content-inner .content-bottom-area .content-bottom-buttons {
  display: flex;
  justify-content: center;
}
.content-container .content-inner .content-bottom-area .content-bottom-buttons .login-button {
  width: 250px;
  padding: 15px 0;
  background-color: #6738ff;
  color: #fff;
  border: 2px solid #6738ff;
  font-size: 18px;
  cursor: pointer;
}

@media (max-width: 990px) {
  .content-container .content-inner .content-bottom-area {
    width: auto;
  }
  .content-container .content-inner .content-top-area .content-top-title {
    font-size: 24px;
    margin-bottom: 18px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-title {
    font-size: 20px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-content {
    font-size: 14px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-buttons {
    flex-direction: column;
    align-items: center;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-buttons .login-button {
    width: 200px;
    font-size: 17px;
  }
}
</style>