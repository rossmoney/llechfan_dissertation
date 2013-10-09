<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('role_id', array('label' => 'Role'));
		echo $this->Form->input('email');
		//echo $this->Form->input('password');
        echo $this->Form->input('passwd', array('type' => 'password', 'label' => 'Password'));
        echo $this->Form->input('passwd_confirm', array('type' => 'password', 'label' => 'Confirm Password'));
		echo $this->Form->input('firstname');
		echo $this->Form->input('surname');
        echo $this->Form->input('gender', array( 'options'=> array('m'=>'Male','f'=>'Female'),'type'=>'select', 'label'=>'Gender'));
        echo $this->Form->input('dob', array('label' => 'Date of Birth', 'default' => '1990-01-01', 'minYear' => '1950', 'maxYear' => date('Y', strtotime("-14 years"))));
		echo $this->Form->input('telno', array('label' => 'Telephone Number'));
		echo $this->Form->input('disabled', array('label' => 'Disable User?'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
