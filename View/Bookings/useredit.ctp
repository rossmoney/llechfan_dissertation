<div class="bookings form">
<?php echo $this->Form->create('Booking'); ?>
	<fieldset>
		<legend><?php echo __('Edit Booking'); ?></legend>
	<?php
	    echo $this->Form->input('id', array('type'=>'hidden'));
        echo $this->Form->input('checkin', array('label' => 'Check In Date', 'default' => date('Y-m-d'),
        'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
        echo $this->Form->input('checkout', array('label' => 'Check Out Date', 'default' => date('Y-m-d', strtotime("+1 day")),
        'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
        echo $this->Form->input('user_id', array('type'=>'hidden'));
        echo $this->Form->input('malemembers', array( 'label' => 'Number of Male Volunteers'));
        echo $this->Form->input('femalemembers', array('label' => 'Number of Female Volunteers'));
        echo $this->Form->input('comments');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Booking.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Booking.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Bookings'), array('action' => 'user')); ?></li>
	</ul>
</div>
