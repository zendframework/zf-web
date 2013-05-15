<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-11-20 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-11-20 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.4! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changes</h2>

<p>
    ZF2 has shipped with two "view strategies" aimed at simplifying common use 
    cases around developing JSON and XML APIs: <code>Zend\View\Strategy\JsonStrategy</code> 
    and <code>Zend\View\Strategy\FeedStrategy</code>. Each of these would
    select an appropriate renderer based on one of the following criteria:
</p>

<ul>
    <li>If the view model present was of a specific type (e.g., 
        <code>JsonModel</code>, <code>FeedModel</code>).
    </li>
    <li>If the <code>Accept</code> header contained the appropriate media type.</li>
</ul>

<p>
    This latter condition sparked some worry that, when enabled at the 
    application level (vs. enabled based on selected module, controller, action, or
    other more specific criteria), any endpoint could be forced to return JSON or Atom
    (based on the strategies registered), regardless of whether or not it was appropriate.
    This could lead to a couple bad situations:
</p>

<ul>
    <li>Data present in the view model not intended for actual display now being displayed.</li>
    <li>Raising of exceptions due to insuitability of certain view variables 
        for serialization in the selected format (e.g., invalid feed data, 
        non-JSON-serializeable objects, etc.); this could lead to resource 
        consumption and potentially other vulnerabilities.</li>
</ul>

<p>
    Based on these concerns, we made the following changes:
</p>

<ul>
    <li>The <code>JsonStrategy</code> and <code>FeedStrategy</code> now only ever select
        a renderer based on the current view model type: e.g. if you want to 
        expose something as JSON, you must return a <code>JsonModel</code>.
    </li>

    <li>Introduced a new controller plugin, <code>acceptableViewModelSelector()</code>.
        This helper can be used to select an appropriate view model if the <code>Accept</code>
        header meets criteria you specify.
    </li>
</ul>

As an example of the latter:

<pre class="highlight">
EOC;
$extended .= highlight_string("<" . "?php
class SomeController extends AbstractActionController
{
    protected \$acceptCriteria = array(
        'Zend\View\Model\JsonModel' => array(
            'application/json',
        ),
        'Zend\View\Model\FeedModel' => array(
            'application/rss+xml',
        ),
    );

    public function apiAction()
    {
        \$viewModel = \$this->acceptableViewModelSelector(\$this->acceptCriteria);
        
        // Potentially vary execution based on model returned
        if (\$viewModel instanceof JsonModel) {
            // ...
        }
    }
}
", true);
$extended .=<<<'EOC'
</pre>

<p>
    The above would return a standard <code>Zend\View\Model\ViewModel</code> instance
    if the criteria is not met, and the specified view model types if the 
    specific criteria is met. Rules are matched in order, with the first match "winning."
</p>


<h2>Changelog</h2>

<p>
    In addition to the changes mentioned above, this release included more than 
    40 patches, ranging from minor docblock improvements to bugfixes.  The full 
    list is as follows:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/2808">2808: Add serializer better inheritance and extension</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2813">2813: Add test on canonical name with the ServiceManager</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2832">2832: bugfix: The helper DateFormat does not cache correctly when a pattern is set.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2837">2837: Add empty option before empty check</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2843">2843: change self:: with static:: in call-ing static property/method</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2857">2857: Unnecessary path assembly on return in Zend\Mvc\Router\Http\TreeRouteStack->assemble() line 236</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2867">2867: Enable view sub-directories when using ModuleRouteListener</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2872">2872: Resolve naming conflicts in foreach statements</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2878">2878: Fix : change self:: with static:: in call-ing static property/method() in other components ( all )</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2879">2879: remove unused const in Zend\Barcode\Barcode.php</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2896">2896: Constraints in Zend\Db\Metadata\Source\AbstractSource::getTable not initalised</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2907">2907: Fixed proxy adapter keys being incorrectly set due Zend\Http\Client</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2909">2909: Change format of Form element DateTime and DateTimeLocal</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2921">2921: Added Chinese translations for zf2 validate/captcha resources</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2924">2924: small speed-up of Zend\EventManager\EventManager::triggerListeners()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2929">2929: SetCookie::getFieldValue() always uses urlencode() for cookie values, even in case they are already encoded</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2930">2930: Add minor test coverage to MvcEvent</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2932">2932: Sessions: SessionConfig does not allow setting non-directory save path</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2937">2937: preserve matched route name within route match instance while forwarding...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2940">2940: change 'Cloud\Decorator\Tag' to 'Cloud\Decorator\AbstractTag'</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2941">2941: Logical operator fix : 'or' change to '||' and 'and' change to '&&'</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2952">2952: Various Zend\Mvc\Router\Http routers turn + into a space in path segments</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2957">2957: Make Partial proxy to view render function</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2971">2971: Zend\Http\Cookie undefined self::CONTEXT_REQUEST</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2976">2976: Fix for #2541</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2981">2981: Controller action HttpResponse is not used by SendResponseListener</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2983">2983: replaced all calls to $this->xpath with $this->getXpath() to always have...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2986">2986: Add class to file missing a class (fixes #2789)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2987">2987: fixed Zend\Session\Container::exchangeArray</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2994">2994: Fixes #2993 - Add missing asterisk to method docblock</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2997">2997: Fixing abstract factory instantiation time</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2999">2999: Fix for GitHub issue 2579</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3002">3002: update master's resources/ja Zend_Validate.php message</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3003">3003: Adding tests for zendframework/zf2#2593</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3006">3006: Hotfix for #2497</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3007">3007: Fix for issue 3001 Zend\Db\Sql\Predicate\Between fails with min and max ...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3008">3008: Hotfix for #2482</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3009">3009: Hotfix for #2451</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3013">3013: Solved Issue 2857</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3025">3025: Removing the separator between the hidden and the visible inputs. As the...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3027">3027: Reduced #calls of plugin() in PhpRenderer using a cache mechanism</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3029">3029: Fixed the pre-commit script, missed the fix command</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3030">3030: Mark module as loaded before trigginer EVENT_LOAD_MODULE</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3031">3031: Zend\Db\Sql Fix for Insert's Merge and Set capabilities with simlar keys</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

<h2>Reminder</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday. Additionally, 
    we have the next minor release, 2.1.0, slated for sometime next month.
</p>

EOC;
$post->setExtended($extended);

return $post;
