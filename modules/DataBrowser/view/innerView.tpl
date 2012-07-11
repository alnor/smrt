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

	foreach($this->var["data"] as $k=>$data){

	}
	
?>

