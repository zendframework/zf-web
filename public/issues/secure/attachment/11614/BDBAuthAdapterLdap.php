<?php

class BDBAuthAdapterLdap extends Zend_Auth_Adapter_Ldap
{


	/**
     * Authenticate the user
     *
     * @throws Zend_Auth_Adapter_Exception
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        /**
         * @see Zend_Ldap_Exception
         */
        require_once 'Zend/Ldap/Exception.php';

        $messages = array();
        $messages[0] = ''; // reserved
        $messages[1] = ''; // reserved

        $username = $this->_username;
        $password = $this->_password;

        if (!$username) {
            $code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            $messages[0] = 'A username is required';
            return new Zend_Auth_Result($code, '', $messages);
        }
        if (!$password) {
            /* A password is required because some servers will
             * treat an empty password as an anonymous bind.
             */
            $code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $messages[0] = 'A password is required';
            return new Zend_Auth_Result($code, '', $messages);
        }

        $ldap = $this->getLdap();

        $code = Zend_Auth_Result::FAILURE;
        $messages[0] = "Authority not found: $username";

        /* Iterate through each server and try to authenticate the supplied
         * credentials against it.
         */
        foreach ($this->_options as $name => $options) {

            if (!is_array($options)) {
                /**
                 * @see Zend_Auth_Adapter_Exception
                 */
                require_once 'Zend/Auth/Adapter/Exception.php';
                throw new Zend_Auth_Adapter_Exception('Adapter options array not in array');
            }
            $ldap->setOptions($options);

            try {

                $canonicalName = $ldap->getCanonicalAccountName($username);

                if ($messages[1])
                    $messages[] = $messages[1];
                $messages[1] = '';
                $messages[] = $this->_optionsToString($options);

                $ldap->bind($canonicalName, $password);


	            if (isset($options['groups'])) {
	            	if ($options['groups']!=null && is_array($options['groups'])) {
		            	// also check Group Membership!!
			    		$ldap->checkGroupMembership($canonicalName);
	            	} else {
	            		throw new Zend_Ldap_Exception(null,
								'config variable groups MUST be an array!',
								Zend_Ldap_Exception::LDAP_OPERATIONS_ERROR);
	            	}
		    	}


                $messages[0] = '';
                $messages[1] = '';
                $messages[] = "$canonicalName authentication successful";

                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $canonicalName, $messages);
            } catch (Zend_Ldap_Exception $zle) {

                /* LDAP based authentication is notoriously difficult to diagnose. Therefore
                 * we bend over backwards to capture and record every possible bit of
                 * information when something goes wrong.
                 */

                $err = $zle->getCode();

                if ($err == Zend_Ldap_Exception::LDAP_X_DOMAIN_MISMATCH) {
                    /* This error indicates that the domain supplied in the
                     * username did not match the domains in the server options
                     * and therefore we should just skip to the next set of
                     * server options.
                     */
                    continue;
                } else if ($err == Zend_Ldap_Exception::LDAP_NO_SUCH_OBJECT) {
                    $code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
                    $messages[0] = "Account not found: $username";
                } else if ($err == Zend_Ldap_Exception::LDAP_INVALID_CREDENTIALS) {
                    $code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
                    $messages[0] = 'Invalid credentials';
                } else {
                    $line = $zle->getLine();
                    $messages[] = $zle->getFile() . "($line): " . $zle->getMessage();
                    $messages[] = str_replace($password, '*****', $zle->getTraceAsString());
                    $messages[0] = 'An unexpected failure occurred';
                }
                $messages[1] = $zle->getMessage();
            }
        }

        $msg = isset($messages[1]) ? $messages[1] : $messages[0];
        $messages[] = "$username authentication failed: $msg";

        return new Zend_Auth_Result($code, $username, $messages);
    }



	/**
	 * returns BDBLdap Object
	 *
	 * @return BDBLdap
	 */
	public function getLdap()
    {
        if ($this->_ldap === null) {
            /**
             * @see Zend_Ldap
             */
            require_once 'BDBLdap.php';
            $this->_ldap = new BDBLdap();
        }
        return $this->_ldap;
    }

	/**
     * Converts options to string
     *
     * @param  array $options
     * @return string
     */
    protected function _optionsToString(array $options)
    {
        $str = '';
        foreach ($options as $key => $val) {
            if ($key === 'password')
                $val = '*****';
            if ($str)
                $str .= ',';
            $str .= $key . '=' . $val;
        }
        return $str;
    }
}
?>