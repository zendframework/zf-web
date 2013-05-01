<?php
set_include_path('/var/www/zend-framework-1.7.2/library:.');

require_once 'Zend/Text/Table.php';
require_once 'Zend/Text/Table/Column.php';

$options = array(
    'columnWidths' => array(20, 20),
);
$table = new Zend_Text_Table($options);
$table->appendRow(
    array(
        'Ete',
        'Dummy'
    )
);
$table->appendRow(
    array(
        'Eté',
        'Dummy'
    )
);

echo '<pre>' . $table . '</pre>';
?>