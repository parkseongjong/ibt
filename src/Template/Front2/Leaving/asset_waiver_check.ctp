<?php echo $this->Form->create('',array('method'=>'post','id'=>'upload_form','enctype'=>'multipart/form-data'));?>
<div class="content-container">
	<div class="content-inner">
		<table>
			<tr>
				<th>업로드한 이미지</th>
				<th>업로드한 날짜</th>
				<th>미승인 사유</th>
				<th>수정</th>
			</tr>
		</table>
	</div>
</div>
<?php echo $this->Form->end();?>
test

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