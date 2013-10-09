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
        echo $this->Form->input('user_id');
        echo $this->Form->input('malemembers', array( 'label' => 'Number of Male Volunteers'));
        echo $this->Form->input('femalemembers', array( 'label' => 'Number of Female Volunteers'));
        ?>
        <div id="room_list">
            <?php
            echo $this->Form->input('room_allocated', array('type' => 'select', "options" => $availability['room_list'], 'label' => 'Room Allocation'));
            ?>
        </div>
        <?php
        echo $this->Form->input('keyissued', array('label' => 'Key Issued?'));
        echo $this->Form->input('amountdue', array('label' => 'Amount Due'));
        echo $this->Form->input('payed', array('label' => 'Payed?'));
        echo $this->Form->input('comments');
        echo $this->Form->input('approved', array('label' => 'Approved?'));

        $ajaxFields = array('#BookingCheckinDay', '#BookingCheckinMonth', '#BookingCheckinYear',
        '#BookingCheckoutDay', '#BookingCheckoutMonth', '#BookingCheckoutYear');

        foreach($ajaxFields as $field) {

        $this->Js->get($field)->event('change', $this->Js->request(
        array('controller' => 'bookings', 'action' => 'ajaxGenerateAvailableRoomList'),
        array(
        'update' => '#room_list',
        'async' => true,
        'dataExpression' => true,
        'method' => 'post',
        'data' => $this->Js->serializeForm(array('isForm' => false, 'inline' => true))
        ) ) );

        }

        echo $this->Js->writeBuffer();
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Booking.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Booking.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Bookings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Rooms'), array('controller' => 'rooms', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Room'), array('controller' => 'rooms', 'action' => 'add')); ?> </li>
	</ul>
</div>
