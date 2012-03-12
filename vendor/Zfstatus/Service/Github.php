<?php
/**
 * @TODO: Use https://github.com/raphaelstolt/github-api-client
 */
class Zfstatus_Service_Github
{
    protected $_apiUrl = 'https://api.github.com/';

    protected $_cache;

    protected $_gravatarMap;

    public function __construct($cache)
    {
        $this->_cache = $cache;
    }

    /**
     * gravatarMap 
     *
     * Hack to figure out GitHub usernames from email
     * 
     * @param string $email 
     * @return string
     */
    public function emailToUsername($email, $repo = false)
    {
        $gravatarHash = md5(trim(strtolower($email)));
        if (!isset($this->_gravatarMap)) {
            $this->_gravatarMap = $this->_buildGravatarMap($repo);
        }
        if (is_array($this->_gravatarMap)) {
            if(isset($this->_gravatarMap[$gravatarHash])) {
                return $this->_gravatarMap[$gravatarHash];
            } else {
                $remoteHash = $this->_md5RemoteGravatar($gravatarHash);
                if(isset($this->_gravatarMap[$remoteHash])) {
                    return $this->_gravatarMap[$remoteHash];
                }
                return false;
            }
        }
        return false;
    }

    /**
     * _buildGravatarMap 
     *
     * Hack to figure out GitHub usernames from email.
     * 
     * @return array
     */
    protected function _buildGravatarMap($repo)
    {
        $remotes = $repo->getRemotes();
        foreach ($remotes as $remote => $url) {
            if ($remote != 'origin') continue;
            unset($remotes['origin']);
            // Must have an origin that is from GitHub.
            if (!isset($url) || strstr($url, '://github.com') === false) return false;
            $url = parse_url(substr($url, 0, -4));
            $return = $this->_get('repos'.$url['path'].'/contributors', rand(172800, 259200));
            $gravatars = array();
            foreach ($return as $contributor) {
                $gravatarUrl = parse_url($contributor->avatar_url);
                $gravatarHash = substr($gravatarUrl['path'], -32);
                if (!isset($gravatars[$gravatarHash])) {
                    $gravatars[$gravatarHash] = $contributor->login;
                    // hack for multiple email addresses...
                    // this will break if they have different commit/github 
                    // email addresses, but no gravatar set for either.
                    $gravatarFileHash = $this->_md5RemoteGravatar($gravatarHash);
                    $gravatars[$gravatarFileHash] = $contributor->login;
                    unset($remotes[$contributor->login]);
                }
            }
            break;
        }
        // for the ones left that we didn't find a gravatar for:
        foreach ($remotes as $remote => $url) {
            $url = parse_url(substr($url, 0, -4));
            $return = $this->_get('repos' . $url['path'], rand(172800, 259200));
            $gravatarUrl = parse_url($return->owner->avatar_url);
            $gravatarHash = substr($gravatarUrl['path'], -32);
            if (!isset($gravatars[$gravatarHash])) {
                $gravatars[$gravatarHash] = $return->owner->login;
                $gravatarFileHash = $this->_md5RemoteGravatar($gravatarHash);
                $gravatars[$gravatarFileHash] = $return->owner->login;
            }
        }
        return $gravatars;
    }

    protected function _addGravatarHash($gravatars, $gravatarHash)
    {
        if (!isset($gravatars[$gravatarHash])) {
            $gravatars[$gravatarHash] = $contributor->login;
            // hack for multiple email addresses...
            // this will break if they have different commit/github 
            // email addresses, but no gravatar set for either.
            $gravatarFileHash = $this->_md5RemoteGravatar("http://gravatar.com/avatar/{$gravatarHash}?s=5");
            $gravatars[$gravatarFileHash] = $contributor->login;
            unset($remotes[$contributor->login]);
        }
    }

    protected function _md5RemoteGravatar($gravatarHash)
    {
        $url = "http://gravatar.com/avatar/{$gravatarHash}?s=5";
        if (($result = $this->_cache->load($this->_cacheTag($url))) !== false ) return $result;
        $img = @file_get_contents($url);
        $result = md5($img);
        if ($img) $this->_cache->save($result, $this->_cacheTag($url), array(), rand(172800, 259200));
        return $result;
    }

    protected function _get($url, $cacheTimeout = false)
    {
        $url = $this->_apiUrl.$url;
        if (($result = $this->_cache->load($this->_cacheTag($url))) !== false ) return $result;
        $result = json_decode(@file_get_contents($url));
        if ($result) {
            if (!$cacheTimeout) {
                $this->_cache->save($result, $this->_cacheTag($url));
            } else {
                $this->_cache->save($result, $this->_cacheTag($url), array(), $cacheTimeout);
            }
        }
        return $result;
    }

    protected function _cacheTag($tag)
    {
        return preg_replace('/[^A-Za-z0-9]/','_', $tag);
    }
}
