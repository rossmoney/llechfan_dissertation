<div class="bookings index">
	<h2><?php echo __('Your Bookings'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort( 'checkin', 'Check In Date'); ?></th>
			<th><?php echo $this->Paginator->sort( 'checkout', 'Check Out Date'); ?></th>
            <th><?php echo $this->Paginator->sort('malemembers', 'Males'); ?></th>
            <th><?php echo $this->Paginator->sort('femalemembers', 'Females'); ?></th>
			<th><?php echo $this->Paginator->sort('allocation_id', 'Room Allocation'); ?></th>
            <th><?php echo $this->Paginator->sort('keyissued', 'Key Issued?'); ?></th>
            <th><?php echo $this->Paginator->sort('amountdue'); ?></th>
            <th><?php echo $this->Paginator->sort('payed', 'Payed?'); ?></th>
            <th><?php echo $this->Paginator->sort('approved', 'Approved?'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user_bookings as $booking): ?>
	<tr>
		<td><?php echo h($booking['Booking']['checkin']); ?>&nbsp;</td>
		<td><?php echo h($booking['Booking']['checkout']); ?>&nbsp;</td>
        <td><?php echo h($booking['Booking']['malemembers']); ?>&nbsp;</td>
        <td><?php echo h($booking['Booking']['femalemembers']); ?>&nbsp;</td>
		<td>
            <?php if($booking['Booking']['room_allocated'] == 0 || $booking['Booking']['room_allocated'] == NULL) { echo "Not Assigned Yet"; } else { ?>
            <?php echo "#".$booking['Room']['number'] . " (" . $booking['Room']['description'] . ")(" . $booking['Room']['beds'] . " Beds)"; ?>
		    <?php } ?>
        </td>
        <td><?php echo h($booking['Booking']['keyissued'] ? 'Yes' : 'No'); ?>&nbsp;</td>
        <td><?php echo h("£" . number_format ( $booking['Booking']['amountdue'], 2) ); ?>&nbsp;</td>
        <td><?php echo h($booking['Booking']['payed'] ? 'Yes' : 'No'); ?>&nbsp;</td>
        <td><?php echo h($booking['Booking']['approved'] ? 'Yes' : 'No'); ?>&nbsp;</td>
		<td class="actions">
            <?php
            if($booking['Booking']['approved'] == 0) {
                echo $this->Html->link(__('Edit'), array('action' => 'useredit', $booking['Booking']['id']));
                echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $booking['Booking']['id']), null, __('Are you sure you want to delete booking on %s?', $booking['Booking']['checkin']));
            }
            ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Booking'), array('action' => 'add')); ?></li>
	</ul>
</div>
