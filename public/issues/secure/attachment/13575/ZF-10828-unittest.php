
class FormTest extends PHPUnit_Framework_TestCase{
    var $form;

    // called before the test functions will be executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    function setUp() {
        // create a new instance of String with the
        // string 'abc'
        $this->form = new Zend_Form();
    }

    function testAddTextboxSucceeds(){
        $result = $this->form->addElement('text', 'username');
        $this->form    = $result;

        $expected = "Zend_Form_Element_Text"; //type of class
        $this->assertTrue(get_class($result->username) == $expected);
    }

    function testAddNewAttrSucceeds(){
        $this->form->addElement('text', 'username');
        $result = $this->form->getElement('username')->setAttrib('readonly', 'readonly');

        $expected = "readonly";
        $this->assertTrue($result->$expected == $expected);
    }

    function testRemoveAttributeRaisesExceptionOnEmptyInput()
    {
        $this->form->addElement('text', 'username');
        $this->form->getElement('username')->setAttrib('readonly', 'readonly');

        $this->setExpectedException('Zend_Form_Exception', 'Attribute cannot be empty');
        $result = $this->form->getElement('username')->removeAttrib('');
    }

    function testRemoveAttributeRaisesExceptionOnUnknownAttribute()
    {
        $this->form->addElement('text', 'username');
        $this->form->getElement('username')->setAttrib('readonly', 'readonly');

        $this->setExpectedException('Zend_Form_Exception', 'Attribute "size" does not exist');
        $result = $this->form->getElement('username')->removeAttrib('size');
    }

    function testRemoveAttributeSucceedsForKnownAttributes()
    {
        $this->form->addElement('text', 'username');
        $this->form->getElement('username')->setAttrib('readonly', 'readonly');

        $result = $this->form->getElement('username')->removeAttrib('readonly');
        $this->assertFalse(isset($this->form->getElement('username')->readonly));
    }
}
