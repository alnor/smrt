<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title>
		{title}
	</title>
</head>

<body>
	<h1>Index tpl</h1>
	
	<hr />
	
	<ul>
	<?php 
		foreach($this->var["menu1"] as $key=>$val){
			echo "<li><a href='".$key."'>".$val."</a></li>";
		}
	?>
	</ul>
		
	<hr />
	
	{content}
	<hr />
	[element=menu]
</body>
</html>