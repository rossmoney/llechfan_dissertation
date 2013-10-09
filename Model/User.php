<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Role $Role
 * @property Booking $Booking
 */
class User extends AppModel {

    public $virtualFields = array(
        'name' => "CONCAT(User.firstname, ' ', User.surname)",
        'age' => "YEAR(NOW())-YEAR(User.dob)"
    );

    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['role_id'])) {
            $roleId = $this->data['User']['role_id'];
        } else {
            $roleId = $this->field('role_id');
        }
        if (!$roleId) {
            return null;
        } else {
            return array('Role' => array('id' => $roleId));
        }
    }

    public function bindNode($user) {
        return array('model' => 'Role', 'foreign_key' => $user['User']['role_id']);
    }

    /*public function beforeSave($options = array()) {
        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        return true;
    }         */
    public function beforeSave($options = array())
    {
        if (!empty($this->data['User']['passwd']))
        {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['passwd']);
        }
        return true;
    }


    function matchpwd($data){
        if ($this->data['User']['passwd']!=$data['passwd_confirm'] ) {
            return false;
        }
        return true;
    }

    function vDob($field, $minAge) {
        $volunteerYear = date('Y', strtotime($field['dob']));
        $currentYear = date('Y');

        if ( ($currentYear-$volunteerYear) > $minAge ) {
            return true;
        } else {
            return false;
        }

        return true;
    }
    /**
 * Validation rules
 *
 * @var array
 */
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'role_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'That email is already in the system!',
                'on' => 'create'
            ),
            'email' => array (
                'rule' => array('email'),
                'message'  => 'Your email is invalid.'
            )
        ),
        'passwd' => array(
            'rule' => array('minLength', '8'),
            'message' => 'Password should be at least 8 characters long',
            'required' => true,
            'allowEmpty' => false,
            'on' => 'create'
        ),
        'passwd_confirm' => array(
            'rule' => 'matchpwd',
            'message' => 'Confirm password doesnt match'
        ),
        'firstname' => array(
            'rule'=>'notEmpty',
            'required'   => true,
            'message' => 'Field is required.'
        ),
        'surname' => array(
            'rule'=>'notEmpty',
            'required'   => true,
            'message' => 'Field is required.'
        ),
        'gender' => array(
            'rule'=>'notEmpty',
            'required'   => true,
            'message' => 'Field is required.'
        ),
        'dob' => array(
            'required' => array(
                'required' => true,
                'allowEmpty' => false,
                'rule' => 'date',
                'message' => 'Field is required.'
            ),
            'valid' => array(
                'rule' => array('vDob', 14),
                'message' => 'Must be above 14 years old'
            )
        ),
        'telno' => array (
            'rule'=> array('custom' , '/(\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*)/'),
            'message' => 'Must be a valid telephone number.',
            'required' => true,
            'allowEmpty' => false
        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Booking' => array(
            'className' => 'Booking',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

}
