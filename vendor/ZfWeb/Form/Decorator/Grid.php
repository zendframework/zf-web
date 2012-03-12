<?php
class ZfWeb_Form_Decorator_Grid extends Zend_Form_Decorator_Abstract
{
    /**
     * Render a grid in a tab
     * 
     * @param  string $content 
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $html = $view->contentPane(
            'grid',
            'Grid demo is loading...',
            array(
                'title'       => 'Grid Demo',
                'preload'     => false,
                'href'        => '/demo_dojo/grid/format/html',
                'parseOnLoad' => true,
            ),
            array(
                'class' => 'tab',
            )
        );
        return $content . $html;
    }
}
