<?php
App::uses('AppController', 'Controller');
/**
 * Bookings Controller
 *
 * @property Booking $Booking
 */
class BookingsController extends AppController {

    public $components = array('RequestHandler', 'Email');

    public function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Session->read('Auth.User');
        $this->Auth->allow('add', 'user', 'confirm', 'complete', 'ajaxGenerateAvailableRoomList', 'jsonAvailabilityData', 'ajaxAllocationInfo');
        $user_bookings = $this->Booking->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id'))));
        if($user_bookings != null)
        {
            $display = false;
            foreach($user_bookings as $booking)
            {
                if(@$this->params['pass']['0'] == $booking['Booking']['id'])
                {
                    $display = true;
                }
            }
            if($display)
            {
                $this->Auth->allow('useredit', 'delete');
            }
        }
        if($user['Role']['name'] == "Administrator")
        {
            $this->Auth->allow('adminadd');
        }
        if ($this->request->accepts('application/json')) {
            $this->RequestHandler->renderAs($this, 'json');
        }
    }
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Booking->recursive = 0;
        $this->set('bookings', $this->paginate());
    }

    public function user() {
        $user_bookings = $this->Booking->find('all', array('conditions' => array('user_id' =>
                                            $this->Session->read('Auth.User.id')), 'order' => array('Booking.checkin')));
        $this->Booking->recursive = 0;
        $this->set('user_bookings', $user_bookings, $this->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Booking->exists($id)) {
            throw new NotFoundException(__('Invalid booking'));
        }
        $options = array('conditions' => array('Booking.' . $this->Booking->primaryKey => $id));
        $this->set('booking', $this->Booking->find('first', $options));
    }

    function getDatesFromRange($startDate, $endDate)
    {
        $return = array($startDate);
        $start = $startDate;
        $i=1;
        if (strtotime($startDate) < strtotime($endDate))
        {
            while (strtotime($start) < strtotime($endDate))
            {
                $start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                $return[] = $start;
                $i++;
            }
        }

        return $return;
    }

    function cakeDateToString($cakedate)
    {
        return date("Y-m-d", strtotime(@$cakedate['year']."-".@$cakedate['month']."-".@$cakedate['day']));
    }

    function generateAvailableRoomList($rettype = false, $checkin = false, $checkout = false, $totalpeople = false)
    {
        if(!$checkin )
        {
            $booking = $this->data['Booking'];
            $booking['totalpeople'] = $booking['malemembers'] + $booking['femalemembers'];
            $bookingRange = $this->getDatesFromRange( $this->cakeDateToString($booking['checkin']),
                $this->cakeDateToString($booking['checkout']));
        } else {
            if($checkin == $checkout)
            {
                $bookingRange = array($checkin);
            } else {
                $bookingRange = $this->getDatesFromRange( $checkin, $checkout);
            }
            if($totalpeople)
            {
                $booking['totalpeople'] = $totalpeople;
            } else {
                $booking['totalpeople'] = 0;
            }
        }
        $this->loadModel('Room');
        $rooms = $this->Room->find('all');
        $room_list = array();
        $roomAvailability = array();
        foreach($rooms as $room)
        {
            $usable = true;
            $room_bookings = $this->Booking->find('all', array(
                'conditions' => array('room_allocated' => $room['Room']['id'])));
            $bedsTaken = array();
            $bedsAvailable = array();
            foreach($room_bookings as $room_booking)
            {
                 if($room_booking['Booking']['approved'])
                 {
                     $roomRange = $this->getDatesFromRange($room_booking['Booking']['checkin'], $room_booking['Booking']['checkout']);
                     foreach($bookingRange as $bookingDate)
                     {
                         if(!isset($bedsTaken[$bookingDate])) $bedsTaken[$bookingDate] = array();
                         foreach($roomRange as $roomDate)
                         {
                             if($bookingDate == $roomDate)
                             {
                                 //if(!in_array($room_booking['Booking']['user_id'], $bedsTaken[$bookingDate]))
                                 //{
                                 $totalPeopleBooking =  $room_booking['Booking']['malemembers'] + $room_booking['Booking']['femalemembers'];
                                 for($i = 0; $i < $totalPeopleBooking; $i++)
                                 {
                                    array_push($bedsTaken[$bookingDate], $room_booking['Booking']['user_id']);
                                 }
                                 //}
                             }
                         }
                     }
                 }
            }
            foreach($bookingRange as $bookingDate)
            {
                $roomAvailability[$bookingDate]['timeStamp'] = strtotime($bookingDate);
                if(!isset($roomAvailability[$bookingDate])) $roomAvailability[$bookingDate] = array();
                if(!isset($roomAvailability[$bookingDate]['bedsAvailable'])) $roomAvailability[$bookingDate]['bedsAvailable'] = 0;
                if(!isset($roomAvailability[$bookingDate]['bedsTaken'])) $roomAvailability[$bookingDate]['bedsTaken'] = 0;
                if(count(@$bedsTaken[$bookingDate]) > 0)
                {
                    $availableBeds = ($room['Room']['beds'] - count($bedsTaken[$bookingDate]));
                    if($availableBeds < $booking['totalpeople'] || $availableBeds == 0 ) $usable = false;
                    $roomAvailability[$bookingDate]['bedsTaken'] += count($bedsTaken[$bookingDate]);
                    $roomAvailability[$bookingDate]['bedsAvailable'] += $availableBeds;
                    $roomAvailability[$bookingDate]['rooms'][$room['Room']['id']]['roomUsable'] = $usable;
                } else {
                    $roomAvailability[$bookingDate]['bedsAvailable'] +=  $room['Room']['beds'];
                }
            }

            if($usable)
            {
                $room_list[$room['Room']['id']] = "#".$room['Room']['number'] . " (" .
                    $room['Room']['description'] . ")(" . $room['Room']['beds'] . " Beds)";
                $roomAvailability[$bookingDate]['rooms'][$room['Room']['id']]['roomUsable'] = $usable;
            }
        }
        foreach($bookingRange as $bookingDate)
        {
            $colourRating = "";
            if($roomAvailability[$bookingDate]['bedsAvailable'] <= 0)
            {
                $colourRating = "red";
            }
            else if($roomAvailability[$bookingDate]['bedsTaken'] > $roomAvailability[$bookingDate]['bedsAvailable'])
            {
                $colourRating = "yellow";
            } else {
                $colourRating = "green";
            }
            $roomAvailability[$bookingDate]['calColourRating'] = $colourRating;
        }
        if($rettype == 'return')
        {
            $data = array('room_list' => $room_list, 'roomAvailability' => $roomAvailability);
            return $data;
        } else if($rettype == 'output') {
            $this->set(compact('room_list', 'roomAvailability'));
        }
    }

    public function ajaxGenerateAvailableRoomList()
    {
        $this->generateAvailableRoomList('output');
    }

    public function ajaxAllocationInfo()
    {
        $timestamp = (int) substr($this->params['named']['calDate'],0,-3);
        $selectedDate = date("Y-m-d", $timestamp);
        if(isset($this->params['named']['calDate']))
        {
            $this->autoRender = false;
            $results = $this->generateAvailableRoomList('return', $selectedDate, $selectedDate);
            $results = $results['roomAvailability'][$selectedDate];
            echo "<h2>Availability for ". date("l jS F Y",  $timestamp ) ."</h2>";
            echo "Beds Available: " . $results['bedsAvailable'] . "<br />";
            echo "Beds Taken: "  . $results['bedsTaken'] . "<br />";
            echo "<h2>Rooms Available</h2><ul>";
            $room_ids = array_keys($results['rooms']);
            for($i = 0; $i < count($results['rooms']); $i++)
            {
                if($results['rooms'][$room_ids[$i]]['roomUsable'])
                {
                    $room_details = $this->Booking->Room->find('first', array('conditions' => array('Room.id' => $room_ids[$i])));
                    echo "<li>#".$room_details['Room']['number'] .
                        " (" . $room_details['Room']['description'] .
                        ")(" . $room_details['Room']['beds'] . " Beds)</li>";
                }
            }
            echo "</ul>";
			echo "<a href=\"/uniwork/llechfan/trunk/bookings/add\">Make a Booking</a></div>";
			//$this->Html->link(__('Make a Booking'), array('controller' => 'bookings', 'action' => 'add')); 
        }  else {
            echo "No date specified!";

        }
    }

    public function returnRoomAvailability()
    {
        return $this->generateAvailableRoomList('return');
    }

    public function jsonAvailabilityData()
    {
        $this->RequestHandler->setContent('json', 'application/json' );
        $this->autoRender = false;
        if(isset($this->params['named']['calMonth']))
        {
            $baseDate = date("Y-m-d", strtotime($this->params['named']['calYear'] . '-' . $this->params['named']['calMonth'] . '-01'));
            $availability = $this->generateAvailableRoomList('return', $baseDate , date("Y-m-d",strtotime("+1 month", strtotime($baseDate) )));
        } else {
            $availability = $this->generateAvailableRoomList('return', date("Y-m-d", strtotime($this->params['named']['calStartDate'])),
                date("Y-m-d",strtotime($this->params['named']['calEndDate'])));
        }
        echo json_encode($availability['roomAvailability']);
    }

    function allocateRoom($booking, $user)
    {
        $booking['totalpeople'] = $booking['malemembers'] + $booking['femalemembers'];
        if($booking['malemembers'] > 0 && $booking['femalemembers'] > 0)
        {
             $usable = false;
        }
        $bookingRange = $this->getDatesFromRange( $this->cakeDateToString($booking['checkin']), $this->cakeDateToString($booking['checkout']));
        $this->LoadModel('Room');
        $this->LoadModel('User');
        $rooms = $this->Room->find('all');
        foreach($rooms as $room)
        {
            $usable = true;
            $room_bookings = $this->Booking->find('all', array(
                'conditions' => array('room_allocated' => $room['Room']['id'])));
            $bedsTaken = array();
            foreach($room_bookings as $room_booking)
            {
                if($room_booking['Booking']['approved'])
                {
                    $user_booking = $this->User->find('first', array(
                        'conditions' => array( 'User.id' => $room_booking['Booking']['user_id'] )));
                    if($user_booking['User']['gender'] != $user['gender'])
                    {
                        $usable = false;
                    }
                    $roomRange = $this->getDatesFromRange($room_booking['Booking']['checkin'], $room_booking['Booking']['checkout']);
                    foreach($bookingRange as $bookingDate)
                    {
                        if(!isset($bedsTaken[$bookingDate])) $bedsTaken[$bookingDate] = array();
                        foreach($roomRange as $roomDate)
                        {
                            if($bookingDate == $roomDate)
                            {
                                if(!in_array($room_booking['Booking']['user_id'], $bedsTaken[$bookingDate]))
                                {
                                    array_push($bedsTaken[$bookingDate], $room_booking['Booking']['user_id']);
                                }
                            }
                        }
                    }
                }
            }
            if(count($bedsTaken) > 0)
            {
                foreach($bookingRange as $bookingDate)
                {
                    $availableBeds = ($room['Room']['beds'] - count($bedsTaken[$bookingDate]));
                    if($availableBeds < $booking['totalpeople'] ) $usable = false;
                }
            }
            if($usable)
            {
                break;
            }
        }
        if($usable)
        {
            $ret_val = $room['Room']['id'];
        }   else {
            $ret_val = NULL;
        }
        return $ret_val;
    }

    public function confirm()
    {
        $this->Booking->create();
        if($this->Session->check('AutoBooking'))
        {
            $savedBooking = $this->Session->read('AutoBooking.Booking');
            $dates =  array( $this->cakeDateToString($savedBooking['Booking']['checkin']),
                $this->cakeDateToString($savedBooking['Booking']['checkout']));
            $user = $this->Booking->User->find('first', array(
                'conditions' =>array('User.id' => $savedBooking['Booking']['user_id'])
            ));
            $room = $this->Booking->Room->find('first', array(
                'conditions' =>array('Room.id' => $savedBooking['Booking']['room_allocated'])
            ));
        } else {
            $this->Session->setFlash(__('No saved booking present!'));
            $this->redirect(array('action' => 'add'));
        }
        $this->set(compact('user', 'room', 'dates', 'savedBooking'));
    }

    public function complete() {
        $user = $this->Session->read('Auth.User');
        $savedBooking = $this->Session->read('AutoBooking.Booking');
        $this->Booking->create();
        if ($this->Booking->save($savedBooking)) {
            $this->Session->setFlash(__('The booking has been saved'));
            //$this->Session->delete('AutoBooking');
            if($user['Role']['name'] == "Volunteer")
            {
                $dates =  array( $this->cakeDateToString($savedBooking['Booking']['checkin']),
                    $this->cakeDateToString($savedBooking['Booking']['checkout']));
                $room = $this->Booking->Room->find('first', array(
                    'conditions' =>array('Room.id' => $savedBooking['Booking']['room_allocated'])
                ));
                $msg = '<p>Thankyou for your booking, ' . h($user['firstname']) .  ' ' . $user['surname'].'</p>Booked For: ' .
    date("l jS F Y", strtotime($dates[0]))." - ".date("l jS F Y", strtotime($dates[1]))
    .'<br />Room Allocated: #'.$room['Room']['number'] . " (" . $room['Room']['description'] .
    ")(" . $room['Room']['beds'] . ' Beds)</p><p>Male Members: ' . $savedBooking['Booking']['malemembers'] .
    '<br />Female Members: ' . $savedBooking['Booking']['femalemembers'] . '</p><p>Amount Due: &pound;' .
    $savedBooking['Booking']['amountdue'] . '</p><p>Comments: ' . $savedBooking['Booking']['comments'] . '</p>';
                $email = new CakeEmail('default');
                $email->template('default')
                    ->emailFormat('html')
                    ->from(array('no-reply@rossmoney.co.uk' => 'Llechfan Hostel Accomodation Booking System'))
                    ->to($user['email'])
                    ->bcc('enquiries@rossmoney.co.uk')
                    ->subject('Your Booking at Llechfan Volunteer Hostel')
                    ->send($msg);
            }

            $this->redirect(array('action' => 'user'));
        } else {
            $this->Session->setFlash(__('The booking could not be saved. Please, try again.'));
            $this->redirect(array('action' => 'add'));
        }
    }
    /**
     * add method
     *
     * @return void
     */

    public function addMethod($id = null, $usermode) {

        if ($this->request->is('post')) {
            $this->Booking->set($this->request->data);
            if ($this->Booking->validates()) {
                if($this->request->data['Booking']['user_id'] == "")
                {
                    $this->Session->write( 'AutoBooking' , array( 'Booking' => $this->request->data ));
                    $this->Session->setFlash(__('Login to your account to complete your booking:'));
                    $this->redirect(array('action' => 'login', 'controller' => 'users'));
                } else {
                    $user = $this->Session->read('Auth.User');
                    if($user['age'] < 14 && $this->request->data['Booking']['user_id'] != "")
                    {
                        $this->Session->setFlash(__('You cannot make this booking! Must be 14 or over to stay at the hostel!'));
                    } else {
                        if($usermode == 'admin')
                        {
                            $this->Booking->create();
                            if ($this->Booking->save($this->request->data)) {
                                $this->Session->setFlash(__('The booking has been saved'));
                                $this->redirect(array('action' => 'index'));
                            } else {
                                $this->Session->setFlash(__('The booking could not be saved. Please, try again.'));
                            }
                        } else {
                            $this->request->data['Booking']['room_allocated'] =
                                $this->allocateRoom($this->request->data['Booking'], $user );
                            if($this->request->data['Booking']['room_allocated'] != NULL)
                            {
                                $room_details = $this->Room->find('first', array(
                                    'conditions' => array('id' => $this->request->data['Booking']['room_allocated'])));
                                $this->request->data['Booking']['amountdue'] = $room_details['Room']['price'];
                            }
                            $this->Session->write( 'AutoBooking' , array( 'Booking' => $this->request->data ));
                            $this->redirect(array('action' => 'confirm', 'controller' => 'bookings'));
                        }
                    }
                }
            } else {
                //$this->Session->write( 'AutoBooking' , array( 'Booking' => $this->request->data ));
                $this->Session->setFlash(__('The booking could not be saved. Please, try again.'));
            }
        }

        if($this->Session->check('AutoBooking'))
        {
            $savedBooking = $this->Session->read('AutoBooking.Booking');
            $this->Session->delete('AutoBooking');
        } else {
            $savedBooking = false;
        }

        if($usermode == 'admin')
        {
            $availability = $this->generateAvailableRoomList('return', date("Y-m-d"), date("Y-m-d", strtotime("+1 day")), 1);
            $users = $this->Booking->User->find('list', array(
                'fields' =>array('User.id', 'User.name')
            ));
        }
        //    $availability = $this->returnRoomAvailability();
        //}

        $this->set(compact('users', 'savedBooking', 'availability'));
    }

    public function add($id = null) {
        $this->addMethod($id, 'user');
    }

    public function adminadd ($id = null) {
        $this->addMethod($id, 'admin');
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    function editMethod($id = null) {
        if (!$this->Booking->exists($id)) {
            throw new NotFoundException(__('Invalid booking'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Booking->save($this->request->data)) {
                $this->Session->setFlash(__('The booking has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The booking could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Booking.' . $this->Booking->primaryKey => $id));
            $this->request->data = $this->Booking->find('first', $options);
        }
        $users = $this->Booking->User->find('list', array('fields' => array('User.id', 'User.name')));
        $availability = $this->returnRoomAvailability();
        $this->set(compact('users', 'availability'));
    }

    public function edit($id = null) {
        $this->editMethod($id);
    }

    public function useredit ($id = null) {
        $this->editMethod($id);
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Invalid booking'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Booking->delete()) {
            $this->Session->setFlash(__('Booking deleted'));
            if($this->Session->read('Auth.User.Role.name') == "Volunteer")
            {
                $this->redirect(array('action' => 'user'));
            } else {
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash(__('Booking was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
