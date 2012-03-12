<?php

class Zend_View_Helper_Components extends Zend_View_Helper_Abstract
{
    protected $_dojo = '
    function() {
        dojo.query(".manual-component-sctr").style({
            display: "block", visibility: "visible"
        }).attr({onchange: function(e) {
            console.log(e);
            window.location.href = e.target.value;
        }});
    }
';

    public function components(array $components)
    {
        $this->view->rightNav()->captureStart('append', 'Components');
        $version = $this->view->mVersion;
        $lang    = $this->view->code;
        if (null === $version || null === $lang) {
            return '';
        }
        if ($this->view->version == 'current') {
            $linkFormat = $this->view->baseUrl() . "/manual/$lang/%s.html";
        } else {
            $linkFormat = $this->view->baseUrl() . "/manual/$version/$lang/%s.html";
        }

        echo $this->_renderSelect($components, $linkFormat)
             . $this->_renderNoScript($components, $linkFormat);
        $this->view->rightNav()->captureEnd();
        return '';
    }

    protected function _renderSelect(array $components, $linkFormat)
    {
        $this->view->dojo()->addOnLoad($this->_dojo);
        $markup = '<select class="manual-component-sctr">' . "\n"
                . '<option value="" selected="selected">Select a component</option>' . "\n";
        foreach ($components as $id => $label) {
            $link = sprintf($linkFormat, $id);
            $markup .= sprintf('<option value="%s">%s</option>' . "\n", $link, $label);
        }
        $markup .= "</select>\n";
        return $markup;
    }

    protected function _renderNoScript($components, $linkFormat)
    {
        $markup = "<noscript>\n<ul>\n";
        foreach ($components as $id => $label) {
            $link = sprintf($linkFormat, $id);
            $markup .= sprintf('<li><a href="%s">%s</a></li>' . "\n", $link, $label);
        }
        $markup .= "</ul>\n</noscript>\n";
        return $markup;
    }
}
