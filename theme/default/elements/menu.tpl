<ul>
	<?php foreach ($this->var["mainMenu"] as $menu) { ?>
		<li><a href="<?php echo $menu["href"]; ?>" id="<?php echo $menu["id"]; ?>"><?php echo $menu["name"]; ?></a></li>
	<?php } ?>
</ul>