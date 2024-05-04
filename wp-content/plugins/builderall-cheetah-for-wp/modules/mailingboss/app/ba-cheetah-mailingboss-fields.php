<?php

class BACheetahMailingbossFields
{	

	/**
	 * Settings of a element
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Fields of a list
	 *
	 * @var array
	 */
	private $fields;

	public function __construct(stdClass $settings, $fields)
	{
		$this->fields = $fields;
		$this->settings = $settings;

		if (!isset($settings->display_labels)) {
			$this->settings->display_labels = 'yes';
		}

		if (!isset($settings->placeholder_content)) {
			$this->settings->placeholder_content = 'label';
		}
	}

	/**
	 * Render a mailingboss form
	 *
	 * @return void
	 */
	public function render() {
		foreach ($this->fields as $field) {

			$field['placeholder'] = array(
				'none' => '',
				'label' => $field['label'],
				'help_text' => $field['help_text']
			)[$this->settings->placeholder_content];

			$this->renderField($field);
		}
	}


	/**
	 * renderField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function renderField($field)
	{
		$type = $field['type']['identifier'];
		$method = $type . 'Field';

		if (method_exists(__CLASS__, $method)) { ?>
			<div class="mailingboss-form-group <?= $field['visibility'] === 'hidden' ? 'mailingboss-form-group-hidden' : ''; ?>">
				<label class="mailingboss-label">
					<?php 
					if ($this->settings->display_labels == 'yes' /*|| !in_array($type, ['text', 'textarea'])*/) :
						echo $field['label'];
						if($field['required'] === 'yes'): ?>
							<span class="mailingboss-required">*</span> <?php 
						endif; 
					endif;
					?>
				</label>
				<?php $this->$method($field); ?>
			</div>
		<?php } else {
			echo "Render method $method not exits<br>";
		}
	}

	/**
	 * textField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function textField($field) { ?>
		<input 
			type="text"
			class="mailingboss-form-control"
			name="<?= $field['tag']; ?>"
			placeholder="<?= $field['placeholder']; ?>"
			value="<?= $field['default_value']; ?>"
			<?= $field['required'] === 'yes' ? 'required' : ''; ?> 
		/>
	<?php }
	
	/**
	 * dropdownField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function dropdownField($field) {
		$options = is_array($field['options']) ? $field['options'] : array(); 
	?>
		<select class="mailingboss-form-control" name="<?= $field['tag']; ?>" <?= $field['required'] === 'yes' ? 'required' : ''; ?>>
			<?php foreach($options as $option) : ?>
			<option value="<?= $option['value']; ?>" <?php selected($field['default_value'], $option['value']); ?>><?= $option['name']; ?></option>
			<?php endforeach; ?>
		</select>
	<?php
	}
	
	/**
	 * checkboxField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function checkboxField($field) {
		$options = is_array($field['options']) ? $field['options'] : array(); 
	?>
		<?php foreach($options as $option) : ?>
		<label class="mailingboss-cr-label">
			<input type="checkbox" name="<?= $field['tag']; ?>[]" value="<?= $option['value']; ?>" <?= $field['required'] === 'yes' ? 'required' : ''; checked($field['default_value'], $option['value']); ?>/> <?= $option['name']; ?> <br />		
		</label>
		<?php endforeach; ?>
	<?php
	}
	
	/**
	 * radioField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function radioField($field) {
		$options = is_array($field['options']) ? $field['options'] : array(); 
	?>
		<?php foreach($options as $option) : ?>
		<label class="mailingboss-cr-label">
			<input type="radio" name="<?= $field['tag']; ?>" value="<?= $option['value']; ?>" <?= $field['required'] === 'yes' ? 'required' : ''; ?>/><?= $option['name']; ?> <br />
		</label>
		<?php endforeach; ?>
	<?php
	}
	
	/**
	 * multiselectField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function multiselectField($field) {
		$options = is_array($field['options']) ? $field['options'] : array(); 
	?>
		<select class="mailingboss-form-control" name="<?= $field['tag']; ?>[]" <?= $field['required'] === 'yes' ? 'required' : ''; ?> multiple="multiple">

			<?php foreach($options as $option) : ?>
			<option value="<?= $option['value']; ?>" <?php selected($field['default_value'], $option['value']); ?>><?= $option['name']; ?></option>
			<?php endforeach; ?>

		</select>
	<?php
	}
	
	/**
	 * dateField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function dateField($field) {
	?>
		<input
			type="date"
			class="mailingboss-form-control"
			name="<?= $field['tag']; ?>"
			value="<?= $field['default_value']; ?>"
			<?= $field['required'] === 'yes' ? 'required' : ''; ?>
		/>
	<?php
	}
	
	/**
	 * datetimeField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function datetimeField($field) {
	?>
		<input
			type="datetime-local"
			class="mailingboss-form-control"
			name="<?= $field['tag']; ?>"
			value="<?= $field['default_value']; ?>"
			<?= $field['required'] === 'yes' ? 'required' : ''; ?> 
		/>
	<?php
	}
	
	/**
	 * textareaField
	 *
	 * @param  mixed $field
	 * @return void
	 */
	public function textareaField($field) {
	?>
		<textarea
			class="mailingboss-form-control"
			name="<?= $field['tag']; ?>"
			placeholder="<?= $field['placeholder'] ?>"
			<?= $field['required'] === 'yes' ? 'required' : ''; ?>>
			<?= $field['default_value']; ?>
		</textarea>
	<?php
	}
	
}
