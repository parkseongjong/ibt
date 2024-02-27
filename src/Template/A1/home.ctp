
	<div class="container"  style="min-height: 500px;">

	<div class="custom_frame" >

        <div style="padding: 10px;">
        <?php
       		echo "<Br>".$title."<br><br><br><Br>";
			foreach($viewData1 as $row){
			
		//	echo $row->uid."<Br>";
		}
			?>
			
			<table width="678" cellpadding="4" cellspacing="1" bgcolor="#AFAFAF">
				<tr>
					<td bgcolor="#DBDCC6"><strong>아이디</strong></td>
					<td bgcolor="#DBDCC6"><strong>성함</strong></td>
					<td bgcolor="#DBDCC6"><strong>날짜</strong></td>
				</tr>
				 <?php
					foreach($viewData1 as $row){
				?>
				<tr>
					<td bgcolor="#FFFFFF"><a href="/a1/view1/<?=$row->idx?>"><?=$row->uid?></a></td>
					<td bgcolor="#FFFFFF"><?=$row->uname?></td>
					<td bgcolor="#FFFFFF"><?=$row->created?></td>
				</tr>
				<?php }?>
				
			</table>
			     <span>
			     	
			     	<button name="a" value="등록하기" onclick="location.href='/a1/write1'">등록하기 </button>
			     </span>
		
        </div>

	</div>
</div>