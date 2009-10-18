<?php
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2009, Adam Price
 * @license         http://www.gnu.org/licenses/lgpl.html LGPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */
 
define(BASEPATH,"system/application/");
 
require_once 'PHPUnit/Framework.php';
require_once BASEPATH . 'tests/Base_test_code.php';
require_once BASEPATH . 'tests/mocks/Model.php';
require_once BASEPATH . 'modules/preference/models/preference_model.php';

/**
 * PreferenceModelTests
 * 
 * Test class for the preference_model class
 * 
 * @subpackage      Tests
 * @category        Models
 */
class PreferenceModelTest extends PHPUnit_Framework_TestCase
{ 
    var $model;
    
    function setUp()
    {
        $GLOBALS['config_return'] = "bep_";
        $this->model = new Preference_model();
    }
    
    function tearDown()
    {
        unset($this->model);
    }
    
    /**
     * Test Get Item Simple
     * 
     * Tests that getting an item from the DB works using
     * a simple type, int/string.
     * 
     * @test
     * @covers Preference_model::item
     */
    function get_item()
    {
        // Mock the preference row we expect
        $row_mock = $this->getMock('row');
        $row_mock->name = 'test_get';
        $row_mock->value = 4;
        
        // Mock the DB query obj returned
        $query_mock = $this->getMock('query', array('result'));        
        $query_mock->expects($this->once())
                    ->method('result')
                    ->will($this->returnValue(array(0 => $row_mock)));
        
        // Mock the DB class
        $db_mock = $this->getMock('db', array('select','from','get'));
        $db_mock->expects($this->once())
                 ->method('select')
                 ->with($this->equalTo('name, value'));
                 
        $db_mock->expects($this->once())
                 ->method('from')
                 ->with($this->equalTo('bep_preferences'));
                 
        // Return the query obj
        $db_mock->expects($this->once())
                 ->method('get')
                 ->will($this->returnValue($query_mock));
                 
        // Assign DB mock to the model
        $this->model->db = $db_mock;
 
        // RUN test
        $this->assertEquals(4, $this->model->item('test_get'));
        $this->assertEquals(1, count($this->model->preference_cache));
        $this->assertEquals(4, $this->model->preference_cache['test_get']);
    }
    
    /**
     * Test Get Item for array
     * 
     * Tests that an array object can be fetched, unserialized
     * and returned.
     * 
     * @test
     * @covers Preference_model::item
     */
    function get_item_array()
    {
        // Create the test array
        $test_array = array('1','2');
        
        // Mock the preference row we expect
        $row_mock = $this->getMock('row');
        $row_mock->name = 'test_get_array';
        $row_mock->value = 'BeP::Object::' . serialize($test_array);
        
        // Mock the DB query obj returned
        $query_mock = $this->getMock('query', array('result'));        
        $query_mock->expects($this->once())
                    ->method('result')
                    ->will($this->returnValue(array(0 => $row_mock)));
        
        // Mock the DB class
        $db_mock = $this->getMock('db', array('select','from','get'));
        $db_mock->expects($this->once())
                 ->method('select')
                 ->with($this->equalTo('name, value'));
                 
        $db_mock->expects($this->once())
                 ->method('from')
                 ->with($this->equalTo('bep_preferences'));
                 
        // Return the query obj
        $db_mock->expects($this->once())
                 ->method('get')
                 ->will($this->returnValue($query_mock));
                 
        // Assign DB mock to the model
        $this->model->db = $db_mock;
 
        // RUN test
        $this->assertEquals($test_array, $this->model->item('test_get_array'));
        $this->assertEquals(1, count($this->model->preference_cache));
        $this->assertEquals($test_array, $this->model->preference_cache['test_get_array']);
    }
    
    /**
     * Test Set Item Simple
     * 
     * Tests that a preference item can be saved. Uses
     * basic int/string value.
     * 
     * @test
     * @covers Preference_model::set_item
     */
    function set_item()
    {
        $db_mock = $this->getMock('db',array('where','update'));
        $db_mock->expects($this->once())
                ->method('where')
                ->with('name', 'test_set');
                
        $db_mock->expects($this->once())
                ->method('update')
                ->with('bep_preferences', array('value' => 'mystring'));
                
        // Assign DB mock to the model
        $this->model->db = $db_mock;
        
        // RUN test
        $this->model->set_item('test_set','mystring');
        $this->assertEquals('mystring', $this->model->preference_cache['test_set']);
    }
    
    /**
     * Test Set Item Array
     * 
     * Tests that a preference item can be saved. Uses a simple
     * array as the preference value.
     * 
     * @test
     * @covers Preference_model::set_item
     */
    function set_item_array()
    {
        // Create the test array
        $test_array = array('1','2');
        
        $db_mock = $this->getMock('db',array('where','update'));
        $db_mock->expects($this->once())
                ->method('where')
                ->with('name', 'test_set_array');
                
                
        $db_mock->expects($this->once())
                ->method('update')
                ->with('bep_preferences', array('value' => 'BeP::Object::' . serialize($test_array)));
                
        // Assign DB mock to the model
        $this->model->db = $db_mock;
        
        // RUN test
        $this->model->set_item('test_set_array',$test_array);
        $this->assertEquals($test_array, $this->model->preference_cache['test_set_array']);
    }
}

/* End of file Preference_model_tests.php */
/* Location: ./system/application/modules/preference/tests/models/Preference_model_tests.php */