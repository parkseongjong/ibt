
	<div class="container"  style="min-height: 500px;">

	<div class="custom_frame" >

        <div style="padding: 10px;">

			
			<table width="678" cellpadding="4" cellspacing="1" bgcolor="#AFAFAF">
				<tr>
				  <td width="97" bgcolor="#DBDCC6"><b>아이디</b></td>
				  <td width="560" bgcolor="#FFFFFF">&nbsp;<?=$board->idx?></td>
			  </tr>
				<tr>
					<td bgcolor="#DBDCC6"><b>성함</b></td>
					<td bgcolor="#FFFFFF">&nbsp;<?=$board->uid?></td>
				</tr>

				<tr>
					<td bgcolor="#DBDCC6"><b>비밀번호</b></td>
					<td bgcolor="#FFFFFF">&nbsp;<?=$board->uname?></td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="#FFFFFF">&nbsp;<button name="a" value="수정하기 " onclick="location.href='/a1/edit1/<?=$board->idx?>'">수정하기</button> &nbsp;&nbsp;
					<button name="a" value="삭제하기" onclick="location.href='/a1/del1/<?=$board->idx?>'">삭제하기 </button>
					</td>					
			  </tr>
	
				
			</table>
		
        </div>

	</div>
</div>