<table width="100%" border=1>
	<tr>
<?php 

	foreach($this->var["columns"] as $k=>$column){
		echo "<td>", $column["Field"], "</td>";
	}	
?>
	</tr>	
	
<?php	
print_r($this->var["data"]);
	foreach($this->var["data"] as $k=>$data){
		foreach($data as $key=>$field){
			echo "<td>", $field, "</td>";
		}
	}
	
?>
</table>
