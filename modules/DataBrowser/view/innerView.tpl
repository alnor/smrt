<div id="tabs">
<ul>
<?php	
	foreach($this->var["tabs"] as $k=>$tb){
		echo "<li><a href='/".$tb["href"]."'>", $tb["name"],"</a></li>";
	}
	
?>
</ul>
</div>
<?php	
	print_r($this->var["tabs"]);
	foreach($this->var["data"] as $k=>$data){
		print_r($data);
	}
	
?>

