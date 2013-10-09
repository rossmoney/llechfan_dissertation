<h2>Confirm Your Booking</h2>

<p>Volunteer: <?php echo $user['User']['firstname']." ".$user['User']['surname']; ?><br />
Booked For: <?php echo date("l jS F Y", strtotime($dates[0]))." - ".date("l jS F Y", strtotime($dates[1])); ?><br />
Room Allocated: <?php echo "#".$room['Room']['number'] . " (" . $room['Room']['description'] .
                        ")(" . $room['Room']['beds'] . " Beds)"; ?></p>
<p>Male Members: <?php echo $savedBooking['Booking']['malemembers']; ?><br />
Female Members: <?php echo $savedBooking['Booking']['femalemembers']; ?></p>

<p>Amount Due: &pound;<?php echo $savedBooking['Booking']['amountdue']; ?></p>
<p>Comments: <?php echo $savedBooking['Booking']['comments']; ?></p>
<br />
<p>Are you sure you want to make this booking?</p>
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('Change Booking'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('Save Booking'), array('action' => 'complete')); ?></li>
    </ul>
</div>
