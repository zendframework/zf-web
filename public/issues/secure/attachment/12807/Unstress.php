<?php

/*
 * Filtre permettant de supprimer tous les accents.
 */
class Zend_Filter_Unstress implements Zend_Filter_Interface {

	public function filter($value){
		$stressed_letters	= array("à","á","â","ã","ä","å","ç","è","é","ê","ë","ì","í","î","ï","ð","ò","ó","ô","õ","ö","ù","ú","û","ü","ý","ÿ","À","Á","Â","Ã","Ä","Å","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ò","Ó","Ô","Õ","Ö","Ù","Ú","Û","Ü","Ý");
		$unstressed_letters	= array("a","a","a","a","a","a","c","e","e","e","e","i","i","i","i","o","o","o","o","o","o","u","u","u","u","y","y","A","A","A","A","A","A","C","E","E","E","E","I","I","I","I","O","O","O","O","O","U","U","U","U","Y");
		return str_replace($stressed_letters, $unstressed_letters, $value);
	}

}

?>
