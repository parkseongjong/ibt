 <form action="/a1/add1" method="POST" name="form1">
 <input type="hidden" name="umod" value="1">
	<div class="container"  style="min-height: 500px;">

	<div class="custom_frame" >

        <div style="padding: 10px;">

			
			<table width="678" cellpadding="4" cellspacing="1" bgcolor="#AFAFAF">
				<tr>
				  <td width="97" bgcolor="#DBDCC6"><b>아이디</b></td>
				  <td width="560" bgcolor="#FFFFFF">&nbsp;<input type="text" name="uid" id="uid" style="width:300px;"></td>
			  </tr>
				<tr>
					<td bgcolor="#DBDCC6"><b>성함</b></td>
					<td bgcolor="#FFFFFF">&nbsp;<input type="text" name="uname" id="uname" style="width:300px;"></td>
				</tr>

				<tr>
					<td bgcolor="#DBDCC6"><b>비밀번호</b></td>
					<td bgcolor="#FFFFFF">&nbsp;<input type="text" name="upass" id="upass" style="width:300px;"></td>
				</tr>
	
				
			</table>
		
        </div>
        			     <span>
			     	
			     	<a href ="javascript:a1()">등록하기 </a>
			     </span>

	</div>
</div>
</form>
<script>
function a1(){
	if(form1.uid.value==""){
		alert('아이디를넣어주세요 ');
		form1.uid.focus();
		 return;
	}
	form1.submit();
}
</script>