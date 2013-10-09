<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __('Llechfan Accomodation Booking System - '); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

        echo $this->Html->css('blitzer/jquery-ui-1.9.2.custom.min');
        echo $this->Html->script('jquery-1.8.3');
        echo $this->Html->script('jquery-ui-1.9.2.custom.min');
        echo $this->Html->css('cake.generic');
        echo $this->Html->css('calendar');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

        if($this->action == "display" && $this->name == "Pages" && $page == "home") {
	?>

    <?php } ?>
</head>
<body>
	<div id="container">
		<div id="header">
            <h1>Llechfan Accomodation Booking System</h1>
            <div id="userstrip">
            <?php
            $user = $this->Session->read('Auth.User');

            if($this->Session->check('Auth.User.id'))
            {
                echo "You are logged in as ".$user['firstname']." ". $user['surname'] . " (" . $user['Role']['name'] .") ";
                echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout'));
            } else {
                echo "You are not logged in.";
                if( $this->action != "login")
                {
                    echo " (".$this->Html->link(__('Login'), array('controller' => 'users', 'action' => 'login')). ")";
                }
                echo " (".$this->Html->link(__('Register'), array('controller' => 'users', 'action' => 'register')). ")";
            }
            ?>
            </div>
        <div id="menu">
            <?php
            if($this->Session->read('Auth.User.Role.name') == "Administrator")
            {
            ?>
            <?php echo $this->Html->link(__('Rooms'), array('controller' => 'rooms', 'action' => 'index')); ?> .
            <?php echo $this->Html->link(__('Users'), array('controller' => 'users','action' => 'index')); ?> .
            <?php echo $this->Html->link(__('Roles'), array('controller' => 'roles','action' => 'index')); ?> .
            <?php echo $this->Html->link(__('Payments'), array('controller' => 'payments','action' => 'index')); ?> .
            <?php echo $this->Html->link(__('Bookings'), array('controller' => 'bookings','action' => 'index')); ?> .
            <?php echo $this->Html->link(__('System Settings'), array('controller' => 'properties','action' => 'index')); ?>
            <?php
            }
            if($this->Session->read('Auth.User.Role.name') == "Volunteer")
            {
            ?>
            <?php echo $this->Html->link(__('Your Bookings'), array('controller' => 'bookings','action' => 'user')); ?>
            <?php
            }
            if($this->Session->read('Auth.User.Role.name') == "Treasurer")
            {
            ?>
            <?php echo $this->Html->link(__('Payments'), array('controller' => 'payments','action' => 'index')); ?>
            <?php
            }
            if($this->Session->read('Auth.User.Role.name') == "Warden")
            {
            ?>
            <?php echo $this->Html->link(__('Bookings'), array('controller' => 'bookings','action' => 'index')); ?>
            <?php
            }
            if($this->Session->read('Auth.User.Role.name') == "Key Issuer")
            {
            ?>
            <a href="rooms">Rooms</a> .
            <a href="users">Users</a> .
            <a href="roles">Roles</a> .
            <?php
            }
            ?>
        </div>
        </div>
		<div id="content">

			<?php
			    echo $this->Session->flash();
                echo $this->Session->flash('auth');
            ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
             <?php
            foreach($properties as $property)
            {
                if($property['Property']['property'] == "copyright") echo $property['Property']['value'];
            }
            ?>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
