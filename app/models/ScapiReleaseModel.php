<?php

class ScapiReleaseModel
{
    protected $_dates = array(
        '1.0.0a1' => '2010-05-06',
    );

    public function getVersionList()
    {
        return array_keys($this->_dates);
    }

    protected function _getReleaseLabel($version, $suffix)
    {
        $label = '';
        if (preg_match('/^(alpha|a|beta|b|rc|r|pl|p)(\d+[a-z]?)?/i', $suffix, $matches)) {
            switch (strtolower($matches[1])) {
                case 'a':
                case 'alpha':
                    $label = 'Alpha';
                    break;
                case 'b':
                case 'beta':
                    $label = 'Beta';
                    break;
                case 'rc':
                case 'r':
                    $label = 'RC';
                    break;
                case 'pl':
                case 'p':
                    $label = 'patch';
                    break;
            }
            if (!empty($matches[2])) {
                $label .= ' ' . $matches[2];
            }
        }
        return $label;
    }
}
