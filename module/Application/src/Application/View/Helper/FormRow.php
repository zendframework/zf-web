<?php

namespace Application\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormRow as BaseFormRow;

class FormRow extends BaseFormRow
{
    public function render(ElementInterface $element)
    {
        // Error helper
        $elementErrorsHelper = $this->getElementErrorsHelper();
        $elementErrorsHelper->setMessageOpenFormat('<small>');
        $elementErrorsHelper->setMessageCloseString('</small>');
        $elementErrorsHelper->setMessageSeparatorString('<br>');
        $elementErrors  = $elementErrorsHelper->render($element);

        // Add CSS class if the element has errors
        $attributes = null;
        if (!empty($elementErrors)) {
            $attributes = $this->createAttributesString(array('class' => 'error'));
        }

        // Append no errors!
        $this->setRenderErrors(false);

        // Render label with element
        $markup = sprintf(
            '<div%s>%s%s</div>',
            (null !== $attributes)?' ' . $attributes:'', // Attributes
            parent::render($element), // Element
            $elementErrors // Errors
        );

        return $markup;
    }
}
