<?php
namespace ZfSiteBlog\View\Helper;

use DomainException;
use InvalidArgumentException;
use Traversable;
use Zend\View\Helper\AbstractHelper;

class Disqus extends AbstractHelper
{
    protected $key;
    protected $developer = 0;

    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    public function setOptions($options)
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new InvalidArgumentException('Expected a traversable set of options');
        }

        foreach ($options as $key => $value) {
            switch ($key) {
                case 'key':
                    $this->key = $value;
                    break;
                case 'development':
                    $this->developer = $value;
                    break;
                default:
                    break;
            }
        }
    }

    public function __invoke($identifier = '', $url = '', $title = '', $key = false, $developer = false)
    {
        if (!$key && !$this->key) {
            throw new DomainException('No disqus key provided');
        } elseif (!$key) {
            $key = $this->key;
        }

        if (!$developer) {
            $developer = $this->developer;
        }

        if (!preg_match('#^https?://#', $url)) {
            // Ensure Disqus uses appropriate hostname!
            $serverUrl = $this->getView()->plugin('serverUrl');
            $serverUrl->setHost('framework.zend.com');
            $url = $serverUrl($url);
        }

        $html =<<<EOH
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_developer = $developer;
        var disqus_shortname = '$key';
        var disqus_identifier = '$identifier';
        var disqus_url = '$url';
        var disqus_title = '$title';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
EOH;
        return $html;
    }
}
