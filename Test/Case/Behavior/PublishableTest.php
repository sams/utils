<?php
App::uses('AppModel', 'Model');
App::uses('Model', 'Model');
App::uses('PublishableBehavior', 'Utils.Model/Behavior');

class Date
{
    protected $date;

    public function __construct($date = null)
    {
        $this->date = $date ? $date : time();
    }

    public function today()
    {
        // to something with $this->date
    }
}

class Product extends CakeTestModel {
	public $useTable = 'products';
}

/**
 * PublishableBehavior Test Case
 *
 */
class PublishableBehaviorTest extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.utils.product');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Product = ClassRegistry::init('Product');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Product);

		parent::tearDown();
	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testFindBehaviorEnabled() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'date_field' => false, 'find' => true));
		$expected = array(
										0 => array('Product' => array(
											'id' => 2,
											'name' => 'Cook like Jamie DVD',
											'description' => 'Learn to cook like Jamie Oliver'
									)),
									1 => array('Product' => array(
											'id' => 4,
											'name' => 'Nigella from the Heart',
											'description' => 'Nigella Eat your heart out'
										))
							);
		$this->assertEquals($expected, $this->Product->find('all', array('fields' => array('id', 'name', 'description'))));

	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testFindBehaviorDisabled() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'find' => false));
		$expected = Array
(
	0 => Array(
		'Product' => Array
				(
						'id' => 1,
						'name' => 'Foot Ball DVD',
						'description' => 'The best footie matches ever'
				)
		),
	1 => Array
		(
				'Product' => Array
						(
								'id' => 2,
								'name' => 'Cook like Jamie DVD',
								'description' => 'Learn to cook like Jamie Oliver'
						)
		),
	2 => Array
		(
			'Product' => Array
					(
							'id' => 3,
							'name' => 'Utimte Fishing',
							'description' => 'Where to Fish in the UK'
					)
		),
	3 => Array
		(
				'Product' => Array
						(
								'id' => 4,
								'name' => 'Nigella from the Heart',
								'description' => 'Nigella Eat your heart out'
						)
		)
);
		$this->assertEquals($expected, $this->Product->find('all', array('fields' => array('id', 'name', 'description'))));

	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testPublish() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'find' => true));
		$this->Product->id = 1;
		$this->assertTrue($this->Product->publish());
		$this->assertTrue($this->Product->field('published'));

	}
/**
 * testUnPublish method
 *
 * @return void
 */
	public function testUnPublish() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'find' => true));
		$this->Product->id = 2;
		$this->assertTrue($this->Product->unPublish());
		$this->assertFalse($this->Product->field('published'));
	}
///**
// * testEnablePublishable method
// *
// * @return void
// */
//	public function testEnablePublishable() {
//
//	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testFindBehaviorEnabledWithDate() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'field_date' => 'publish_date', 'find' => true));
		Configure::write('Publishable.disable', false);
		$expected = array(array('Product' => array(
                    'id' => 4,
                    'name' => 'Nigella from the Heart',
                    'description' => 'Nigella Eat your heart out')));
		$this->assertEquals($expected, $this->Product->find('all', array('fields' => array('id', 'name', 'description'))));
	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testFindBehaviorDisabledWithDate() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'field_date' => 'publish_date', 'find' => true));
		Configure::write('Publishable.disable', true);
		//$expected = array();
		//$this->assertEquals($expected, $this->Product->find('all', array('conditions' => array('id' => array(1,2)), 'fields' => array('id', 'name', 'description'))));
	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testPublishWithDate() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'field_date' => 'publish_date', 'find' => true));
		$this->Product->id = 4;
		$this->assertTrue($this->Product->unPublish());
		$this->assertFalse($this->Product->field('published'));
		$this->assertEmpty($this->Product->field('publish_date'));
		$this->assertTrue($this->Product->publish());
		$this->assertTrue($this->Product->field('published'));
		$this->assertNotEmpty($this->Product->field('publish_date'));
		$result = $this->Product->find('all', array('fields' => array('id', 'name', 'description', 'published', 'publish_date')));
		$expected = array('Product' => array(
			'id' => '4',
			'name' => 'Nigella from the Heart',
			'description' => 'Nigella Eat your heart out',
			'published' => true,
			'publish_date' => 'regex:/2012-([0-9]{2})-([0-9]{2})(.*)/'
		));
		$this->assertEquals($expected, $result);
		$this->Product->Behaviors->detach('Utils.Publishable');
	}

/**
 * testUnPublish method
 *
 * @return void
 */
	public function testUnPublishWithDate() {
		$this->Product->Behaviors->attach('Utils.Publishable', array(
			'field' => 'published', 'field_date' => 'publish_date', 'find' => true));
		$this->Product->id = 4;
		$this->assertTrue($this->Product->unPublish());
		$this->assertFalse($this->Product->field('published'));
		$this->assertEmpty($this->Product->field('publish_date'));
		$expected = array();
		$this->assertEquals($expected, $this->Product->find('all', array('fields' => array('id', 'name', 'description'))));
		$this->Product->Behaviors->detach('Utils.Publishable');
	}
}
