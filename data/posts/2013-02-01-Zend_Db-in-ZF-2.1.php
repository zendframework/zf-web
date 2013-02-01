<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('ralphschindler');
$author->setName('Ralph Schindler');
$author->setEmail('ralph.s@zend.com');
$author->setUrl('http://ralphschindler.com/');

$post = new EntryEntity();
$post->setTitle('Zend\Db in ZF 2.1');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-02-01 11:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-02-01 11:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'

<p>
    <code>Zend\Db</code> just got a little better with the release of Zend 
    Framework 2.1.  All the cool things you could do on Mysql, SQLite, 
    Postgresql and SQL Server can now be done on DB2 and Oracle.  In addition, 
    a number of additions were brought into the <code>Zend\Db\Sql\Select</code> object as 
    well.
</p>

EOS;
$post->setBody($body);
$extended =<<<'EOS'

<h3>Connecting to DB2 and Oracle</h3>

<p>
    Connecting to DB2 whether on Windows, *nix, Mac, or the IBM iSeries, is the 
    same as any other driver - using the array notation:
</p>

<pre class="highlight">
use Zend\Db\Adapter\Adapter as DbAdapter;

// DB2 Connection
$adapter = new DbAdapter(array(
    'driver' => 'IbmDb2',
    'database' => '*LOCAL',
    'username' => '',
    'password' => '',
    'driver_options' => array(
        'i5_naming' => DB2_I5_NAMING_ON,
        'i5_libl' => 'LIB1 LIB2 LIB3'
    ),
    'platform_options' => array('quote_identifiers' => false)
);

// Oracle Connection
$adapter = new DbAdapter(array(
    'driver' => 'Oci8',
    'hostname' => 'localhost/XE',
    'username' => 'developer',
    'password' => 'developer'
));
</pre>

<p>
    Both Oracle and DB2 carry different conventional usage patterns and 
    workflows than their more modern successors in the relational database 
    space.  As such, certain default behaviors can be turned off.  For example, 
    by default, when queries are generated via any of the <code>Zend\Db\Sql</code> object
    (SQL abstraction), all known identifiers are identifier quoted.  That means 
    if a developer wrote: <code>$select->from('foo');</code> then "foo" would be quoted 
        in the database platform specific way.  For MySQL this means 
        back-ticks, like <code>`foo`</code>, and for most other database that means being 
        quoted with double quotes.  In cases of Oracle and DB2 where there is a 
        pronounced history of not quoting identifiers, <code>Zend\Db\Adapter</code> can be 
        provided an option to turn this off - as you can see above in the 
        "platform_options".
</p>

<p>
    Once an adapter is created, it can be used by any of the <code>Zend\Db</code> API.  Here 
    are a few examples of what you can do:
</p>

<pre class="highlight">
// Zend\Db\TableGateway
use Zend\Db\TableGateway\TableGateway;
 
$table = new TableGateway('ARTIST'), $adapter);
$results = $table->select(array('ARTIST_ID > ?' => 5000));

// iterate results outputting each column
foreach ($results as $row) {
  echo '&lt;tr&gt;';
  foreach ($row as $col) {
    echo '&lt;td&gt;' . $col . '&lt;/td&gt;';
  }
  echo '&lt;/tr&gt;';
}
</pre>

<p>
    A more complex query:
</p>

<pre class="highlight">
// complex query
$sql = new Sql($adapter);
$select = $sql->select()->from('ARTIST')
    ->columns(array()) // no columns from main table
    ->join('ALBUM', 'ARTIST.ARTIST_ID = ALBUM.ARTIST_ID', array('TITLE', 'RELEASE_DATE'))
    ->order(array('RELEASE_DATE', 'TITLE'))
    ->where->like('ARTIST.NAME', '%Brit%');
$statement = $sql->prepareStatementFromSqlObject($select);
foreach ($statement->execute() as $row) {
    // var_dump($row);
}
</pre>

<h3>Other Interesting Additions to Zend\Db\Sql</h3>

<p>
    Join From SubSelect:
</p>

<pre class="highlight">
$subselect = new Select;
$subselect->from('bar')->where->like('y', '%Foo%');
$select = new Select;
$select->from('foo')->join(array('z' => $select39subselect), 'z.foo = bar.id');

// produces SQL92 SQL (newlines added for readability):
SELECT "foo".*, "z".*
    FROM "foo"
    INNER JOIN (
        SELECT "bar".* FROM "bar"
            WHERE "y" LIKE '%Foo%'
        ) AS "z" ON "z"."foo" = "bar"."id"
</pre>

<p>
    Expressions inside Order:
</p>

<pre class="highlight">
$select = new Select;
$select->order(new Expression('RAND()'));
</pre>

<h3>Call to Action</h3>

<p>
    Since our DB2 and Oracle drivers are new, we are sure they are not perfect 
    yet and can be improved to better allow a more natural workflow for the 
    database needs of a DB2 or Oracle developer.  If you find anything that is 
    a bug, or feature request, please take the time to fill out an issue on our 
    github repository for ZF2:
</p>

<p>
    https://github.com/zendframework/zf2/issues
</p>

<p>
Happy ZFing!
</p>
EOS;
$post->setExtended($extended);

return $post;
