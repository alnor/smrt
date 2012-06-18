<?php 

if (isset($this->var["message"])) { 
	
	echo $this->var["message"];

} else { ?>

	Test: {test} <br />
	Name: {name}<br /><br />

<?php } ?>

<a href="/">Back</a>