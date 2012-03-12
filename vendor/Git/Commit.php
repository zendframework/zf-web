<?php
class Git_Commit
{
    /**
     * _hash 
     * 
     * @var string
     */
    protected $_hash;

    /**
     * _tree 
     * 
     * @var string
     */
    protected $_tree;

    /**
     * _parents 
     * 
     * @var array
     */
    protected $_parents;

    /**
     * _authorName 
     * 
     * @var string
     */
    protected $_authorName;

    /**
     * _authorEmail 
     * 
     * @var string
     */
    protected $_authorEmail;

    /**
     * _authorTime 
     * 
     * @var DateTime
     */
    protected $_authorTime;

    /**
     * _committerName 
     * 
     * @var string
     */
    protected $_committerName;

    /**
     * _committerEmail 
     * 
     * @var string
     */
    protected $_committerEmail;

    /**
     * _committerTime 
     * 
     * @var DateTime
     */
    protected $_committerTime;

    /**
     * _subject 
     * 
     * @var string
     */
    protected $_subject;

    /**
     * _message 
     * 
     * @var string
     */
    protected $_message;

    /**
     * _files 
     * 
     * @var array
     */
    protected $_files;
 
    /**
     * Get hash.
     *
     * @return hash
     */
    public function getHash()
    {
        return $this->_hash;
    }
 
    /**
     * Set hash.
     *
     * @param $hash the value to be set
     */
    public function setHash($hash)
    {
        $this->_hash = $hash;
        return $this;
    }
 
    /**
     * Get tree.
     *
     * @return tree
     */
    public function getTree()
    {
        return $this->_tree;
    }
 
    /**
     * Set tree.
     *
     * @param $tree the value to be set
     */
    public function setTree($tree)
    {
        $this->_tree = $tree;
        return $this;
    }
 
    /**
     * Get parents.
     *
     * @return parents
     */
    public function getParents()
    {
        return $this->_parents;
    }
 
    /**
     * Set parents.
     *
     * @param $parents the value to be set
     */
    public function setParents($parents)
    {
        $this->_parents = $parents;
        return $this;
    }
 
    /**
     * Get authorName.
     *
     * @return authorName
     */
    public function getAuthorName()
    {
        return $this->_authorName;
    }
 
    /**
     * Set authorName.
     *
     * @param $authorName the value to be set
     */
    public function setAuthorName($authorName)
    {
        $this->_authorName = $authorName;
        return $this;
    }
 
    /**
     * Get authorEmail.
     *
     * @return authorEmail
     */
    public function getAuthorEmail()
    {
        return $this->_authorEmail;
    }

    /**
     * getAuthorGravatar 
     * 
     * @return string
     */
    public function getAuthorGravatar()
    {
        return md5(strtolower(trim($this->getAuthorEmail())));
    }
 
    /**
     * Set authorEmail.
     *
     * @param $authorEmail the value to be set
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->_authorEmail = $authorEmail;
        return $this;
    }
 
    /**
     * Get authorTime.
     *
     * @return authorTime
     */
    public function getAuthorTime()
    {
        return $this->_authorTime;
    }
 
    /**
     * Set authorTime.
     *
     * @param $authorTime the value to be set
     */
    public function setAuthorTime($authorTime)
    {
        $this->_authorTime = new DateTime($authorTime);
        return $this;
    }
 
    /**
     * Get committerName.
     *
     * @return committerName
     */
    public function getCommitterName()
    {
        return $this->_committerName;
    }
 
    /**
     * Set committerName.
     *
     * @param $committerName the value to be set
     */
    public function setCommitterName($committerName)
    {
        $this->_committerName = $committerName;
        return $this;
    }
 
    /**
     * Get committerEmail.
     *
     * @return committerEmail
     */
    public function getCommitterEmail()
    {
        return $this->_committerEmail;
    }

    /**
     * getCommitterGravatar 
     * 
     * @return string
     */
    public function getCommitterGravatar()
    {
        return md5(strtolower(trim($this->getCommitterEmail())));
    }
 
    /**
     * Set committerEmail.
     *
     * @param $committerEmail the value to be set
     */
    public function setCommitterEmail($committerEmail)
    {
        $this->_committerEmail = $committerEmail;
        return $this;
    }
 
    /**
     * Get committerTime.
     *
     * @return committerTime
     */
    public function getCommitterTime()
    {
        return $this->_committerTime;
    }
 
    /**
     * Set committerTime.
     *
     * @param $committerTime the value to be set
     */
    public function setCommitterTime($committerTime)
    {
        $this->_committerTime = new DateTime($committerTime);
        return $this;
    }

    /**
     * Get subject.
     *
     * @return subject
     */
    public function getSubject()
    {
        return $this->_subject;
    }
 
    /**
     * Set subject.
     *
     * @param $subject the value to be set
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }
 
    /**
     * Get message.
     *
     * @return message
     */
    public function getMessage()
    {
        return $this->_message;
    }
 
    /**
     * Set message.
     *
     * @param $message the value to be set
     */
    public function setMessage($message)
    {
        $this->_message = trim($message);
        $lines = explode("\n", $this->_message);
        $this->setSubject($lines[0]);
        return $this;
    }
 
    /**
     * Get files.
     *
     * @return files
     */
    public function getFiles()
    {
        return $this->_files;
    }
 
    /**
     * Set files.
     *
     * @param $files the value to be set
     */
    public function setFiles($files)
    {
        $this->_files = array();
        foreach ($files as $file) {
            if (count($file) == 7) {
                unset($file[0], $file[1], $file[2], $file[3]);
                // <OCD>
                $file['insertions'] = (int) $file['insertions'];
                $file['deletions'] = (int) $file['deletions'];
                // </OCD>
                $this->_files[] = $file;
            }
        }
        return $this;
    }
}
