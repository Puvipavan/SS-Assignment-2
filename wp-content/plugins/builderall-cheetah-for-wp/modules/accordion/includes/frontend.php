<?php 
$accordion_node_id = "ba-node-$id"; 
?>

<section class="ba-module__accordion <?php echo $accordion_node_id; ?>">
<?php

if (!isset($settings->faq_mode) || $settings->faq_mode == 1): 

?>
<div class="accordion__content">
	<?php
	foreach ($settings->items as $key => $item) : 
		$module->accordion_item_template($item->title, $item->body, $item->dragdrop, $item->row_node_id, $id, $settings, $key);
	endforeach; 
	?>
</div>
<?php 

else: 
	
foreach ($settings->items as $key => $item) : ?>
	<div class="accordion__content">
		<?php $module->accordion_item_template($item->title, $item->body, $item->dragdrop, $item->row_node_id, $id, $settings, $key); ?>
	</div> <?php 
endforeach; 

endif;
?> 
</section>