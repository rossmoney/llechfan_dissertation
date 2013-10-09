<?php echo $this->Form->create('Booking'); ?>
	<fieldset>
		<legend><?php echo __('Make Your Booking'); ?></legend>
	<?php
	    $user = $this->Session->read('Auth.User');
	    if($savedBooking)
	    {
            echo $this->Form->input('checkin', array('label' => 'Check In Date',
        'selected'=>$savedBooking['Booking']['checkin']['year']."-".$savedBooking['Booking']['checkin']['month']."-".$savedBooking['Booking']['checkin']['day'],
        'default' => date('Y-m-d'), 'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
            echo $this->Form->input('checkout', array('label' => 'Check Out Date',
        'selected'=>$savedBooking['Booking']['checkout']['year']."-".$savedBooking['Booking']['checkout']['month']."-".$savedBooking['Booking']['checkout']['day'],
        'default' => date('Y-m-d'), 'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $user['id']));
            echo $this->Form->input('malemembers', array('value' => $savedBooking['Booking']['malemembers'], 'label' => 'Number of Male Volunteers'));
            echo $this->Form->input('femalemembers', array('value' => $savedBooking['Booking']['femalemembers'], 'label' => 'Number of Female Volunteers'));
	        echo $this->Form->input('comments', array('value' => $savedBooking['Booking']['comments']));
        } else {
            echo $this->Form->input('checkin', array('label' => 'Check In Date', 'default' => date('Y-m-d'),
            'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
            echo $this->Form->input('checkout', array('label' => 'Check Out Date', 'default' => date('Y-m-d', strtotime("+1 day")),
            'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
		    echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $user['id']));
            echo $this->Form->input('malemembers', array( 'value' => '0', 'label' => 'Number of Male Volunteers'));
            echo $this->Form->input('femalemembers', array( 'value' => '0', 'label' => 'Number of Female Volunteers'));
            echo $this->Form->input('comments');
        }

	?>
	</fieldset>
<?php echo $this->Form->end(__('Book')); ?>
<!--
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Bookings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Allocations'), array('controller' => 'allocations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Allocation'), array('controller' => 'allocations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Rooms'), array('controller' => 'rooms', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Room'), array('controller' => 'rooms', 'action' => 'add')); ?> </li>
	</ul>
</div>
-->