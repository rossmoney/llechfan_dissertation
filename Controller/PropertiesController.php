<?php
App::uses('AppController', 'Controller');
/**
 * Properties Controller
 *
 */
class PropertiesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Session->read('Auth.User');
        if($user['Role']['name'] == "Administrator")
        {
            $this->Auth->allow('index', 'add', 'edit', 'destroy');
        }
    }

    public function index() {
        $this->Property->recursive = 0;
        $this->set('properties', $this->paginate());
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Property->exists($id)) {
            throw new NotFoundException(__('Invalid Property'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Property->save($this->request->data)) {
                $this->Session->setFlash(__('The Property has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Property could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Property.' . $this->Property->primaryKey => $id));
            $this->request->data = $this->Property->find('first', $options);
        }
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
        $this->Property->id = $id;
        if (!$this->Property->exists()) {
            throw new NotFoundException(__('Invalid Property'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Property->delete()) {
            $this->Session->setFlash(__('Property deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Property was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
