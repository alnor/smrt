<table width="100%" border=1>
	<tr>
<?php 

	foreach($this->var["columns"] as $k=>$column){
		echo "<td>", \core\Smrt_Lang::get("db_field", $column), "</td>";
	}	
?>
	</tr>	
		
<?php	

	foreach($this->var["data"] as $k=>$data){
		echo "<tr>";
		foreach($data as $key=>$field){
			echo "<td>", $field, "</td>";
		}
		echo "</tr>";
	}
	
?>
</table>
