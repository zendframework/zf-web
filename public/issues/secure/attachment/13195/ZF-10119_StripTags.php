<?php

class DummyTest extends PHPUnit_Framework_TestCase
{

    public function testStripTagsUnicode()
    {
        set_time_limit(30);

        $value = '<div>This string will be<!-- Strange char r�d --> cleaned</div>';

        $filter = new Zend_Filter_StripTags();
        $valueFiltered = $filter->filter($value);
        $this->assertEquals('This string will be cleaned', $valueFiltered);
    }

}
