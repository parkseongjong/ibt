<?php
namespace App\View\Helper;

use Cake\View\Helper;


class UtilityHelper extends Helper
{
	
	function timeFunc($dateTime)
	{
		
		
		$difference = strtotime(date('Y-m-d H:i:s')) -  strtotime($dateTime);
		if($difference < 3570) $output = round($difference / 60).' minutes ago ';
		elseif ($difference < 86370) $output = round($difference / 3600).' hours ago';
		elseif ($difference < 604770) $output = round($difference / 86400).' days ago';
		elseif ($difference < 31535970) $output = round($difference / 604770).' week ago';
		else $output = round($difference / 31536000).' years ago';
		return $output;
	}
}
?>
