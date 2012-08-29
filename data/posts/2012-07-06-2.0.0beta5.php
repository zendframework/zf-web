<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0beta5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-07-06 11:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-07-06 11:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta5. Packages and installation instructions are
available at:
</p>

<ul class="ul">
    <li>
    <a href="http://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </li>
</ul>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta5. Packages and installation instructions are
available at:
</p>

<dl>
    <dd>
    <a href="http://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </dd>
</dl>

<p>
This is the fifth and last in a series of planned beta releases. The beta 
release cycle has followed the "gmail" style of betas, whereby new features 
have been added in each new release, and backwards compatibility (BC) has not 
been guaranteed. The desire has been for developers to adopt and work with new 
components as they are shipped, and provide feedback so we can polish the 
distribution.
</p>

<p>
Our plan at this time is to do some cleanup of the repository, fix some 
outstanding issues and features, migrate documentation to a new repository and 
format, and then begin the release candidate process. At this time, we 
anticipate few if any BC breaks between now and the first release candidate (RC).
Applications built today on beta5 will operate in the way we expect them to for 
the stable release.
</p>

<p>
Beta5 had a rigorous and busy schedule. We tackled a number of consistency
issues, and solidified the security the framework offers via several fixes and 
the new Escaper component. We processed over 400 pull requests again, in a 
shorter timeframe than we had for beta4; the community really pulled together
to get this release out the door.
</p>

<p>
    There are three primary new features for the release:
</p>

<ul class="ul">
    <li><code>Zend\Escaper</code>, which provides an OWASP-compliant solution 
        for context-specific escaping mechanisms targetting HTML, HTML attributes, 
        URLs, CSS, and JavaScript.</li>
    <li><code>Zend\I18n</code>, which leverages <acronym title="PHP Hypertext 
        Preprocessor">PHP</acronym>'s <code>ext/intl</code> extension to 
        provide localization, and also provides a new translation component.</li>
    <li><code>Zend\Form</code> now has new annotation support, allowing you
        to define annotations in your domain entities that can then be used
        to build a form and its related input filter and hydrator. Annotation
        support now has a dependency on <a 
        href="http://doctrine-project.org">Doctrine\Common</a>'s annotation 
        library, for our default annotation syntax parser.</li>
</ul>

<p>
    2.0.0beta5 had many more changes, as you can see from the release changelog:
</p>

<pre>
 - Escaper component (Padraic Brady)
    - Provides context-specific escaping mechanisms for HTML content,
      HTML attributes, URLs, CSS, and JavaScript.
    - BC BREAK: The escape() view helper was removed, and replaced with
      escapeHtml(), escapeHtmlAttr(), escapeJs(), escapeCss(), and
      escapeUrl() implementations.
 - New I18n component (Ben Scholzen, Chris Martin, Dennis Portnov,
   Matthew Weier O'Phinney)
    - New component leveraging PHP's ext/intl extension to provide
      internationalization (i18n) and localization (L10n) features and
      capabilities to applications.
    - LEVERAGES:
        - DateTime, DateTimezone, IntlDateFormatter
        - Locale
        - NumberFormatter
    - BC BREAK: REMOVES the following components:
        - Zend\Currency
        - Zend\Date
        - Zend\Locale
        - Zend\Measure
        - Zend\Translator
        - All filters, validators, and view helpers that relied on the
          above.
    - PROVIDES:
        - Zend\I18n\Translator, including support for gettext and
          PHP-array-based translations (more are planned).
        - Zend\I18n\Filter, containing localized filtering capabilites
          for Alnum (alphanumeric), Alpha (alphabetic), and NumberFormat
          (numerical strings).
        - Zend\I18n\Validator, containing localized validation
          capabilities for Alnum (alphanumeric), Alpha (alphabetic),
          Iban (international bank account number standard), Int
          (integer), and PostCode (localized postal codes).
        - Zend\I18n\View, containing localized view helpers for
          CurrencyFormat, DateFormat, NumberFormat, Translate, and
          TranslatePlural.
 - Db layer additions (Ralph Schindler, Rob Allen, Guillaume Metayer,
   Sascha Howe, Chris Testroet, Evan Coury, Ben Youngblood)
    - Metadata support
    - Postgresql adapter/driver
    - New HydratingResultSet, allowing the ability to specify a custom
      hydrator (from Zend\Stdlib\Hydrator) for hydrating row objects.
    - Many bugfixes and stabilizations
 - Form additions (Matthew Weier O'Phinney, Michaël Gallego, Yanick Rochon)
    - Annotations support: Ability to use annotations with a domain
      object in order to define a form, fieldsets, elements, inputs and
      input filters, and more.
    - Hydration of fieldsets; fieldsets may compose their own hydrators
      if desired.
    - Collection support; allows multiple instances of the same
      fieldset. As an example, you might have an interface that
      allows adding a set of form elements via an XHR call; on the
      backend, these would be defined as a collection, allowing
      arbitrary numbers of these fieldsets to be submitted.
    - New view helpers covering most HTML5-specific element types, most
      XHTML-specific element types. Additionally, a number of the
      HTML5-specific element types now have Element implementations to
      create turn-key solutions that include validation and filtering.
    - BC BREAK: Options support. Many attributes were being used not as
      HTML attributes but to define behavior. The ElementInterface now
      has an accessor and mutator for options. Examples of options
      include labels for non-radio/checkbox/select elements, the CAPTCHA
      adapter for CAPTCHA elements, CSRF tokens, etc. If you were
      defining labels in your forms, please move the label and label
      attributes definitions from the "attributes" to the "options" of
      the element, fieldset, or form.
    - BC BREAK: new interface, ElementPrepareAwareInterface, defining
      the method "prepareElement(Form $form)". The FieldsetInterface,
      and, by extension, FormInterface, extend this new interface. It is
      used to allow preparing elements prior to creating a
      representation.
 - MVC additions (Kyle Spraggs, Evan Coury, Matthew Weier O'Phinney)
    - New "Params" controller plugin. Allows retrieving query, post,
      cookie, header, and route parameters. Usage is
      $this->params()->fromQuery($name, $default).
    - New listener, Zend\Mvc\ModuleRouteListener. When enabled, if a
      route match contains a "\__NAMESPACE__" key, that namespace value
      will be prepended to the value of the "controller" key. This
      should typically be used in the root route for a given module, to
      ensure controller names do not clash.
    - Bootstrap simplification. A new "init()" method was created that
      accepts the path to a configuration file, and then creates and
      bootstraps the application; this eliminates all common boilerplate
      for the bootstrap scripts.
 - Hydrator changes (Adam Lundrigan)
    - BC BREAK: the ClassMethods hydrator now assumes by default that
      it should convert between underscore_separated names and
      camelCase.
 - BC BREAK: Doctrine Annotations Parser (Matthew Weier O'Phinney, Marco
   Pivetta, Guilherme Blanco)
    - Zend\Code\Annotation now has a dependency on Doctrine\Common for
      its annotation parser.
    - Annotations now conform to Doctrine's standards by default, but
      the AnnotationManager in ZF2 allows attaching alternate parsers
      for specific annotation types.
 - BC BREAK: Removal of Plugin Broker usage (Matthew Weier O'Phinney,
   Evan Coury)
    - All uses of the Plugin Broker / Plugin Class Locator combination
      were removed. A new class, Zend\ServiceManager\AbstractPluginManager, 
      was created and used to replace all previous usages of the plugin
      broker. This provides more flexibility in creation of plugins, as
      well as reduces the number of APIs developers need to learn.
    - Configuration of plugin managers is now done at the top-level. All
      plugin manager configuration follows the format utilized by
      Zend\ServiceManager\ServiceConfiguration, and
      Zend\ModuleManager\Listener\ServiceListener has been updated to
      allow informing it of plugin manager instances it should manage,
      as well as the configuration key to utilize.
 - BC BREAK: Coding Standards (Maks3w, Sascha Prolic, Rob Allen)
    - Renamed most abstract classes to prefix them with the term
      "Abstract". In particular, ActionController and RestfulController
      are now AbstractActionController and AbstractRestfulController.
    - Renamed getters in HTTP, EventManager, and Mail components. These
      components were using accessors such as "events()", "query()",
      "headers()", etc. All such accessors were renamed to prepend
      "get", and, in the case of "events()", renamed to indicate the
      actual object retrieved ("getEventManager()"). 
 - SECURITY FIX: XmlRpc (Matthew Weier O'Phinney)
    - A security issue arising from XML eXternal Entity (XXE) injection
      was patched; see http://framework.zend.com/security/advisory/ZF2012-01
</pre>

<p>
    The <a href="http://github.com/zendframework/ZendSkeletonApplication">skeleton
    application</a> and a <a
    href="http://github.com/zendframework/ZendSkeletonModule">skeleton
    module</a> have been updated for 2.0.0beta5, and are a 
    great place to look to help get you started. You may also want to check out the <a
    href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">quick start
    guide to the MVC</a>.
</p>

<p>
    As a reminder, all ZF2 components are also available individually as <a 
    href="http://pear2.php.net">Pyrus</a> AND composer packages; 
    packages.zendframework.com is our official channel for both package formats.
</p>

<p>
I'd like to thank each and every person who has contributed ideas, feedback, 
pull requests (no pull request is too small!), testing, and more -- every
contribution is leading to a better, more stable, polished framework.
</p>
EOC;
$post->setExtended($extended);

return $post;
