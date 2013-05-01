<?php
/**
 * @category   SWAT
 * @package    Controller
 * @copyright  Copyright (c) 2009 MIH SWAT
 * @author 	   Guilherme Blanco <gblanco@mihinternet.com>
 */

/**
 * @category   SWAT
 * @package    Controller
 * 
 * Controller for ajax calls for invites
 */
class WikiarticleController extends Zend_Controller_Action 
{ 
	/**
	 * Disable layout
	 *
	 */
	public function preDispatch()
	{
	    $this->_helper->layout()->disableLayout();
	    Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
	}
	
	
	/**
	 * CLI Bot action
	 * 
	 */
	public function botAction()
	{
		// Retrieve configuration
		$config = Zend_Registry::get('config');
		
		// URI to be called
		$uri = $config->snsServerUrlScheme . '://'
			 . $config->snsServerUrlHost 
			 . $config->snsServerUrlPath . 'default/wikiarticle/create';
		
		try {
			// Connect to POP3 mail storage
		    $mail = new Zend_Mail_Storage_Pop3(array(
		    	'host' => $config->wikiarticle->pop3->host, 
		    	'port' => $config->wikiarticle->pop3->port, 
		    	'user' => $config->wikiarticle->pop3->user, 
		    	'password' => $config->wikiarticle->pop3->pass
		    ));
		} catch (Exception $ex) {
			//SWAT_Log::get()->MailPuller()->err($ex->getMessage());
			Zend_Registry::get('log')->err($ex->getMessage());		
		}
		
		// Generate mail puller instance
		$puller = new SWAT_Mail_Puller();
		$puller->setFileDir(sys_get_temp_dir());
		$puller->setFileTypes(
		    array_map('trim', explode(',', $config->wikiarticleAllowedMimeTypes))
		);
		
	    //echo 'We have ' . $mail->countMessages() . ' messages to process.<br />';
	    //echo 'Attachments will be stored in directory: ' . sys_get_temp_dir() . '<br />';
	    
	    // Foreach mail found
	    foreach ($mail as $number => $message) {
	    	//echo 'Found message with title: ' . $message->subject . '<br />';
	    	
	    	// Check if it's a wiki article to be created
	        if (strtoupper(substr($message->subject, 0, 5)) === 'WIKI:') {
	    		try {
		        	// Retrieve message information
		            $info = $puller->getMessageInfo($message);
		            
		            // Post found information to wiki article creator
		            $client = new Zend_Http_Client($uri);
				    $client->setRawData(Zend_Json::encode($info));
				    $response = $client->request(Zend_Http_Client::POST);
				    
				    //echo 'Response: <pre>' . var_export($response->getBody(), true) . '</pre>';			    
				    
				    // Switch to each response status
				    switch ($response->getStatus()) {
				        case 400:
				            throw new Exception('Invalid request.');
				        break;
				            
				        case 500:
				            throw new Exception('Internal server error.');
				        break;
				            
				        default:
				        	// Decode response
				        	$body = SWAT_Encoder::decode($response->getRawBody());
				        	
				        	// If error code is not 201, we need to report the issue
				        	if ($body['code'] != 201) {
				        		throw new Exception($body['message']);
				        	}
				        	
				        	// Generate log entry
				        	//SWAT_Log::get()->MailPuller()->debug($body['message']);
				        	Zend_Registry::get('log')->debug($body['message']);
				        break;
				    }
	        	} catch (Exception $ex) {
				    //SWAT_Log::get()->MailPuller()->err($ex->getMessage());
				    Zend_Registry::get('log')->err($ex->getMessage());
				}
	        }
	        
	    	$mail->noop();
	    }		
	}
	
	
	/**
	 * Create article action
	 *
	 */
    public function createAction() 
    { 
    	// Build basic return data
    	$return = array(
    		'code'    => 400,
    		'message' => 'Unable to create Wiki Article.',
    	);
    	
    	// Retrieve configuration
    	$config = Zend_Registry::get('config');
    	
    	// Decode data we received from HTTP request
    	$data = SWAT_Encoder::decode($this->getRequest()->getRawBody());
    	
    	// Check for author existance
    	$authorEmail = isset($data['author']) 
    		? $data['author'] : $this->getRequest()->getParam('author');
    	$authorId = MKX_User::getUserIdByEmail($authorEmail);
    	
    	if ( ! empty($authorId)) {
    		// Assign found user as current logged user
    		$userNameSpace = new Zend_Session_Namespace('User');
    		$userNameSpace->userId = $authorId;
    		
    		// Retrieve and inspect if we do not already have an article with this title
    		$articleSubject = isset($data['subject']) 
	    		? $data['subject'] : $this->getRequest()->getParam('subject');
	    		
	    	if (substr($articleSubject, 0, 5) == 'WIKI:') {
	    		$articleSubject = substr($articleSubject, 5);
	    	}
	    	
    		$articleSubject = str_replace(' ', '_', ucfirst(trim($articleSubject)));
    		$article = MKX_Wiki_Article::getPageByTitle($articleSubject);
    		
    		if (empty($article) && $articleSubject != '') {
    			// We need to bring all CC and BCC to our userland
    			$cc = trim(isset($data['cc']) ? $data['cc'] : $this->getRequest()->getParam('cc', ''));
    			$ccUserEmails = ($cc != '') ? MKX_User::getUserIdsByEmails($cc) : array();
    			
    			$bcc = trim(isset($data['bcc']) ? $data['bcc'] : $this->getRequest()->getParam('bcc', ''));
    			$bccUserEmails = ($bcc != '') ? MKX_User::getUserIdsByEmails($bcc) : array();
    			
    			// Defining ad-hoc group
    			$requestedUsersPermissions = array_unique(array_merge(
    				array($authorId), $ccUserEmails, $bccUserEmails
    			));
    			$restriction = $this->createAdHocGroup($requestedUsersPermissions);
    			
    			// Prepatring body content of article
    			$body = isset($data['body']) ? $data['body'] : $this->getRequest()->getParam('body', '');
				$rawBody = $body = trim(preg_replace('/<accesscontrol>.*?<\/accesscontrol>/', '', $body));   			    			
				$body .= "\n\n" . $restriction;
				
    			// Loop through each attachment and save it as a resource in Wiki
    			$attachments = isset($data['files']) ? $data['files'] : $this->getRequest()->getParam('files', null);
		    		
    			if ( ! empty($attachments)) {
    				foreach ($attachments as $file) {
    					// Check if file is not already saved (do not save)
    					$file['name'] = str_replace(' ', '_', $file['name']);
    					$fileArticle = MKX_Wiki_Article::getPageByTitle($file['name']);
            
                        if (empty($fileArticle)) {
	    					try {
	    						// Upload and save the resource
	    						MKX_Wiki::createResource(MKX_Wiki::FILE_NAMESPACE, $authorId, $authorEmail, $articleSubject, $body, $file);
	    						
	    						if ($rawBody == '') {
	    							$return['code'] = 201;
									$return['message'] = 'Wiki resource created successfully';
	    						}
	    					} catch (Exception $e) {
								$return['message'] = $e->getMessage();
							}
                        }
    				}
    			}
    			
    			// Check if we are delaing with a single resources upload 
				// or with a true article creation
				if ($rawBody != '') {
					try {
	    				// Creating the article
	    				MKX_Wiki::createArticle(MKX_Wiki::PAGE_NAMESPACE, $authorId, $authorEmail, $articleSubject, $body);
	    				
						$return['code'] = 201;
						$return['message'] = 'Wiki article created successfully';
					} catch (Exception $e) {
						$return['message'] = $e->getMessage();
					}
				}
    		} else {
    			$return['message'] = 'Unable to create Wiki article, page "' . $articleSubject . '" already exists';
    		}
    	} else {
    		$return['message'] = 'Unable to find MKX User with this email: ' . $authorEmail;
    	}
    	
    	// Print our final result
    	print SWAT_Encoder::encode($return);
    }
    
    
    /**
     * Create and associate defined users in an ad-hoc group
     *
     */
    private function createAdHocGroup($requestedUsersPermissions = array())
    {
    	// Blank permissions
    	$permissions = array();
	    
	    // Checking for user specific restriction
	    if ( ! empty($requestedUsersPermissions)) {    
	        // We need to find each user ID for each user (name or email).
	        $userIds = MKX_UserManager::getUserIdByNameOrEmail($requestedUsersPermissions);
	        
	        // Now we prepare our own desired group name
	        $groupName = SWAT_Guid::generate();
	        
	        // Create group and check for errors
	        $result = MKX_GroupManager::createGroupWithUsers($groupName, $userIds, MKX_RestrictedGroup::SOURCE_WIKI);
	        
	        // Include ad-hoc group in the list of permissions
	        $permissions[] = $groupName;
	    }
	    
	    // If we have any permission assigned, prepare and return it
	    return (count($permissions) > 0) 
	    	? '<accesscontrol>' . implode(',,', $permissions) . '</accesscontrol>' : '';
    }
}