<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Register'); ?></legend>
	<?php
		echo $this->Form->input('role_id', array('type' => 'hidden', 'value' => $role['Role']['id']));
		echo $this->Form->input('email');
		//echo $this->Form->input('password');
        echo $this->Form->input('passwd', array('type' => 'password', 'label' => 'Password'));
        echo $this->Form->input('passwd_confirm', array('type' => 'password', 'label' => 'Confirm Password'));
		echo $this->Form->input('firstname');
		echo $this->Form->input('surname');
        echo $this->Form->input('gender', array( 'options'=> array('m'=>'Male','f'=>'Female'),'type'=>'select', 'label'=>'Gender'));
        echo $this->Form->input('dob', array('label' => 'Date of Birth', 'default' => '1990-01-01', 'minYear' => '1950', 'maxYear' => date('Y', strtotime("-14 years"))));
		echo $this->Form->input('telno', array('label' => 'Telephone Number'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

