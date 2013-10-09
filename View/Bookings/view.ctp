<div class="bookings view">
<h2><?php  echo __('Booking'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Checkin'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['checkin']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Checkout'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['checkout']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($booking['User']['id'], array('controller' => 'users', 'action' => 'view', $booking['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Room Allocated'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['room_allocated']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Keyissued'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['keyissued']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amountdue'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['amountdue']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Payed'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['payed']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($booking['Booking']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Booking'), array('action' => 'edit', $booking['Booking']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Booking'), array('action' => 'delete', $booking['Booking']['id']), null, __('Are you sure you want to delete # %s?', $booking['Booking']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bookings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Booking'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
