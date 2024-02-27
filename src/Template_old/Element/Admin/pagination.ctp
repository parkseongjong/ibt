<?php 
	$paginator	=	$this->Paginator;
	if ( !isset($queryString) )
		$queryString = '';
	
	$options = array('class'=>'p_numbers','separator'=> '');
	 
?>
 
<?php
$pages	=	$paginator->counter(array('format' => '%pages%')); 
if($pages>1){
 ?>
<div><ul class="pagination pull-right">
<?php 
	echo $paginator->first(__('First', true), array('id'=> 'p_first','tag'=>'li'), null, array('class'=>''));
	echo $paginator->prev(__("&larr; Previous", true), array('id'=> 'p_prev','tag'=>'li','escape'=>false), null, array('class'=>'previous p_numbers','escape'=>false));
	echo $paginator->numbers($options,array('tag'=>'li'));
	echo $paginator->next(__("Next &rarr;", true), array('id'=> 'p_next','tag'=>'li','escape'=>false), null, array('class'=>'current','escape'=>false));
	echo $paginator->last(__('Last', true), array('id'=> 'p_last','tag'=>'li'), null, array('class'=>''));
?></ul>
</div>
<?php 
}
?>
