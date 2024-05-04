<?php 

if ($module->get_data()):
$node_selector = "ba-node-$id";
?>

<div class="ba-module__timer <?php echo $node_selector?>">

	<div class="timer__counter">
		<div class="timer__box">
			<output class="timer__output__days">00</output>
			<span class="timer__label"><?php echo _e('Days', 'ba-cheetah') ?></span>
		</div>

		<span class="timer__colon">:</span>

		<div class="timer__box">
			<output class="timer__output__hours">00</output>
			<span class="timer__label"><?php echo _e('Hours', 'ba-cheetah') ?></span>
		</div>

		<span class="timer__colon">:</span>

		<div class="timer__box">
			<output class="timer__output__minutes">00</output>
			<span class="timer__label"><?php echo _e('Minutes', 'ba-cheetah') ?></span>
		</div>

		<span class="timer__colon">:</span>

		<div class="timer__box">
			<output class="timer__output__seconds">00</output>
			<span class="timer__label"><?php echo _e('Seconds', 'ba-cheetah') ?></span>
		</div>
	</div>

	<div class="timer__message">
		<?php echo wp_kses_post($settings->action_message) ?>
	</div>
</div>

<?php 
else:
	echo _e('Please set a time', 'ba-cheetah');
endif;