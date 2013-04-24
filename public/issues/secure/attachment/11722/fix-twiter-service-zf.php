/**
 * Retrieve direct messages for the current user
 *
 * $params may include one or more of the following keys
 * - since: return results only after the date specified
 * - since_id: return statuses only greater than the one specified
 * - page: return page X of results
 *
 * @param  array $params
 * @return Zend_Rest_Client_Result
 */
public function directMessageMessages(array $params = array())
{
    $this->_init();
    $path = '/direct_messages.xml';
	$_params = array();
    foreach ($params as $key => $value) {
        switch (strtolower($key)) {
            case 'since':
                $this->_setDate($value);
                break;
            case 'since_id':
				$_params['since_id'] = (int) $value;
                break;
            case 'page':
				$_params['page'] = (int) $value;
                break;
            default:
                break;
        }
    }
    $response = $this->restGet($path,$_params);
    return new Zend_Rest_Client_Result($response->getBody());
}

/**
 * Retrieve list of direct messages sent by current user
 *
 * $params may include one or more of the following keys
 * - since: return results only after the date specified
 * - since_id: return statuses only greater than the one specified
 * - page: return page X of results
 *
 * @param  array $params
 * @return Zend_Rest_Client_Result
 */
public function directMessageSent(array $params = array())
{
    $this->_init();
    $path = '/direct_messages/sent.xml';
    foreach ($params as $key => $value) {
        switch (strtolower($key)) {
            case 'since':
                $this->_setDate($value);
                break;
            case 'since_id':
				$_params['since_id'] = (int) $value;
                break;
            case 'page':
				$_params['page'] = (int) $value;
                break;
            default:
                break;
        }
    }
    $response = $this->restGet($path);
    return new Zend_Rest_Client_Result($response->getBody());
}
