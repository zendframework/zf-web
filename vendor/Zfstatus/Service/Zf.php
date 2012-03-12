<?php
class Zfstatus_Service_Zf
{
    /**
     * ZF2 Components
     *
     * This could probably be semi-automated? 
     * They are each an array because I had some idea to add meta data to each 
     * component but now I cannot remember what that was. Oh well.
     * 
     * @var array
     * @access protected
     */
    protected $_components = array (
        'Documentation'       => array(),
        'Zend\Acl'            => array(),
        'Zend\Amf'            => array(),
        'Zend\Application'    => array(),
        'Zend\Authentication' => array(),
        'Zend\Barcode'        => array(),
        'Zend\Cache'          => array(),
        'Zend\Captcha'        => array(),
        'Zend\Cloud'          => array(),
        'Zend\Code'           => array(),
        'Zend\CodeGenerator'  => array(),
        'Zend\Config'         => array(),
        'Zend\Console'        => array(),
        'Zend\Controller'     => array(),
        'Zend\Crypt'          => array(),
        'Zend\Currency'       => array(),
        'Zend\Date'           => array(),
        'Zend\Db'             => array(),
        'Zend\Di'             => array(),
        'Zend\Dojo'           => array(),
        'Zend\Dom'            => array(),
        'Zend\EventManager'   => array(),
        'Zend\Feed'           => array(),
        'Zend\File'           => array(),
        'Zend\Filter'         => array(),
        'Zend\Form'           => array(),
        'Zend\GData'          => array(),
        'Zend\Http'           => array(),
        'Zend\Ical'           => array(),
        'Zend\InfoCard'       => array(),
        'Zend\Json'           => array(),
        'Zend\Layout'         => array(),
        'Zend\Ldap'           => array(),
        'Zend\Loader'         => array(),
        'Zend\Locale'         => array(),
        'Zend\Log'            => array(),
        'Zend\Mail'           => array(),
        'Zend\Markup'         => array(),
        'Zend\Measure'        => array(),
        'Zend\Memory'         => array(),
        'Zend\Mime'           => array(),
        'Zend\Mvc'            => array(),
        'Zend\Navigation'     => array(),
        'Zend\OAuth'          => array(),
        'Zend\OpenId'         => array(),
        'Zend\Paginator'      => array(),
        'Zend\Pdf'            => array(),
        'Zend\ProgressBar'    => array(),
        'Zend\Queue'          => array(),
        'Zend\Reflection'     => array(),
        'Zend\Rest'           => array(),
        'Zend\Search'         => array(),
        'Zend\Serializer'     => array(),
        'Zend\Server'         => array(),
        'Zend\Service'        => array(),
        'Zend\Session'        => array(),
        'Zend\Soap'           => array(),
        'Zend\Stdlib'         => array(),
        'Zend\Tag'            => array(),
        'Zend\Test'           => array(),
        'Zend\Text'           => array(),
        'Zend\TimeSync'       => array(),
        'Zend\Tool'           => array(),
        'Zend\Translator'     => array(),
        'Zend\Uri'            => array(),
        'Zend\Validator'      => array(),
        'Zend\View'           => array(),
        'Zend\Wildfire'       => array(),
        'Zend\XmlRpc'         => array(),
    );

    /**
     * _gh 
     * 
     * @var ZfStatus_Service_Github
     */
    protected $_gh;

    /**
     * __construct 
     * 
     * @param mixed $gh 
     * @access public
     * @return void
     */
    public function __construct($gh)
    {
        $this->setGh($gh);
    }

    /**
     * getRecentActivity 
     * 
     * @param Git_Repo $repo 
     * @param string $sortBy alpha|recent
     * @param bool $justCommits
     * @access public
     * @return array
     */
    public function getRecentActivity($repo, $sortBy = 'alpha', $justCommits = false)
    {
        $componentIndex = array();
        $commitsByBranch = $repo->getCommitsByBranch(4, '--no-merges --first-parent', array('origin'), array('master'));
        foreach ($commitsByBranch as $remote => $branches) {
            foreach ($branches as $branch => $commits) {
                foreach ($commits as $hash) {
                    $commit = $repo->getCommit($hash);
                    $gitHubUsername = $this->getGh()->emailToUsername($commit->getAuthorEmail(), $repo);
                    // this helps filter out irrelevant branches / commits
                    if ($gitHubUsername != $remote) continue;
                    $components = $this->_commitToComponents($commit);
                    $sortFunc = function($a, $b){
                        if (is_array($a) && isset($a['latest'])) {
                            if ($a['latest']->getAuthorTime() > $b['latest']->getAuthorTime()) return 0;
                            return 1;
                        }
                        if ($a->getAuthorTime() > $b->getAuthorTime()) return 0;
                        return 1;
                    };
                    $absBranch = $remote.'/'.$branch;
                    if ($justCommits) {
                        $componentIndex['commits'][$hash] = $commit;
                        uasort($componentIndex['commits'], $sortFunc);
                        $componentIndex['meta'][$hash]['components'] = $components;
                        $componentIndex['meta'][$hash]['remote'] = $remote;
                        $componentIndex['meta'][$hash]['branch'] = $branch;
                        continue;
                    }
                    foreach ($components as $component) {
                        // comment the following line out to include components 
                        // that are not in the list above (no validation)
                        // if (!in_array($component, $this->getComponents())) continue;
                        $componentIndex[$component]['branches'][$absBranch]['remote'] = $remote;
                        $componentIndex[$component]['branches'][$absBranch]['branch'] = $branch;
                        $componentIndex[$component]['branches'][$absBranch]['gravatar'] = $commit->getAuthorGravatar();
                        $componentIndex[$component]['branches'][$absBranch]['commits'][$hash] = $commit;
                        uasort($componentIndex[$component]['branches'][$absBranch]['commits'], $sortFunc);
                        $latest = $this->_mostRecentCommit(@$componentIndex[$component]['branches'][$absBranch]['latest'], $commit);
                        $componentIndex[$component]['branches'][$absBranch]['latest'] = $latest;
                        uasort($componentIndex[$component]['branches'], $sortFunc);
                        $latest = $this->_mostRecentCommit(@$componentIndex[$component]['latest'], $commit);
                        $componentIndex[$component]['latest'] = $latest;
                    }
                }
            }
        }

        if (!$justCommits){ 
            if ($sortBy == 'recent') {
                // Sort by most recently updated component
                uasort($componentIndex, $sortFunc);
            } else {
                ksort($componentIndex);
            }
        }
        return $componentIndex;
    }

    protected function _mostRecentCommit($commit1 = NULL, $commit2)
    {
        if (!$commit1 || $commit1->getAuthorTime() < $commit2->getAuthorTime()) return $commit2;
        return $commit1;
    }

    public function getComponents()
    {
        return array_keys($this->_components);
    }

    protected function _commitToComponents($commit)
    {
        $components = array('nomatch' => array());
        if (count($commit->getFiles()) > 0) {
            foreach ($commit->getFiles() as $f) {
                $f = $f['file'];
                if ($c = $this->_filenameToComponentName($f)) {
                    if (!isset($components[$c])) $components[$c] = 0;
                    $components[$c]++;
                } else {
                    if (!isset($components['nomatch'][$f])) $components['nomatch'][$f] = 0;
                    $components['nomatch'][$f]++;
                }
            }
        }
        unset($components['nomatch']); // comment out for debugging unmatched components
        if (count(array_unique($components)) > 1) arsort($components);
        $components = array_keys($components); 
        return $components;
    }

    protected function _filenameToComponentName($filename)
    {
        $parts = explode('/', $filename);
        if (count($parts) > 1 && $parts[0] == 'documentation') return 'Documentation'; 
        if (count($parts) < 2 || $parts[1] != 'Zend') return false;
        return $parts[1].'\\'.$parts[2];
    }

 
    /**
     * Get gh.
     *
     * @return gh
     */
    public function getGh()
    {
        return $this->_gh;
    }
 
    /**
     * Set gh.
     *
     * @param $gh the value to be set
     */
    public function setGh($gh)
    {
        $this->_gh = $gh;
        return $this;
    }
}
