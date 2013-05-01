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

Zend_Dojo_View_Helper_Dojo::setUseDeclarative ();

class AuthModel {
	//регулярное выражение, описывающее разрешенные строки для имени пользователя (логина)
	const LOGIN_PARTIAL_REGEX = '^[a-zA-Z0-9_-]{3,32}$';
	const LOGIN_PARTIAL2_REGEX = '[a-zA-Z0-9_-]{3,32}';
	const LOGIN_REGEX = '/^[a-z0-9_-]{3,32}$/i';
	const LOGIN_MAX_LEN = 32;

	const EMAIL_PARTIAL_REGEX = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$';
	const EMAIL_MAX_LEN = 128;

	const PWD_MIN_LEN = 6;
	const PWD_MAX_LEN = 64;
	const PWD_PARTIAL_REGEX = '^.{6,64}$';

	const VAR_FIELD_MAX_LEN = 64;
	const NAME_MAX_LEN = 48;
	const ICQNUM_MAX_CHARS = 16; //макс кол-во цифр в асечном номере
}

//Declare a form class
class RegisterForm extends Zend_Dojo_Form {

	public function init(){
		//логин пользователя
		$txtLog = new Zend_Dojo_Form_Element_ValidationTextBox(array(
			'name' => 'username',
			'label'=> 'Имя пользователя (логин) (*)',
			'required'	=> true,
			'trim' =>true,
			'regExp'         => AuthModel::LOGIN_PARTIAL_REGEX,
        	'invalidMessage' => 'Допускаются символы латинского алфавита, знак подчёркивания и минус. Длина от 3 до 32х символов'));
		//правильный валидатор имени пользователя в соответсвии с требованиями.
        $txtLog->addValidator('regex',true, array(AuthModel::LOGIN_REGEX));

        $txtPwd = new Zend_Dojo_Form_Element_PasswordTextBox(array (
        	'name' => 'password', 'label' => 'Пароль (проверьте текущую раскладку клавиатуры и состояние клавиши CapsLock) (*)',
        	'required' => true,
        	'regExp'	=> AuthModel::PWD_PARTIAL_REGEX,
        	'invalidMessage' => 'Пароль должен быть не менее '.AuthModel::PWD_MIN_LEN.' знаков и не более '.AuthModel::PWD_MAX_LEN.' знаков длиной'));
        $txtPwd->addFilter('StringTrim');
        $txtPwd2 = new Zend_Dojo_Form_Element_PasswordTextBox(array (
        	'name' => 'password2', 'label' => 'Повторите пароль (*)',
        	'required' => true,
        	'regExp'         => AuthModel::PWD_PARTIAL_REGEX,
        	'invalidMessage' => 'Повторно введённый пароль не совпадает с исходным!'));
        $txtPwd2->addFilter('StringTrim');

        $txtEmail = new Zend_Dojo_Form_Element_ValidationTextBox(array(
			'name' => 'email',
			'label'=> 'Электронная почта (*). Используется только внутри сайта для получения уведомлений о новых комментариях, смены пароля и прочих служебных действий. Третьим лицам не передаётся.',
			'required'	=> true,
			'trim' =>true,
			'regExp'         => AuthModel::EMAIL_PARTIAL_REGEX,
        	'invalidMessage' => 'Вероятно, введённая строка не явлется адресом почты. Укажите правильный адрес'));
        $txtEmail->addFilter('StringToLower');
        $txtEmail->addValidator('StringLength',true,array(0,AuthModel::EMAIL_MAX_LEN))->addValidator('EmailAddress',true);


        $bShowEmail = new Zend_Dojo_Form_Element_CheckBox(array('name' => 'bshowemail',
			'label'=> 'Показывать другим пользователям Ваш email?',
        	'checkedValue'   => '1',
        	'uncheckedValue' => '0',
        	'checked'        => false));
        $bShowEmail->addValidator('regex', true, array('/^0|1$/'));


        $txtName = new Zend_Dojo_Form_Element_ValidationTextBox(array(
			'name' => 'name',
			'label'=> 'Ваше имя',
        	'trim' => true,
        	'regExp' => '^.{0,'.AuthModel::NAME_MAX_LEN .'}$',
			'invalidMessage' => 'Длина поля ограничена '.AuthModel::NAME_MAX_LEN .' символами'));
        $txtName->addValidators(array (	array(new Zend_Validate_StringLength(0,AuthModel::NAME_MAX_LEN),true)));

        //страна
        $txtCountry = new Zend_Dojo_Form_Element_ValidationTextBox(array(
			'name' => 'country',
			'label'=> 'Страна',
        	'trim' => true,
        	'regExp' => '^.{0,'.AuthModel::VAR_FIELD_MAX_LEN.'}$',
			'invalidMessage' => 'Длина поля ограничена '.AuthModel::VAR_FIELD_MAX_LEN.' символами'));
        $varFieldLengthVld = array( new Zend_Validate_StringLength(0,AuthModel::VAR_FIELD_MAX_LEN) , true);
        $txtCountry->addValidators( array($varFieldLengthVld));


        $txtCity = new Zend_Dojo_Form_Element_ValidationTextBox(array(
			'name' => 'city',
			'label'=> 'Город',
        	'trim' => true,
        	'regExp' => '^.{0,'.AuthModel::VAR_FIELD_MAX_LEN.'}$',
			'invalidMessage' => 'Длина поля ограничена '.AuthModel::VAR_FIELD_MAX_LEN.' символами'));
        $txtCity->addValidators( array ($varFieldLengthVld));


        $birthday = new Zend_Dojo_Form_Element_DateTextBox(array(
        	'name' => 'birthday',
        	'label' => 'Ваш день рождения',
	        'invalidMessage' => 'Указана неверная дата',
    	    'formatLength'   => 'long'
        ));
        $birthday->addValidator('Date', true);

        $cIcq = new Zend_Dojo_Form_Element_ValidationTextBox(array(
        	'name'=>'cicq',
        	'label'=>'Номер ICQ',
        	'trim' =>true,
			'regExp' => '^\d{0,'.AuthModel::ICQNUM_MAX_CHARS.'}$',
			'invalidMessage' => 'Номер ICQ должен являться числом'
        ));
        $cIcq->addValidators( array( array('StringLength',true, array(0,AuthModel::ICQNUM_MAX_CHARS)), array('Digits',true) ));


        $cSkype = new Zend_Dojo_Form_Element_TextBox(array(
			'name' => 'cskype',
			'label'=> 'Skype',
        	'trim' => true));
        $cSkype->addValidators(array($varFieldLengthVld));

        $captcha = new Zend_Form_Element_Captcha('turingtest', array(
		    'label' => "Captcha",
			'captcha' => 'ReCaptcha',
		    'captchaOptions' => array(
		        'privKey' => '6Lf9xQMAAAAAAGt9yhFNmsuemMpAzefPc0qDrxmo',
				'pubKey' => '6Lf9xQMAAAAAAN9DCeBc_x8ZAK7hKtQrozTF6KAa'
		    ),
		));
		//делаем язык. через жопу
		$captcha->getCaptcha()->getService()->setOption('lang', 'ru');



        $btnSubmit = new Zend_Dojo_Form_Element_SubmitButton(array('name'=>'register',
        	'label'=> 'Register!'));
        //добавляем onSubmit, потому что требуется там ручками обрабатывать значение чекбокса - оно почему-то не меняется.
        //задаём проверочную функцию
    	$this->setAttrib('onsubmit', "dojo.byId('bshowemail').value = dijit.byId('bshowemail').attr('checked') ? '1' : '0'; return true;");
    	$this->setAttrib ( 'id', 'regForm' );


        $this->addElements(array($txtLog, $txtPwd, $txtPwd2,$txtEmail, $bShowEmail, $txtName,
        	$txtCountry, $txtCity, $birthday, $cIcq, $cSkype, $captcha, $btnSubmit));
	}
}



///готовим форму
$form = new RegisterForm();



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/js/dojo/dojo/resources/dojo.css" media="screen" rel="stylesheet" type="text/css" />
<link href="/js/dojo/dijit/themes/tundra/tundra.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" djConfig="parseOnLoad:true, isDebug:false" src="/js/dojo/dojo/dojo.js"></script>
<script type="text/javascript">
    //<![CDATA[
/*init = function(){
	alert('load completed');
}*/
dojo.require("dojo.parser"); dojo.require("dijit.form.Form"); dojo.require("dijit.form.Button"); dojo.require('dijit.form.ValidationTextBox');
dojo.require("dijit.form.DateTextBox"); dojo.require("dijit.form.CheckBox");
//dojo.addOnLoad(init);
//]]>
</script>

</head>

<body>
	<div style="padding: 10px;">
		<div>
	    	bla-bla-blaaa
	    </div>
	    <div>
	    	<?php echo $form; ?>
	    </div>
	    <div>
    	bla-bla-blaaaaaaaaaaa
    	</div>
	</div>
</body>

</html>