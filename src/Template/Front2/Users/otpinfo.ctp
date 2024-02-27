<div class="content-container">
  <div class="content-inner">
    <div class="otp-auth-top-title">
      OTP 인증
    </div>
    <div class="step-box">
      <div class="step-1">
        <div class="step-title">
          STEP 1
        </div>
        <div class="step-subtitle">
          OTP앱을 스마트폰에 다운로드 받는다.
        </div>
        <div class="store-wrap">
          <div class="store-content">
            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=ko&gl=US" target="_blank" rel="noopener">
              <img src="/wb/imgs/google-store-badge.png" alt="google-store" class="store-badge">
            </a>
            <div class="store-text">
              안드로이드는 Google OTP를 검색 후 다운로드 하세요
            </div>
          </div>
          <div class="store-content">
            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank" rel="noopener">
              <img src="/wb/imgs/apple-store-badge.png" alt="apple-store" class="store-badge">
            </a>
            <div class="store-text">
              아이폰은 Google Authenticator를 검색 후 다운로드 하세요.
            </div>
          </div>
        </div>
      </div>
      <div class="step-2">
        <div class="step-title">
          STEP 2
        </div>
        <div class="step-subtitle">
          다운로드 받은 후, 아래 QR코드를 스캔하거나 비밀키를 입력하세요.
        </div>
        <div class="step-2-qr-code-wrap">
          <img src="<?= $googleAuthUrl; ?>" alt="qr-code" class="step-2-qr-code">
        </div>
        <div class="private-key-container">
          <div class="private-key-input-group">
            <div class="private-key-text" id="copy-target">
              <?= $secret; ?>
            </div>
            <div class="private-key-copy" id="copy-trigger">
              비밀키 복사
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="step-desc-container">
      <div class="step-desc-item">
        <div class="step-desc-item-left">
          STEP 1)
        </div>
        <div class="step-desc-item-right">
          Google OTP를 설치합니다.
        </div>
      </div>
      <div class="step-desc-item">
        <div class="step-desc-item-left">
          STEP 2)
        </div>
        <div class="step-desc-item-right">
          설치한 어플을 실행하여 [바코드 스캔] 또는 [직접 입력]을 선택하여 코드를 입력합니다.
        </div>
      </div>
      <div class="step-desc-item">
        <div class="step-desc-item-left">
          STEP 3)
        </div>
        <div class="step-desc-item-right">
          OTP 생성완료
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('#copy-trigger').addEventListener('click', function() {
    const copyTarget = document.querySelector('#copy-target');

    const range = document.createRange();
    range.selectNode(copyTarget);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range); // to select text
    document.execCommand("copy");
    // window.getSelection().removeAllRanges();// to deselect
  });
});
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
  padding: 100px 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.content-container .content-inner .otp-auth-top-title {
  font-size: 24px;
  font-weight: 700;
  color: #2c2c2c;
  margin-bottom: 40px;
}
.content-container .content-inner .step-box {
  border: 2px solid #f0f0f0;
  padding: 30px 40px;
  max-width: 620px;
  margin-bottom: 30px;
  box-sizing: border-box;
}
.content-container .content-inner .step-box .step-1 {
  border-bottom: 1px solid #bfbfbf;
  padding-bottom: 60px;
  margin-bottom: 50px;
}
.content-container .content-inner .step-box .step-1 .step-title,
.content-container .content-inner .step-box .step-2 .step-title {
  color: #6738ff;
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 15px;
}
.content-container .content-inner .step-box .step-1 .step-subtitle,
.content-container .content-inner .step-box .step-2 .step-subtitle {
  color: #0f0d0d;
  font-size: 19px;
  margin-bottom: 60px;
}
.content-container .content-inner .step-box .step-1 .store-wrap {
  display: flex;
}
.content-container .content-inner .step-box .step-1 .store-wrap .store-content {
  flex: 1;
  text-align: center;
}
.content-container .content-inner .step-box .step-1 .store-wrap .store-content:first-child {
  margin-right: 35px;
}
.content-container .content-inner .step-box .step-1 .store-wrap .store-content .store-badge {
  margin-bottom: 20px;
  width: 250px;
}
.content-container .content-inner .step-box .step-1 .store-wrap .store-content .store-text {
  font-size: 16px;
  color: #656565;
  line-height: 1.5;
}
.content-container .content-inner .step-box .step-2 .step-subtitle {
  margin-bottom: 30px;
}
.content-container .content-inner .step-box .step-2 .step-2-qr-code-wrap {
  width: 100%;
  text-align: center;
  margin-bottom: 20px;
}
.content-container .content-inner .step-box .step-2 .step-2-qr-code-wrap .step-2-qr-code {}
.content-container .content-inner .step-box .step-2 .private-key-container {}
.content-container .content-inner .step-box .step-2 .private-key-container .private-key-input-group {
  display: flex;
}
.content-container .content-inner .step-box .step-2 .private-key-container .private-key-text {
  border: 1px solid #e5e5e5;
  border-right: none;
  font-size: 18px;
  padding: 15px;
  flex: 1;
}
.content-container .content-inner .step-box .step-2 .private-key-container .private-key-copy {
  background-color: rgba(43, 51, 220, 0.99);
  color: #fff;
  font-size: 20px;
  padding: 15px;
  width: 20%;
  text-align: center;
  cursor: pointer;
}
.content-container .content-inner .step-desc-container {
  font-size: 18px;
  max-width: 620px;
}
.content-container .content-inner .step-desc-container .step-desc-item {
  display: flex;
}
.content-container .content-inner .step-desc-container .step-desc-item:not(:last-of-type) {
  margin-bottom: 15px;
}
.content-container .content-inner .step-desc-container .step-desc-item .step-desc-item-left {
  width: 15%;
}
.content-container .content-inner .step-desc-container .step-desc-item .step-desc-item-right {
  flex: 1;
  word-break: keep-all;
}

@media (max-width: 990px) {
  .wrapper {}
  .content-container {}
  .content-container .content-inner {}
  .content-container .content-inner .otp-auth-top-title {}
  .content-container .content-inner .step-box {
    width: 100%;
    margin-left: 10px;
    margin-right: 10px;
    padding: 30px 20px;
  }
  .content-container .content-inner .step-box .step-1 {}
  .content-container .content-inner .step-box .step-1 .step-title,
  .content-container .content-inner .step-box .step-2 .step-title {}
  .content-container .content-inner .step-box .step-1 .step-subtitle,
  .content-container .content-inner .step-box .step-2 .step-subtitle {
    font-size: 14px;
  }
  .content-container .content-inner .step-box .step-1 .store-wrap {
    flex-direction: column;
  }
  .content-container .content-inner .step-box .step-1 .store-wrap .store-content {}
  .content-container .content-inner .step-box .step-1 .store-wrap .store-content:first-child {
    margin-right: 0;
    margin-bottom: 20px;
  }
  .content-container .content-inner .step-box .step-1 .store-wrap .store-content .store-badge {
    width: 100%;
    max-width: 250px;
  }
  .content-container .content-inner .step-box .step-1 .store-wrap .store-content .store-text {
    font-size: 13px;
  }
  .content-container .content-inner .step-box .step-2 .step-subtitle {}
  .content-container .content-inner .step-box .step-2 .step-2-qr-code-wrap {}
  .content-container .content-inner .step-box .step-2 .step-2-qr-code-wrap .step-2-qr-code {}
  .content-container .content-inner .step-box .step-2 .private-key-container {}
  .content-container .content-inner .step-box .step-2 .private-key-container .private-key-input-group {
    flex-direction: column;
  }
  .content-container .content-inner .step-box .step-2 .private-key-container .private-key-text {
    font-size: 12px;
    border-right: 1px solid #e5e5e5;
  }
  .content-container .content-inner .step-box .step-2 .private-key-container .private-key-copy {
    font-size: 14px;
    width: auto;
  }
  .content-container .content-inner .step-desc-container {
    padding: 0 10px;
    font-size: 14px;
  }
  .content-container .content-inner .step-desc-container .step-desc-item {
    flex-direction: column;
  }
  .content-container .content-inner .step-desc-container .step-desc-item:not(:last-of-type) {}
  .content-container .content-inner .step-desc-container .step-desc-item .step-desc-item-left {
    width: auto;
  }
  .content-container .content-inner .step-desc-container .step-desc-item .step-desc-item-right {}
}
</style>