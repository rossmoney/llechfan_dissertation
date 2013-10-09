<?php
App::uses('AppModel', 'Model');
/**
 * Property Model
 *
 */
class Property extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'propertys';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'property';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
