<?php

class BDBLdap extends Zend_Ldap
{
	const LDAP_USER_NOT_MEMBER_OF_GROUP = -5;




 	/**
     * Validate group membership
     *
     * Searches the LDAP server for group membership of the
     * supplied username.  Quotes all LDAP filter meta characters in
     * the user name before querying the LDAP server.
     *
     * @param  string Distinguished Name of the authenticated User
     * @return boolean
     */
    public function checkGroupMembership($user)
    {
    	if (!is_resource($this->_resource))
            $this->connect();

		$userDn = $this->_getAccountDn($user);

		foreach ($this->_options['groups'] as $group) {
	        // make filter
	        $filter = sprintf('(&(%s=%s)(%s=%s)%s)',
	                          $this->_options['groupAttr'],
	                          $group,
	                          $this->_options['memberAttr'],
	                          $this->_quoteFilterString($userDn),
	                          $this->_options['groupFilter']);

	        // make search base dn
	        $search_basedn = $this->_options['groupDn'];
	        if ($search_basedn != '' && substr($search_basedn, -1) != ',') {
	            $search_basedn .= ',';
	        }
	        $search_basedn .= $this->_options['baseDn'];

	        $func_params = array($this->_resource, $search_basedn, $filter,
	                             array($this->_options['memberAttr']));
	        $func_name = 'ldap_search';

	        //echo "Searching with $func_name and filter $filter in $search_basedn";

	        // search
	        if (($result_id = @call_user_func_array($func_name, $func_params)) != false) {
	            if (@ldap_count_entries($this->_resource, $result_id) == 1) {
	                @ldap_free_result($result_id);
	                //echo 'User is member of group';
	                return true;
	            }
	        }
		}
        // default
        throw new Zend_Ldap_Exception(null,
               'User is NOT member of any group!',
               BDBLdap::LDAP_USER_NOT_MEMBER_OF_GROUP);
        return false;
    }



 	/**
     * Escapes LDAP filter special characters as defined in RFC 2254.
     *
     * @access private
     * @param string Filter String
     */
    protected function _quoteFilterString($filter_str)
    {
        $metas        = array(  '\\',  '*',  '(',  ')',   "\x00");
        $quoted_metas = array('\\\\', '\*', '\(', '\)', "\\\x00");
        return str_replace($metas, $quoted_metas, $filter_str);
    }

/**
     * Sets the options used in connecting, binding, etc.
     *
     * Valid option keys:
     *  host
     *  port
     *  useSsl
     *  username
     *  password
     *  bindRequiresDn
     *  baseDn
     *  accountCanonicalForm
     *  accountDomainName
     *  accountDomainNameShort
     *  accountFilterFormat
     *  allowEmptyPassword
     *  useStartTls
     *
     * @param  array $options Options used in connecting, binding, etc.
     * @return Zend_Ldap Provides a fluent interface
     * @throws Zend_Ldap_Exception
     */
    public function setOptions(array $options)
    {
        $permittedOptions = array(
            'host'                      => null,
            'port'                      => null,
            'useSsl'                    => null,
            'username'                  => null,
            'password'                  => null,
            'bindRequiresDn'            => null,
            'baseDn'                    => null,
            'accountCanonicalForm'      => null,
            'accountDomainName'         => null,
            'accountDomainNameShort'    => null,
            'accountFilterFormat'       => null,
            'allowEmptyPassword'        => null,
            'useStartTls'               => null,

       		'groups' 		            => null,
        	'groupDn' 		            => null,
        	'groupAttr' 		        => null,
	        'groupFilter' 		        => null,
	        'memberAttr' 		        => null,
        );

        $diff = array_diff_key($options, $permittedOptions);
        if ($diff) {
            list($key, $val) = each($diff);
            require_once 'Zend/Ldap/Exception.php';
            throw new Zend_Ldap_Exception(null, "Unknown Zend_Ldap option: $key");
        }

        foreach ($permittedOptions as $key => $val) {
            if (!array_key_exists($key, $options)) {
                $options[$key] = null;
            } else {
                /* Enforce typing. This eliminates issues like Zend_Config_Ini
                 * returning '1' as a string (ZF-3163).
                 */
                switch ($key) {
                    case 'port':
                    case 'accountCanonicalForm':
                        $options[$key] = (int)$options[$key];
                        break;
                    case 'useSsl':
                    case 'bindRequiresDn':
                    case 'allowEmptyPassword':
                    case 'useStartTls':
                        $val = $options[$key];
                        $options[$key] = $val === true ||
                                $val === '1' ||
                                strcasecmp($val, 'true') == 0;
                        break;
                }
            }
        }

        $this->_options = $options;

        return $this;
    }
}
?>