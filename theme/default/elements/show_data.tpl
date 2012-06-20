<table width="100%" border=1>
<?php 
	
	foreach($this->var["show_data"] as $data){
		echo "<tr>";
		echo "<td>", $data["id"], "</td>";
		echo "<td><a href='/projects/view/id/".$data["id"]."'>", $data["name"], "</a></td>";
		echo "<td>", $data["service_name"], "</td>";
		echo "<td>", $data["user_name"], "</td>";
		echo "<td>", $data["created_on"], "</td>";
		echo "</tr>";
	}
	
?>
</table>