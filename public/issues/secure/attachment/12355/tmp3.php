<?php
require_once 'Zend/Validate.php';


$unitTests = array(
    '2001:0db8:0000:0000:0000:0000:1428:57ab'    => true,
    '2001:0DB8:0000:0000:0000:0000:1428:57AB'    => true,
    '2001:00db8:0000:0000:0000:0000:1428:57ab'    => false,
    '2001:0db8:xxxx:0000:0000:0000:1428:57ab'    => false,

    '2001:db8::1428:57ab'    => true,
    '2001:db8::1428::57ab'    => false,
    '2001:dx0::1234'    => false,
    '2001:db0::12345'    => false,

    ''            => false,
    ':'            => false,
    '::'            => true,
    ':::'            => false,
    '::::'            => false,
    '::1'            => true,
    ':::1'            => false,

    '::1.2.3.4'        => true,
    '::256.0.0.1'        => false,
    '::01.02.03.04'        => false,
    'a:b:c::1.2.3.4'    => true,
    'a:b:c:d::1.2.3.4'    => true,
    'a:b:c:d:e::1.2.3.4'    => true,
    'a:b:c:d:e:f:1.2.3.4'    => true,
    'a:b:c:d:e:f:1.256.3.4'    => false,
    'a:b:c:d:e:f::1.2.3.4'    => false,

    'a:b:c:d:e:f:0:1:2'    => false,
    'a:b:c:d:e:f:0:1'    => true,
    'a::b:c:d:e:f:0:1'    => false,
    'a::c:d:e:f:0:1'    => true,
    'a::d:e:f:0:1'        => true,
    'a::e:f:0:1'        => true,
    'a::f:0:1'        => true,
    'a::0:1'        => true,
    'a::1'            => true,
    'a::'            => true,

    '::0:1:a:b:c:d:e:f'    => false,
    '::0:a:b:c:d:e:f'    => false,
    '::a:b:c:d:e:f'        => true,
    '::b:c:d:e:f'        => true,
    '::c:d:e:f'        => true,
    '::d:e:f'        => true,
    '::e:f'            => true,
    '::f'            => true,

    '0:1:a:b:c:d:e:f::'    => false,
    '0:a:b:c:d:e:f::'    => false,
    'a:b:c:d:e:f::'        => true,
    'b:c:d:e:f::'        => true,
    'c:d:e:f::'        => true,
    'd:e:f::'        => true,
    'e:f::'            => true,
    'f::'            => true,

    'a:b:::e:f'        => false,
    '::a:'            => false,
    '::a::'            => false,
    ':a::b'            => false,
    'a::b:'            => false,
    '::a:b::c'        => false,
    'abcde::f'        => false,

    '10.0.0.1'        => false,
    ':10.0.0.1'        => false,
    '0:0:0:255.255.255.255'    => false,
    '1fff::a88:85a3::172.31.128.1' => false,

    'a:b:c:d:e:f:0::1'    => false,
    'a:b:c:d:e:f:0::'    => false,
    'a:b:c:d:e:f::0'    => true,
    'a:b:c:d:e:f::'        => true,

    'total gibberish'    => false
);

foreach($unitTests as $ip => $expectedOutcome) {
	if(Zend_Validate::is($ip,'Ip') != $expectedOutcome) {
		echo $ip." ip is ".($expectedOutcome?'valid':'invalid').PHP_EOL;
	}
}
echo PHP_EOL;
