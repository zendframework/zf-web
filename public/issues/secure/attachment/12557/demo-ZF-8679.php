<?php
class A
{
    const FOO = "bar";

    public function getFoo()
    {
        echo self::FOO . PHP_EOL;
    }
}

class B extends A
{
    const FOO = "baz";
}

$a = new A;
$b = new B;

$a->getFoo();
$b->getFoo();
?>
