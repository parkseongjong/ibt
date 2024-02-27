<?php echo $this->Form->create('',array('method'=>'post','id'=>'upload_form','enctype'=>'multipart/form-data'));?>
<div class="content-container">
	<div class="content-inner">
		<div class="content-top-area">
			<div class="content-title"> 자산포기각서 업로드 페이지 </div>
			<div class="content-subtitle"> 자산포기각서 스캔파일을 올려주세요! </div>
			<div class="content-subtitle2"> 자필 작성 후 스캔하여 자산포기각서 파일을
				<br /> 업로드 해주시면 담당 부서 확인후 탈퇴처리가 완료됩니다.
				<br /> 탈퇴 시까지 시간 소요될 수 있으니 양해 부탁드립니다. </div>
		</div>
		<div class="content-bottom-area">
			<div class="file-input-area">
				<input type="file" id="upload-input" class="upload-input" name="asset_waiver_img">
				<label for="upload-input" class="upload-label-input">파일을 등록 해주세요</label>
				<label for="upload-input" class="upload-label">파일 찾기</label>
			</div>
			<p class="text-pink" id="error_msg" style="display:none;">자산포기각서를 업로드해주세요</p>
			<?= $this->Flash->render(); ?>
			<button type="button" class="complete-button" onClick="onClickCompleteDeleteAccount()"> 탈퇴 요청 완료 </button>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
<script>
	const error_msg = document.getElementById('error_msg');
	document.addEventListener('DOMContentLoaded', function() {
		const uploadInput = document.querySelector('#upload-input');

		uploadInput.addEventListener('change', function() {
			//console.log('uploadInput change', this.files);
			const uploadLabelInput = document.querySelector('.upload-label-input');
			const uploadFile = this.files[0];
			if (!uploadFile) {
				uploadLabelInput.innerText = '파일을 등록 해주세요';
			    uploadLabelInput.style.color = null;
			    return;
			};
			const filename = uploadFile.name;

			uploadLabelInput.innerText = filename;
			uploadLabelInput.style.color = '#000';
			error_msg.style.display = 'none';
		});
	});

function onClickCompleteDeleteAccount() {
	const uploadFile = document.querySelector('#upload-input').files[0];
//	console.log('file? ', uploadFile);
	if (!uploadFile) {
		error_msg.style.display = 'block';
		return;
	}
	$('#upload_form').submit();
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
.content-container .content-inner .content-top-area {
  margin-bottom: 50px;
}
.content-container .content-inner .content-top-area .content-title {
  font-size: 40px;
  font-weight: 700;
  margin-bottom: 40px;
}
.content-container .content-inner .content-top-area .content-subtitle {
  font-size: 24px;
  margin-bottom: 30px;
}
.content-container .content-inner .content-top-area .content-subtitle2 {
  font-size: 24px;
  color: #535353;
}
.content-container .content-inner .content-bottom-area {}
.content-container .content-inner .content-bottom-area .file-input-area {
  width: 400px;
  margin: 0 auto;
  margin-bottom: 40px;
  display: flex;
  flex-direction: column;
  font-size: 20px;
}
.content-container .content-inner .content-bottom-area .file-input-area .upload-input {
  display: none;
}
.content-container .content-inner .content-bottom-area .file-input-area .upload-label-input {
  border: 2px solid #dadada;
  padding: 15px;
  margin-bottom: 10px;
  text-align: left;
  color: #c5c5c5;
}
.content-container .content-inner .content-bottom-area .file-input-area .upload-label {
  border: 2px solid #dadada;
  background-color: #f2f2f2;
  text-align: center;
  padding: 15px;
  color: #656565;
}
.content-container .content-inner .content-bottom-area .complete-button {
  background-color: #6738ff;
  font-size: 20px;
  color: #fff;
  width: 400px;
  border: none;
  padding: 15px;
  cursor: pointer;
}


@media (max-width: 990px) {
  .wrapper {}
  .content-container {}
  .content-container .content-inner {}
  .content-container .content-inner .content-top-area {}
  .content-container .content-inner .content-top-area .content-title {
    font-size: 24px;
  }
  .content-container .content-inner .content-top-area .content-subtitle {
    font-size: 20px;
  }
  .content-container .content-inner .content-top-area .content-subtitle2 {
    font-size: 18px;
  }
  .content-container .content-inner .content-bottom-area {}
  .content-container .content-inner .content-bottom-area .file-input-area {
    width: 220px;
    font-size: 16px;
  }
  .content-container .content-inner .content-bottom-area .file-input-area .upload-input {}
  .content-container .content-inner .content-bottom-area .file-input-area .upload-label-input {}
  .content-container .content-inner .content-bottom-area .file-input-area .upload-label {}
  .content-container .content-inner .content-bottom-area .complete-button {
    width: 220px;
    font-size: 16px;
  }
}
</style>