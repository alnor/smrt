<?php 
if (isset($this->var["message"])) { 	
	echo $this->var["message"];
}
?>

<form method="post">
	<input type="text" name="form[name]" />
	<select name="form[service_id]">
		<?php foreach($this->var["services"] as $key=>$service){ ?>
		<option value="<?php echo $service["id"]; ?>"><?php echo $service["name"]; ?></option>
		<?php } ?>
	</select>
	<input type="submit" value="Ok" />
</form>