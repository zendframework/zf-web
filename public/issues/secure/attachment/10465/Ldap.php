<?php

/**
 * This class is an adapter for the Zend_Auth in order to authenticate a user
 * against a LDAP repository.
 * This is will require some config settings, as the server, port, etc.
 *
 * The authentication requires a login and password.
 *
 *
 * some questions
 * - do we need to check if the ldap extension has been loaded or not?
 *
 * TODO :
 * - check for the need of ldap_sasl_bind()
 * - implement a authentication depending on a group
 *
 *
 *
<code>

require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/Ldap.php';

//instanciate the Zend_auth object
$auth = Zend_Auth::getInstance();

//data that should come from the login form
$username="Vincent.Dupont";
$password="xxxxxxxxx";

//these are the config settings, needed to connect the the LDAP directory
$options = array(
   'host'=>'192.168.1.1', //server IP, mandatory
   'bind_dn'=>'cn=LDAPUser,OU=Users,DC=example,DC=com', //DN of the user that may browse the ldap
   'bind_pw'=>'xxxxxxx', //password
   'user_oc'=>'user', //ObjectClass for users
   'base_dn'=>'DC=example,DC=com', //base DN for all users
   'user_dn'=>'OU=Users', //dn for users
   'user_attr'=>'samAccountName', //the attribute that contains the user login
   //AD options
   'use_domain_from_email'=>false, //username is an email, split it to get the domain
   'domain'=>'', //NT domain to prepend to the username, this is used with the direct_bind on AD
   'use_direct_bind'=>false//force the auth based only on binding by username and password provided by user
);


try {
  // Set up the authentication adapter, this may throw an error
  $authAdapter = new Zend_Auth_Adapter_Ldap($username, $password, $options);
  // Attempt authentication, saving the result
  $result = $auth->authenticate($authAdapter);
}catch (Exception $e){
    print_r($e);
}
if (!$result->isValid()) {
    // Authentication failed; print the reasons why
    foreach ($result->getMessages() as $message) {
        echo "$message<br>";
    }
} else{
    echo 'Authentication is Ok';
    echo '<br />';
    echo 'Username is :';
    echo $auth->getIdentity();
}
...
</code>
 *
 *
 * @category   Zend
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
*/

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/** This class is the LDAP adapter for Zend_Auth
 * It does require some config settings in an array:
 * - 'host' : mandatory, the host IP or servername.Multiple hosts may be
 * added, coma separated. This is a feature of the PHP LDAP extension :
 * if the first host is not available, the extension will try to silently
 * connect to the next one (PHP LDAP API feature).
 * - 'port' : the port of the LDAP service. default to 389
 * - 'url' : alternative connection string (see ldap_connect).
 * Will be preferred to the 'host' if provided
 * - 'protocol_version' : The LDAP protocol version. Default is V3

 * - 'use_direct_bind' : use only a direct bind with the username and password
 * - 'domain' : prepend a domain to the username
 * - 'use_domain_from_email' : if the username is formatted as an email,
 * parse the username to get the domain (vincent.dupont@example.com =>username = vincent.dupont, domain = example.com)
 *
 * - 'bind_dn'  : superuser bind username. This is used to browse the LDAP
 * directory to get the user information
 * - 'bind_pw'  : superuser bind password.
 * - 'base_dn'  : base LDAP path to start searching for the user information
 * - 'user_dn'  : user part of the LDAP path. This will be prepended to
 * base_dn when searching for the user information (Default:'')
 * - 'user_oc'  : the ObjectClass on which the user login will be searched
 * for (default :posixAccount,ActiveDirectory: user)
 * - 'user_attr': the attributes that correspond to the user login
 * (default: UID, ActiveDirectory : SamAccounName)
 */
class Zend_Auth_Adapter_Ldap implements Zend_Auth_Adapter_Interface
{

    /**
     * Connection and LDAP search settings
     *
     * This is a associative array with all needed config settings
     *
     *
     * @var array
     */
    protected $_params;

    /**
     * Error messages that will be returned along with the Zend_Auth_Result
     * object
     * This is a class member so as to be able to keep messages coming
     * from all methods of this class
     *
     * @var array
     */
    protected $_messages;



    /**
     * Constructor with mandatory arguments
     *
     * This will apply the settings and check if the php ldap extension is loaded.
     * This may throw a Zend_Auth_Adapter_Exception in case of problem
     * @see Zend_Auth_Adapter_Exception
     *
     * @param String $username
     * @param String $password
     * @param array $params
     */
    function __construct($username, $password, $params=array()){
       if(empty($username) ||
       empty($password) || //here I always expect a password. Is this ok?
       !isset($params['host']) ||
       empty($params['host'])){
           /**
             * @see Zend_Auth_Adapter_Exception
             */
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new Zend_Auth_Adapter_Exception(
                    'Username, Password and Host must be set before calling '
                    . 'authenticate()');
       }

       //test if the ldap module is loaded
       if(!extension_loaded('ldap')){
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new Zend_Auth_Adapter_Exception(
                    'Authenticate() : Ldap extension is not properly loaded');

       }

       $this->_messages=array();

       $this->_params                       = $this->_setDefaultParams();
       $this->_params['username']           = $username;
       $this->_params['posted_username']    = $this->_params['username'];
       $this->_params['password']           = $password;


       //apply the provided params
       foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }


       //set the variables depending on the params array
       if(isset($this->_params['use_domain_from_email']) && $this->_params['use_domain_from_email']){
           //the ensure the user has posted an email
           $at_pos = strpos($this->_params['posted_username'], '@');
           if(!$at_pos){
                $this->_messages[]=__CLASS__ . ' use_domain_from_email option is enabled, '.
                                            ' but user did not provide an email ';
           }
           else{
               //parse the email to get the domain
               $this->_params['username'] = substr($this->_params['posted_username'], 0, $at_pos);
               $this->_params['domain']   = substr($this->_params['posted_username'], $at_pos+1);
           }
       }
       //NT domain needs to be prepended to the username
       if(isset($this->_params['domain']) && !empty($this->_params['domain'])){
           $this->_params['username']        = $this->_params['domain']."\\".$this->_params['username'];
       }


    }//constructor



    /**
     * authenticate.
     *
     * This is the auth method that will be used by Zend_Auth
     * Return a Zend_Auth_Result object that will indicate the result status as well as a message
     *
     * @return Zend_Auth_Result
     * @see Zend_Auth_Result
     */
    public function authenticate(){
/*PROCESSES
1. connect to the server
2. bind so as to be able to query the server (with bindn or anonymously)
3. locate the dn of the user. this can be done using the user_dn
4. check if the user is ok regarding the groups
5. bind with the user_dn and password
*/
        // connect
        if (isset($this->_params['url']) && !empty($this->_params['url'])) {
            $conn = @ldap_connect($this->_params['url']);
        } else {
            $conn = @ldap_connect($this->_params['host'], $this->_params['port']);
       }


        if(!$conn){
            /**
             * @see Zend_Auth_Adapter_Exception
             * Note that even though the remote server has no LDAP service, you may not come here.
             * The error here does uniquely reflect a problem when creating the resource...
             */
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new Zend_Auth_Adapter_Exception('ldap connection failed '. 'connect()');
            $this->_messages[] = __CLASS__.' ldap_connect failed! '.__FUNCTION__;
        }

        //set the ldap version
        @ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, $this->_params['protocol_version']);


        //physical LDAP connection is ok, try to bind
        $ldap_bind = false;//default

        //a trick on Active Directory (at least) is to try to connect with the username and password
        if(isset($this->_params['use_direct_bind']) && $this->_params['use_direct_bind']){
        $ldap_bind = @ldap_bind($conn, $this->_params['username'], $this->_params['password']);
         if($ldap_bind==true){
             //this trick suceeded, so we can exit now
             //note : this may be no more possible if we allow more options in the way the auth is done
             ldap_close($conn);
             return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_params['posted_username']);
             //otherwise we will need a more complex procedure
         }
         else{
             //then report an loginfailed
             $this->_messages[] = __CLASS__ . ' use_direct_bind option failed';
         }
        }
        elseif(isset($this->_params['bind_dn']) && isset($this->_params['bind_pw'])){
            $ldap_bind = @ldap_bind($conn, $this->_params['bind_dn'], $this->_params['bind_pw']);//bind with the credentials
            $this->_messages[] = __CLASS__.' ldap_bind with binddn and bindpw credentials '.__FUNCTION__;
        }
        else {
           $this->_messages[] = __CLASS__.' ldap_bind anonymously '.__FUNCTION__;
           $ldap_bind = @ldap_bind($conn);//try to bind anonymously
        }



        //the ldap_bind is not successful
        if($ldap_bind!=true){
            ldap_close($conn);
            $this->_messages[]=__CLASS__. ' ldap_bind failed '.__FUNCTION__;
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->_params['username'], $this->_messages);
        }

        //the ldap_bind is successful
        //try to fetch the user and control the user_dn and group
        $filter = sprintf('(&(objectClass=%s)(%s=%s))', $this->_params['user_oc'], $this->_params['user_attr'], $this->_params['username']);
        // make search base dn
        $search_basedn = $this->_params['user_dn'];
        if ($search_basedn != '' && substr($search_basedn, -1) != ',') {
            $search_basedn .= ',';
        }
        $search_basedn .= $this->_params['base_dn'];

        $res = ldap_search($conn, $search_basedn, $filter, array($this->_params['user_attr']));
        if($res==false){
            ldap_close($conn);
            $this->_messages[]=__CLASS__. ' ldap_search failed '.__FUNCTION__;
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->_params['username'], $this->_messages);
        }
        $entries = ldap_get_entries($conn, $res);
        if($entries===false){
            ldap_close($conn);
            $this->_messages[]=__CLASS__. ' ldap_get_entries failed '.__FUNCTION__;
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_params['username'], $this->_messages);
        }
        //be sure we have only 1 entry
        if(ldap_count_entries($conn, $res)==1){

            //ok, the user data were found, try to re-bind useing his/her credentials
            //we need a dn to connect

            // then get the user dn
            $entry = ldap_first_entry($conn, $res);
            $user_dn  = ldap_get_dn($conn, $entry);
            ldap_free_result($res);

            $user_bind = @ldap_bind($conn, $user_dn, $this->_params['password']);
            if($user_bind){
                //bind OK for USERDN and PASSWORD
                //AUTH OK

                //Here we may check that the user is mmber of the requested group

                ldap_close($conn);
                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_params['username']);
            }

            else{
                //AUTH FAILED
                ldap_close($conn);
                $this->_messages[]=__CLASS__. ' Authentication failed, username or password invalid '.__FUNCTION__;
                return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->_params['username'], $this->_messages);
            }
        }
        else{
            //0 or 2 or more entries were found
            ldap_close($conn);
            $this->_messages[]=__CLASS__. ' ldap_get_entries return 0 or too many entries '.__FUNCTION__;
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, $this->_params['username'], $this->_messages);
        }
        ldap_close($conn);
        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->_params['username'], $this->_messages);
    }


    /**
     * set default config settings
     *
     * @return null
     */
   private function _setDefaultParams(){
        return array(
            'port'                  =>  389,
            'host'                  =>  null,
            'username'              =>  null,
            'password'              =>  null,
            'url'                   =>  null,
            'bind_dn'               =>  null,
            'bind_pw'               =>  null,
            'use_domain_from_email' =>  false,
            'domain'                =>  null,
            'protocol_version'      =>  3,
            'use_direct_bind'       =>  false,
            'user_oc'               =>  'posixAccount', //for Active Directory use user, else posixAccount
            'user_dn'               =>  '',
            'user_attr'             =>  'uid' //for Active Directory use samAccountName, else uid
        );
    }

}//class