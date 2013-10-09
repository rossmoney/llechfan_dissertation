<?php
App::uses('AppModel', 'Model');
/**
 * Booking Model
 *
 * @property User $User
 */
class Booking extends AppModel {

    function field_comparison($check1, $operator, $field2) {
        foreach($check1 as $key=>$value1) {
            $value2 = $this->data[$this->alias][$field2];
            if (!Validation::comparison($value1, $operator, $value2))
                return false;
        }
        return true;
    }

    function dateInPast($check, $field)
    {
        if(strtotime($check[$field]) < strtotime(date("Y-m-d"))) return false;

        return true;
    }

    public function mfCheck(){
        if($this->data['Booking']['malemembers'] > 0 || $this->data['Booking']['femalemembers'] > 0){
            return true;
        }
        return false;
    }

    public $virtualFields = array(
        'totalpeople' => "Booking.malemembers + Booking.femalemembers",
        'arrive' => 'DATE_FORMAT(Booking.checkin,"%d/%m/%Y")',
        'depart' => 'DATE_FORMAT(Booking.checkout,"%d/%m/%Y")'
    );

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'checkin' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'inPast' => array(
                'rule'=>array('dateInPast', 'checkin'),
                'message' => 'Check in date cannot be in the past!',
                'allowEmpty'=>false
            ),
		),
		'checkout' => array(
            'comparison' => array(
                'rule'=>array('field_comparison', '>', 'checkin'),
                'message' => 'Check out date cannot be before check in date!',
                'allowEmpty'=>false
            ),
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'inPast' => array(
                'rule'=>array('dateInPast', 'checkout'),
                'message' => 'Check out date cannot be in the past!',
                'allowEmpty'=>false
            ),
		),
		'malemembers' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Must be a number!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'customCheck' => array(
                'rule' => 'mfCheck',
                'message' => 'Males and females cannot be 0. Must be at least one male or female.'
            ),
            'number' => array(
                'rule' => array('range', -1, 5),
                'message' => 'Must be between 0 and 4.'
            )
		),
		'femalemembers' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Must be a number!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'customCheck' => array(
                'rule' => 'mfCheck',
                'message' => 'Males and females cannot be 0. Must be at least one male or female.'
            ),
            'number' => array(
                'rule' => array('range', -1, 5),
                'message' => 'Must be between 0 and 4.'
            )
		),
		'keyissued' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'payed' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comments' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'approved' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
        'Room' => array(
			'className' => 'Room',
			'foreignKey' => 'room_allocated',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
