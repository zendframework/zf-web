<?php

 // should go to Zend_Controller_Response_HttpTestCase
 protected function sendHeadersFixed()
    {
        $headers = array();
        foreach ($this->getResponse()->getHeaders() as $header) {

            $name = $header['name'];
            $key  = strtolower($name);
            if (array_key_exists($key, $headers)) {
                if ($header['replace']) {
                    $headers[$key] = $header['name'] . ': ' . $header['value'];
                } else {
                    // START FIX
                    if (!is_array($headers[$key])) {
                        // this is the second key to add, make it an array
                        $headers[$key] = array($headers[$key], $header['name'] . ': ' . $header['value']);
                    } else {
                        // this is a third+ key to add, just add to the end of the array
                        $headers[$key][] = $header['name'] . ': ' . $header['value'];
                    }
                    // END FIX
                }
            } else {
                $headers[$key] = $header['name'] . ': ' . $header['value'];
            }
        }
        return $headers;
    }

    // should go to Zend_Test_PHPUnit_Constraint_ResponseHeader 
    protected function _getHeaderFixed(Zend_Controller_Response_Abstract $response, $header)
    {
        // replace back when patching real code
        $headers = $this->sendHeadersFixed();
        //$headers = $response->sendHeaders();

        $header  = strtolower($header);
        if (array_key_exists($header, $headers)) {
            return $headers[$header];
        }
        return null;
    }

     // should go to Zend_Test_PHPUnit_Constraint_ResponseHeader etc.
    protected function _headerRegexFixed($header, $pattern)
    {
        if (null === ($fullHeader = $this->_getHeaderFixed($this->getResponse(), $header))) {
            return false;
        }

        if (!is_array($fullHeader)) {
            $contents = str_replace($header . ': ', '', $fullHeader);
            return preg_match($pattern, $contents);
        } else {
            // START FIX
            foreach ($fullHeader as $fullHeaderSingle) {
                $contents = str_replace($header . ': ', '', $fullHeaderSingle);
                if (preg_match($pattern, $contents)) return true;
            }
            // END FIX
        }

        return false;
    }

