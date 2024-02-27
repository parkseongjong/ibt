<div class="content-container">
	<div class="content-inner">
		<div class="content-title"> <img src="/wb/imgs/caution-icon.png" alt="caution" class="caution-icon"> <span>아직 회원님의 자산이 남아있어요!</span> </div>
		<div class="content-subtitle"> 자산포기각서 다운로드하여 자필 작성 후
			<br /> 스캔하여 자산포기각서 파일을
			<br /> 업로드 해주시면 탈퇴처리가 완료됩니다. </div>
		<div class="button-area">
			<button class="download-button" onClick="onClickDownload()">
				<span class="download-button-text">자산포기각서 다운로드</span>
				<img src="/wb/imgs/download-icon.png" alt="download" class="download-icon">
			</button>
			<button class="upload-button" onClick="onClickGoUpload()"> 자산포기각서 업로드 하러가기 </button>
		</div>
	</div>
</div>

<script>
function onClickDownload() {
	window.location.href="/front2/leaving/asset-waiver-download";
}

function onClickGoUpload() {
	window.location.href="/front2/leaving/asset-waiver-upload";
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
.content-container .content-inner .content-title .caution-icon {
  width: 70px;
}
.content-container .content-inner .content-subtitle {
  font-size: 24px;
  color: #535353;
  line-height: 1.5;
  margin-bottom: 120px;
}
.content-container .content-inner .button-area {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.content-container .content-inner .button-area .download-button,
.content-container .content-inner .button-area .upload-button {
  width: 500px;
  height: 64px;
  font-size: 22px;
  padding: 15px 0;
  text-align: center;
  border: none;
  cursor: pointer;
}
.content-container .content-inner .button-area .download-button {
  margin-bottom: 10px;
  color: #fff;
  background-color: #6738ff;
}
.content-container .content-inner .button-area .download-button .download-button-text {
  margin-right: 8px;
}
.content-container .content-inner .button-area .download-button .download-icon {}
.content-container .content-inner .button-area .upload-button {
  color: #6738ff;
  background-color: #fff;
  border: 2px solid #6738ff;
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
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .content-container .content-inner .content-title .caution-icon {
    width: 40px;
  }
  .content-container .content-inner .content-subtitle {
    font-size: 16px;
    padding: 0 5px;
    margin-bottom: 80px;
  }
  .content-container .content-inner .button-area .download-button,
  .content-container .content-inner .button-area .upload-button {
    width: 200px;
    height: 50px;
    padding: 15px 0;
    font-size: 12px;
  }
  .content-container .content-inner .button-area .download-button .download-icon {
    width: 20px;
  }
}
</style>