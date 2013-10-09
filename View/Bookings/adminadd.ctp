<?php echo $this->Form->create('Booking'); ?>
	<fieldset>
		<legend><?php echo __('New Booking'); ?></legend>
	<?php
        echo $this->Form->input('checkin', array('label' => 'Check In Date', 'default' => date('Y-m-d'),
        'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
        echo $this->Form->input('checkout', array('label' => 'Check Out Date', 'default' => date('Y-m-d', strtotime("+1 day")),
        'minYear' => date('Y'), 'maxYear' => date('Y', strtotime("+5 years"))));
	    echo $this->Form->input('user_id');
        echo $this->Form->input('malemembers', array( 'value' => '0','label' => 'Number of Male Volunteers'));
        echo $this->Form->input('femalemembers', array( 'value' => '0','label' => 'Number of Female Volunteers'));
        ?>
        <div id="room_list">
            <?php
            echo $this->Form->input('room_allocated', array('type' => 'select', "options" => $availability['room_list'], 'label' => 'Room Allocation'));
            ?>
        </div>
        <?php
         echo $this->Form->input('comments');

        $ajaxFields = array('#BookingCheckinDay', '#BookingCheckinMonth', '#BookingCheckinYear',
        '#BookingCheckoutDay', '#BookingCheckoutMonth', '#BookingCheckoutYear',
        '#BookingMalemembers','#BookingFemalemembers');

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
<?php echo $this->Form->end(__('Book')); ?>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Bookings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li> </li>
	</ul>
</div>