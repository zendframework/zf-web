<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$out = '';
$i = 0;
foreach (file('http://www.eurid.eu/en/eu-domain-names/idns-eu/supported-unicode-characters') as $row) {
    $row = trim($row);
    if (!$i && $row == '<tr>') {
        $i = 1;
        continue;
    } elseif ($i == 1) {
        // expected <td>U+00E0</td>
        $i = (preg_match('~^\<td\>U\+[^<]+\</td\>$~', $row) ? 2 : 0);
        continue;
    } elseif ($i == 2) {
        // expected <td>ã</td>
        if (preg_match('~^\<td\>([^<]+)\</td\>$~u', $row, $m)) {
            $out .= $m[1];
        } else {
            echo "# DEBUG not preg_match $row\n";
        }
        $i = 0;
        continue;
    }
}
// 2010-02-15 tested alls characters are unique
echo $out."\n";
