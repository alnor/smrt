<form method="post" action="/projects/result">
	<input type="text" name="form[test]" />
	<input type="text" name="form[name]" />
	<input type="submit" value="Ok" />
</form>

<?php 

	foreach($this->var["test"] as $k=>$v){
		echo "ID:", $v["id"], "<br />";
		echo "Name:", $v["name"], "<br />";
	}
?>