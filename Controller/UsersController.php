<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Session->read('Auth.User');
        $this->Auth->allow('initdb', 'destroysession', 'register', 'logout');
        if($user['Role']['name'] == "Administrator")
        {
            $this->Auth->allow('destroysession');
        }
    }

    public function destroySession() {
         $this->Session->destroy();
         $this->redirect($this->Auth->logout());
         exit;
    }

    /*public function initDB() {
        $role = $this->User->Role;
        //Allow admins to everything
        $role->id = 1;   //Administrator
        $this->Acl->allow($role, 'controllers');

        $role->id = 2;      //Treasurer
        $this->Acl->allow($role, 'controllers/Users/logout');

        $role->id = 3;      //Volunteer
        $this->Acl->allow($role, 'controllers/Bookings/user');

        $role->id = 4;      //Warden
        $this->Acl->allow($role, 'controllers/Bookings/index');

        echo "all done";
        exit;
    }*/

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $user = $this->Session->read('Auth.User');
                if ($user) {
                    if($user['disabled'] == 1)
                    {
                        $this->Session->setFlash('Your account has been disabled!' );
                        $this->redirect($this->Auth->logout());
                    }
                    $this->Session->setFlash('You are logged in!' );
                    if($this->Session->check('AutoBooking'))
                    {
                        $this->redirect('/bookings/add', null, false);
                    } else {
                        if($user['Role']['name'] == "Administrator")
                        {
                            $this->redirect('/users/index', null, false);
                        }
                        if($user['Role']['name'] == "Treasurer")
                        {
                            $this->redirect('/payments', null, false);
                        }
                        if($user['Role']['name'] == "Volunteer")
                        {
                            $this->loadModel('Booking');
                            $user_bookings = $this->Booking->find('count', array('conditions' => array('user_id' => $user['id'])));
                            if($user_bookings > 0)
                            {
                                $this->redirect('/bookings/user', null, false);
                            } else {
                                $this->redirect('/bookings/add', null, false);
                            }
                        }
                        if($user['Role']['name'] == "Warden")
                        {
                            $this->redirect('/bookings/index', null, false);
                        }
                    }
                }
            } else {
                $this->Session->setFlash('Your username or password was incorrect.');
            }
        }
    }

    public function logout() {
        $this->Session->destroy();
        $this->Session->setFlash('You have been logged out!');
        $this->redirect($this->Auth->logout());
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
		    $this->User->create();
		    if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
    }

    public function register() {
        if ($this->request->is('post')) {
            $age = date("Y") - $this->request->data['User']['dob']['year'];
            if($age < 15 )
            {
                $this->Session->setFlash(__('Must be over 14 to stay at the hostel!'));
            } else {
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('The user has been saved'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('You could not be saved in the system. Please correct any mistakes and try again.'));
                }
            }
        }
        $role = $this->User->Role->find('first', array('conditions' => array('name' => 'Volunteer')));
        $this->set(compact('role'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
