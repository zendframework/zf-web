<?php
/***********************************************************************
  small demo app to show problem with ignoreNoFile option and other 
  issues
  ( issue: ZF-4280, ZF-4347 )
  
  for ZF-4280:

  just submit the empty form, dont select a file -> it will output error
  messages, altough it shouldnt ( file elements are not required ), 
  doesnt matter how many file elements in the form

  for ZF-4347 ( regarding the comments from september 23rd ):

  ignore it for one file element

  with 2 file elements:

  no files uploaded -> look above

  with invalid files uploaded ( example: wrong extension ):
  	only in file1 -> both elements gets fileSizeNotFound error [incorrect]
    in both file elements -> both gets fileExtensionFalse error [correct]
    only in file2 -> both get fileExtensionFalse error [incorrect]

  next scenario:

  file1 -> a valid jpeg file
  file2 -> a invalid pdf file
  -> both file elements gets fileExtensionFalse error

  file1 -> a invalid pdf file
  file2 -> a valid jpeg file
  -> form is invalid but no error messages returned

  next scenario:

  file1 -> an image which is too wide
  file2 -> empty
  -> both file elements gets fileExtensionFalse error  

  file1 -> an image which is too wide
  file2 -> an invalid file ( wrong extension )
  -> both file elements gets fileExtensionFalse error

  file1 -> empty
  file2 -> an image which is too wide
  -> form is invalid, but no error messages returned 

***/
ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.'../library');
require_once('Zend/Loader.php');
Zend_Loader::registerAutoload();

$request = new Zend_Controller_Request_Http();

// setup the form
$form = new Zend_Form();
$form->setMethod("post");
$form->setAttrib("enctype",Zend_Form::ENCTYPE_MULTIPART);

// file element, upload is optional, but if file is uploaded run multiple validators
$file1 = $form->createElement("file","file1");
$file1->setRequired(false)
	  ->setLabel("File:")
      ->addValidator('Count', true, 2)     
      ->addValidator('Size', true, "100KB")
      ->addValidator('Extension', true, 'jpg')
      ->addValidator('MimeType',true,array('image/jpeg'))
      ->addValidator('ImageSize',true,array(0,0,340,480));

// add another file element with same validators      
$file2 = clone $file1;
$file2->setName("file2");

$submit = $form->createElement("submit","submit");
$submit->setLabel("GO!");

$form->addElements(array($file1,$file2,$submit));

// check the form
if($request->isPost()) {
	$formData = $request->getPost();
	if($form->isValid($formData)) {
		echo "FORM VALID";
	} else {
		print_r($form->getMessages());		
	}
}
?>
<html>
<head>
<title>Test</title>
</head>
<body>
<?php echo $form->render(new Zend_View());?>
</body>
</html>