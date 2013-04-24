<?php

define('APP_ROOT',realpath ( dirname ( __FILE__ ) . '/../../' ));
define('PATH_TO_LIBS',APP_ROOT.'libs/');
define('PATH_TO_FUNCS',APP_ROOT.'/funcs');		//без завершающего слеша

set_include_path ( PATH_TO_LIBS );

require_once "Zend/Loader.php";
// Set up autoload.
Zend_Loader::registerAutoload ();
mb_internal_encoding('UTF-8');

function require_func($func, $folder='') {
	if (function_exists($func)){
		return;
	}
	require_once PATH_TO_FUNCS . $folder . '/' . $func . '.php';
}

error_reporting ( E_ALL | E_STRICT );
ini_set ( 'display_startup_errors', 1 );
ini_set ( 'display_errors', 1 );


$view = new Zend_View();
$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer ( );
$viewRenderer->setView ( $view );
Zend_Controller_Action_HelperBroker::addHelper ( $viewRenderer );


///готовим форму
$form = new Zend_Form();
$form->addElement( new Zend_Form_Element_Captcha('recaptcha', array(
		    'label' => "Enter CAPTCHA",
			'captcha' => 'ReCaptcha',
		    'captchaOptions' => array(
		        'privKey' => '6Lf9xQMAAAAAAGt9yhFNmsuemMpAzefPc0qDrxmo',
				'pubKey' => '6Lf9xQMAAAAAAN9DCeBc_x8ZAK7hKtQrozTF6KAa'
		    ),
		) ) );

$form->addElement( new Zend_Form_Element_Submit(array('name'=>'subm',
        	'label'=> 'Submit')) );

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<script type="text/Javascript">

init = function(){
	document.getElementById('msg').innerHTML = "Page has been initialized successfully";
};

</script>

</head>

<body onload="init()">
	<div>
	    <div>
	    	<?php echo $form; ?>
	    </div>
	</div>

    <div id='msg'>
    	If you see this message, JavaScript initialization has been failed.
    </div>

</body>

</html>