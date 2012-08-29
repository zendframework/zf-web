<?php

namespace Application\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormRow as BaseFormRow;

class FormRow extends BaseFormRow
{
    public function render(ElementInterface $element)
    {
        $markup = parent::render($element);
        return sprintf('<div class="element">%s</div><div class="clear"></div>', $markup);
    }
}
