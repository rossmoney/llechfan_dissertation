<?php
echo '<p>Thankyou for your booking, ' . $user['firstname'] .  ' ' . $user['surname'].'</p>Booked For: ' .
                         date("l jS F Y", strtotime($dates[0]))." - ".date("l jS F Y", strtotime($dates[1]))
                         .'<br />Room Allocated: #'.$room['Room']['number'] . " (" . $room['Room']['description'] .
                     ")(" . $room['Room']['beds'] . ' Beds)</p><p>Male Members: ' . $savedBooking['Booking']['malemembers'] .
                     '<br />Female Members: ' . $savedBooking['Booking']['femalemembers'] . '</p><p>Amount Due: &pound;' .
                     $savedBooking['Booking']['amountdue'] . '</p><p>Comments: ' . $savedBooking['Booking']['comments'] . '</p>';
?>