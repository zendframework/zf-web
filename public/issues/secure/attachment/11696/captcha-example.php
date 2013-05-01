<?php

// Creating a Zend_View instance
$view = new Zend_View();

$captcha = new Zend_Captcha_Figlet(array(
    'name' => 'foo',
    'wordLen' => 6,
    'timeout' => 300,
));


if (!empty($_POST)) {
    if ($captcha->isValid($_POST['foo'], $_POST)) {
        // Validated!
    }
}

$id = $captcha->generate();

?>
<form method="post" action="">
<?php
echo $captcha->render($view);
?>

<input type="hidden" name="foo[id]" value="<?=$id?>" />
<input type="text" name="foo[input]" /><br />

<input type="submit" value="Submit" />
</form>
