<?php
include('Zend/Config/Xml.php');

$string = <<<EOT
<?xml version="1.0"?>
<config>
    <production>
        <db>
            <adapter value="pdo_mysql"/>
            <params>
                <host value="db.example.com"/>
            </params>
        </db>
    </production>
    <staging extends="production">
        <db>
            <params>
                <host value="dev.example.com"/>
            </params>
        </db>
    </staging>
</config>
EOT;

$config = new Zend_Config_Xml($string, 'staging');
?>