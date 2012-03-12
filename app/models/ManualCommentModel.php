<?php

/**
 * ManualCommentModel
 * 
 * This model is responsible for processing comments that for individual pages (Sections) of the
 * manual.  It will use akismet to determine the 'status' of each comment which will ultimately
 * decide which to show on the frontend.
 * 
 * @author ralphschindler
 *
 */
class ManualCommentModel
{
    protected static $_statusTypes = array(
        'VISIBLE',
        'HIDDEN',
        'UNPROCESSED',
        'DELETE'
        );
    
    /**
     * @var Zend_Db_Table
     */
    protected $_table = null;
    
    public static function getStatusTypes()
    {
        return self::$_statusTypes;
    }
    
    /**
     * __constructor
     */
    public function __construct()
    {
        $this->_table = new Zend_Db_Table('manual_comment');
    }

    /**
     * Retrieve all comments, in descending order by date created
     * 
     * @return Zend_Paginator
     */
    public function getAllCommentsAsPaginator($filter = 'all')
    {
        /* @var $select Zend_Db_Select */
        $select = $this->_table->select();
        if ($filter !== 'all') {
            $select->where('status = ?', strtoupper($filter));
        }
        $select->order('created_on DESC');

        $adapter   = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
        return $paginator;
    }
    
    /**
     * getComments()
     * 
     * @param $sectionName
     * @param $versionNumber
     * @return array
     */
    public function getComments($sectionName, $versionNumber = null)
    {
        if ($sectionName == null) {
            return array();
        }
        
        $select = $this->_table->select();
        $select->where('section_name = ?', $sectionName);
        
        if ($versionNumber) {
            $select->where('version <= ?', $this->_versionAsInteger($versionNumber));
        }
        
        $rowset = $this->_table->fetchAll($select)->toArray();
        
        // tansform the version number
        foreach ($rowset as $commentIndex => $comment) {
            $rowset[$commentIndex]['version'] = $this->_versionAsDotted($comment['version']);
        }
        
        return $rowset;
    }

    /**
     * Fetch a single comment
     * 
     * @param  int $id 
     * @return Zend_Db_Table_Row
     */
    public function getComment($id)
    {
        $select = $this->_table->select();
        $select->where('id = ?', $id);

        return $this->_table->fetchRow($select);
    }
    
    /**
     * processNewComment()
     * 
     * @param $commentInfo
     */
    public function processNewComment($commentInfo)
    {
        $status = 'UNPROCESSED';
        
        $status = $this->_processGetStatus($commentInfo);
        
        $commentRowData = array(
            'section_name'    => $commentInfo['section'],
            'version'         => $this->_versionAsInteger($commentInfo['version']),
            'commenter_name'  => $commentInfo['name'],
            'commenter_email' => $commentInfo['email'],
            'commenter_url'   => (isset($commentInfo['url'])) ? $commentInfo['url'] : '',
            'status'          => $status,
            'body'            => $commentInfo['comment'],
            'created_on'      => new Zend_Db_Expr('NOW()')
        );

        $this->_table->insert($commentRowData);
    }
    
    /**
     * 
     * @param int $commentId
     * @param string $newStatus
     */
    public function changeStatus($commentId, $newStatus)
    {
        if (!in_array($newStatus, self::$_statusTypes)) {
            throw new Exception($newStatus . ' is not a valid status type.');
        }
        $comments = $this->_table->find($commentId);
        if (count($comments) != 1) {
            throw new Exception('Comment by id ' . $commentId . ' was not found.');
        }
        $comment = $comments->current();
        $comment->status = $newStatus;
        $comment->save();
        return;
    }
    
    public function purgeCommentsMarkedAsDelete()
    {
        return $this->_table->delete('status = "DELETE"');
    }
    
    /**
     * _versionAsInteger()
     * 
     * convert the dotted version number to an integer for storage in the db
     * 
     * @param string $dottedVersion
     * @return int
     */
    protected function _versionAsInteger($dottedVersion)
    {
        list($major, $minor) = explode('.', $dottedVersion);
        $string              = sprintf('%d%02d', $major, $minor);
        return (int) $string;
    }
    
    /**
     * _versionAsDotted
     * 
     * convert the integer
     * 
     * @param int $integerVersion
     * @return string
     */
    protected function _versionAsDotted($integerVersion)
    {
        $minor = substr($integerVersion, -2);
        $major = substr($integerVersion, 0, -2);
        return (int) $major . '.' . (int) $minor;
    }
    
    protected function _processGetStatus($commentInfo)
    {
        $config     = Zend_Registry::get('config');
        $akismetKey = isset($config->service->akismet->key) ? $config->service->akismet->key : false;
        $akismetUrl = isset($config->service->akismet->url) ? $config->service->akismet->url : false;
        
        if (!$akismetKey || !$akismetUrl) {
            return 'UNPROCESSED';
        }
        
        $akismet = new Zend_Service_Akismet($akismetKey, $akismetUrl);
        
        $akismetData = array(
            'user_ip'              => $commentInfo['ip'],
            'user_agent'           => $commentInfo['userAgent'],
            'comment_type'         => 'comment',
            'comment_author'       => $commentInfo['name'],
            'comment_author_email' => $commentInfo['email'],
            'comment_content'      => $commentInfo['comment']
        );
            
        if ($akismet->isSpam($akismetData)) {
            return 'HIDDEN';
        }

        return 'VISIBLE';
    }
    
}
