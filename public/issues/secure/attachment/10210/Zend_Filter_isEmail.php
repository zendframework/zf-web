    /**
     * Returns TRUE if a valid email address, FALSE if not. This method 
     * tests for the allowed string format for an email address, not 
     * whether the email address actually exists. This method is 
     * dependant on Zend_Filter::isHostname to validate the domain
     * part of the email address. This method uses the classification 
     * within RFC2822 to match a valid email address.
     * 
     * @param string $value Email address to test
     * @return boolean
     */
    public static function isEmail($value)
    {
        // Split email address up
        if (preg_match('/^([^@]+)@([^@]+)$/', $value, $matches)) {
            $localPart	= $matches[1];
            $domain 	= $matches[2];
            
           /**
             * @todo check isHostname against RFC spec
             * @todo implement basic MX check on hostname via dns_get_record()
             */
            // Match domain part (allow hostnames and IP addresses)
            $domainResult = self::isHostname($domain, 3);
            
            // First try to match the local part on the common dot-atom format
            $localResult = false;
            
            // Dot-atom characters are:
            // ALPHA / DIGIT / and "!", "#", "$", "%", "&", "'", "*", "+", 
            // "-", "/", "=", "?", "^", "_", "`", "{", "|", "}", "~", "."
            // Dot character "." must be surrounded by other non-dot characters
            $dotAtom = '[a-zA-Z0-9\x21\x23\x24\x25\x26\x27\x2a\x2b\x2d\x2f\x3d';
            $dotAtom .= '\x3f\x5e\x5f\x60\x7b\x7c\x7d\x7e\x2e]';
            if ( (preg_match('/^' . $dotAtom . '+$/', $localPart)) && 
                 (strpos($localPart, '.') !== 0) && 
                 (strrpos($localPart, '.') !== strlen($localPart) - 1) ) {
                $localResult = true;
            }
            
            /**
             * @todo check Quoted-string character class
             */ 
            // If not matched, try quoted string format
            if (!$localResult) {
                // Quoted-string characters are:
                // Any US-ASCII characters except "\" or double-quote "
                
                // DQUOTE *([FWS] qcontent) [FWS] DQUOTE
                $quoted = '\x22[^\x5c\x22]+\x22';
                
                if (preg_match('/^' . $quoted . '$/', $localPart)) {
                    $localResult = true;
                }
            }
            
            /**
             * @todo if not matched, try obsolete format
             */ 
            
            
            if ($localResult && $domainResult) {
                return true;
            }
            
        } 
        
        return false;
        
    }
  