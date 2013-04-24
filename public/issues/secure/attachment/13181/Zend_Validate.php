<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Translate
 * @subpackage Ressource
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id:$
 */

/**
 * EN-Revision: 21135
 */
return array (
   // Zend_Validate_Alnum
  "Invalid type given, value should be float, string, or integer" => "Невалидан тип, вредност треба да буде текст или број",
  "'%value%' contains characters which are non alphabetic and no digits" => "'%value%' садржи карактере који нису слова нити цифре",
  "'%value%' is an empty string" => "'%value%' је празан текст",
  
  // Zend_Validate_Alpha
  "Invalid type given, value should be a string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' contains non alphabetic characters" => "'%value%' садржи карактере који нису слова",
  "'%value%' is an empty string" => "'%value%' је празан текст",
  
  // Zend_Validate_Barcode
  "'%value%' failed checksum validation" => "'%value%' грешка у цхецксум валидацији",  
  "'%value%' contains invalid characters" => "'%value%' садржи невалидне карактере",
  "'%value%' should have a length of %length% characters" => "'%value%' треба да буде дужине %ленгтх%",
  "Invalid type given, value should be string" => "Невалидан тип, вредност треба да буде текст",
  
  // Zend_Validate_Between
  "'%value%' is not between '%min%' and '%max%', inclusively" => "'%value%' није између '%min%' и '%max%', укључиво",
  "'%value%' is not strictly between '%min%' and '%max%'" => "'%value%' није строго између '%min%' и '%max%'",
  
  // Zend_Validate_Callback
  "'%value%' is not valid" => "'%value%' није валидно",
  "Failure within the callback, exception returned" => "Грешка у позиву",
  
  // Zend_Validate_Ccnum
  "'%value%' must contain between 13 and 19 digits" => "'%value%' треба да садржи између 13 и 19 цифара",
  "Luhn algorithm (mod-10 checksum) failed on '%value%'" => "Лухн алгоритам не пролази на '%value%'",
  
  // Zend_Validate_CreditCard
  "Luhn algorithm (mod-10 checksum) failed on '%value%'" => "Лухн алгоритам не пролази на '%value%'",
  "'%value%' must contain only digits" => "'%value%' треба да садржи само цифре",
  "Invalid type given, value should be string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' contains an invalid amount of digits" => "'%value%' садржи невалиду количину цифара",
  "'%value%' is not from an allowed institute" => "'%value%' није из дозвољене институције",
  "Validation of '%value%' has been failed by the service" => "Валидација '%value%' није успела од стране сервиса",
  "The service returned a failure while validating '%value%'" => "Сервис је вратио грешку при валидацији '%value%'",
  
  // Zend_Validate_Date
  "Invalid type given, value should be string, integer, array or Zend_Date" => "Невалидан тип, вредност треба да буде текст, цео број, низ или Зенд_Дате",
  "'%value%' does not appear to be a valid date" => "'%value%' није валидан датум",
  "'%value%' does not fit the date format '%format%'" => "'%value%' није у формату датума '%format%'",
  
  // Zend_Validate_Db_Abstract
  "No record matching %value% was found" => "Запис који се поклапа са %value% није пронађен",
  "A record matching %value% was found" => "Запис који се поклапа са %value% је пронађен",
  
  // Zend_Validate_Digits
  "Invalid type given, value should be string, integer or float" => "Невалидан тип, вредност треба да буде текст или број",
  "'%value%' contains characters which are not digits; but only digits are allowed" => "'%value%' садржи карактере који нису цифре, а само цифре су дозвољене",
  "'%value%' contains not only digit characters" => "'%value%' не садржи само цифре",
  "'%value%' is an empty string" => "'%value%' је празан текст",
  
  // Zend_Validate_EmailAddress
  "Invalid type given, value should be string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' is no valid email address in the basic format local-part@hostname" => "'%value%' није валидна адреса електронске поште у формату адреса@имехоста",
  "'%hostname%' is no valid hostname for email address '%value%'" => "'%hostname%' није валидно име хоста за адресу електронске поште '%value%'",
  "'%hostname%' does not appear to have a valid MX record for the email address '%value%'" => "'%hostname%' нема валидан МX запис за адресу електронске поште '%value%'",
  "'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved from public network." => "'%hostname%' није рутабилан мрежни сегмент. Адреса електронске поште '%value%' не треба да буде разрешена са јавне мреже",
  "'%localPart%' can not be matched against dot-atom format" => "'%localPart%' се не поклапа са дот-атом форматом",
  "'%localPart%' can not be matched against quoted-string format" => "'%localPart%' се не поклапа са qуотед-стринг форматом",
  "'%localPart%' is no valid local part for email address '%value%'" => "'%localPart%' није валидан део адресе електронске поште '%value%'",
  "'%value%' exceeds the allowed length" => "'%value%' прелази дозвољену дужину",
  
  // Zend_Validate_File_Count
  "Too many files, maximum '%max%' are allowed but '%count%' are given" => "Превелики број фајлова, максимално '%max%' је дозвољено, а '%count%' је прослеђено",
  "Too few files, minimum '%min%' are expected but '%count%' are given" => "Премали број фајлова, минимално '%min%' је очекивано, а '%count%' је прослеђено",
  
  // Zend_Validate_File_Crc32
  "File '%value%' does not match the given crc32 hashes" => "Фајл '%value%' не пролази црц32 проверу",
  "A crc32 hash could not be evaluated for the given file" => "Нема црц32 кодова за дати фајл",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_ExcludeExtension
  "File '%value%' has a false extension" => "Фајл '%value%' има невалидну екстензију",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_ExcludeMimeType
  "File '%value%' has a false mimetype of '%type%'" => "Фајл '%value%' има невалидан миме-тип '%type%'",
  "The mimetype of file '%value%' could not be detected" => "Миме-тип фајла '%value%' не може бити детектован",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_Exists
  "File '%value%' does not exist" => "Фајл '%value%' не постоји",
  
  // Zend_Validate_File_Extension
  "File '%value%' has a false extension" => "Фајл '%value%' има невалидну екстензију",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_FilesSize
  "All files in sum should have a maximum size of '%max%' but '%size%' were detected" => "Сви фајлови у збиру треба да имају максималну величину '%max%', величина послатих фајлова је '%size%'",
  "All files in sum should have a minimum size of '%min%' but '%size%' were detected" => "Сви фајлови у збиру треба да имају минималну величину '%min%', величина послатих фајлова је '%size%'",
  "One or more files can not be read" => "Један или више фајлова не може бити прочитан",
  
  // Zend_Validate_File_Hash
  "File '%value%' does not match the given hashes" => "Фајл '%value%' је неправилно кодиран",
  "A hash could not be evaluated for the given file" => "Хешеви нису пронађени за дати фајл",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_ImageSize
  "Maximum allowed width for image '%value%' should be '%maxwidth%' but '%width%' detected" => "Максимална дозвољена ширина слике '%value%' је '%maxwidth%', дата слика има ширину '%width%'",
  "Minimum expected width for image '%value%' should be '%minwidth%' but '%width%' detected" => "Минимална очекивана ширина слике '%value%' је '%minwidth%', дата слика има ширину '%width%'",
  "Maximum allowed height for image '%value%' should be '%maxheight%' but '%height%' detected" => "Максимална дозвољена висина слике '%value%' је '%maxheight%', дата слика има висину '%height%'",
  "Minimum expected height for image '%value%' should be '%minheight%' but '%height%' detected" => "Минимална очекивана висина слике '%value%' је '%minheight%', дата слика има висину '%height%'",
  "The size of image '%value%' could not be detected" => "Величина слике '%value%' не може бити одређена",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_IsCompressed
  "File '%value%' is not compressed, '%type%' detected" => "Фајл '%value%' није компресован, '%type%' детектован",
  "The mimetype of file '%value%' could not be detected" => "Миме-тип фајла '%value%' не може бити детектован",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_IsImage
  "File '%value%' is no image, '%type%' detected" => "Фајл '%value%' није слика, '%type%' детектован",
  "The mimetype of file '%value%' could not be detected" => "Миме-тип фајла '%value%' не може бити детектован",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_Md5
  "File '%value%' does not match the given md5 hashes" => "Фајл '%value%' не пролази мд5 проверу",
  "A md5 hash could not be evaluated for the given file" => "Нема мд5 хешева за дати фајл",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_MimeType
  "File '%value%' has a false mimetype of '%type%'" => "Фајл '%value%' има невалидан миме-тип '%type%'",
  "The mimetype of file '%value%' could not be detected" => "Миме-тип фајла '%value%' не може бити детектован",
  "File '%value%' can not be read" => "Фајл '%value%' не може бити прочитан",
  
  // Zend_Validate_File_NotExists
  "File '%value%' exists" => "Фајл '%value%' постоји",
  
  // Zend_Validate_File_Sha1
  "File '%value%' does not match the given sha1 hashes" => "Фајл '%value%' не пролази сха1 проверу",
  "A sha1 hash could not be evaluated for the given file" => "Нема сха1 хешева за дати фајл",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_Size
  "Maximum allowed size for file '%value%' is '%max%' but '%size%' detected" => "Максимална дозвољена величина фајла '%value%' је '%max%', дата величина је '%size%'",
  "Minimum expected size for file '%value%' is '%min%' but '%size%' detected" => "Минимална очекивана величина фајла '%value%' је '%min%', дата величина је '%size%'",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_File_Upload
  "File '%value%' exceeds the defined ini size" => "Фајл '%value%' превазилази максималну дозвољену величину",
  "File '%value%' exceeds the defined form size" => "Фајл '%value%' превазилази максималну дозвољену величину",
  "File '%value%' was only partially uploaded" => "Фајл '%value%' је само парцијално аплоадован",
  "File '%value%' was not uploaded" => "Фајл '%value%' није аплоадован",
  "No temporary directory was found for file '%value%'" => "Привремени директоријум није пронађен за фајл '%value%'",
  "File '%value%' can't be written" => "Фајл '%value%' не може бити измењен",
  "A PHP extension returned an error while uploading the file '%value%'" => "Екстензија је вратила грешку током уплоада фајла '%value%'",
  "File '%value%' was illegally uploaded. This could be a possible attack" => "Фајл '%value%' је илегално аплоадован, могућ напад",
  "File '%value%' was not found" => "Фајл '%value%' није пронађен",
  "Unknown error while uploading file '%value%'" => "Непозната грешка при уплоаду фајла '%value%'",
  
  // Zend_Validate_File_WordCount
  "Too much words, maximum '%max%' are allowed but '%count%' were counted" => "Превише речи, максимално '%max%' је дозвољено, '%count%' је избројано",
  "Too less words, minimum '%min%' are expected but '%count%' were counted" => "Премало речи, минимално '%min%' је очекивано, '%count%' је избројано",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_Float
  "'%value%' does not appear to be a float" => "'%value%' није разломљени број",
  "File '%value%' could not be found" => "Фајл '%value%' не може бити пронађен",
  
  // Zend_Validate_GreaterThan
  "'%value%' is not greater than '%min%'" => "'%value%' није веће од '%min%'",
  
  // Zend_Validate_Hex
  "Invalid type given, value should be a string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' has not only hexadecimal digit characters" => "'%value%' се не састоји само од хексадецималних карактера",
  
  // Zend_Validate_Hostname
  "Invalid type given, value should be a string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' appears to be an IP address, but IP addresses are not allowed" => "'%value%' је ИП адреса, ИП адресе нису дозвољене",
  "'%value%' appears to be a DNS hostname but cannot match TLD against known list" => "'%value%' је ДНС име хоста, али ТЛД није у листи познатих",
  "'%value%' appears to be a DNS hostname but contains a dash in an invalid position" => "'%value%' је ДНС име хоста, али садржи средњу црту (-) на недозвољеној позицији",
  "'%value%' appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'" => "'%value%' је ДНС име хоста, али се не поклапа са шемом за '%tld%' ТЛД",
  "'%value%' appears to be a DNS hostname but cannot extract TLD part" => "'%value%' је ДНС име хоста, али не може да се екстрактује ТЛД део '%tld%'",
  "'%value%' does not match the expected structure for a DNS hostname" => "'%value%' се не поклапа са очекиваном структуром ДНС имена хоста",
  "'%value%' does not appear to be a valid local network name" => "'%value%' није валидно име локалне мреже",
  "'%value%' appears to be a local network name but local network names are not allowed" => "'%value%' је име локалне мреже, локална имена мрежа нису дозвољена",
  "'%value%' appears to be a DNS hostname but the given punycode notation cannot be decoded" => "'%value%' је ДНС име хоста, али дата пуникод нотација не може бити декодирана",
  
  // Zend_Validate_Iban
  "Unknown country within the IBAN '%value%'" => "Непозната земља у ИБАН '%value%'",
  "'%value%' has a false IBAN format" => "'%value%' није у валидном ИБАН формату",
  "'%value%' has failed the IBAN check" => "'%value%' не пролази ИБАН проверу",
  
  // Zend_Validate_Identical
  "The two given tokens do not match" => "Токени се не поклапају",
  "No token was provided to match against" => "Токен за проверу није прослеђен",
  
  // Zend_Validate_InArray
  "'%value%' was not found in the haystack" => "'%value%' није пронађено у гомили",
  
  // Zend_Validate_Int
  "Invalid type given, value should be string or integer" => "Невалидан тип, вредност треба да буде текст или цео број",
  "'%value%' does not appear to be an integer" => "'%value%' није цео број",
  
  // Zend_Validate_Ip
  "Invalid type given, value should be a string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' does not appear to be a valid IP address" => "'%value%' није валидна ИП адреса",
  
  // Zend_Validate_Isbn
  "Invalid type given, value should be string or integer" => "Невалидан тип, вредност треба да буде текст или цео број",
  "'%value%' is no valid ISBN number" => "'%value%' није валидан ИСБН број",
  
  // Zend_Validate_LessThan
  "'%value%' is not less than '%max%'" => "'%value%' је мање од '%max%'",
  
  // Zend_Validate_NotEmpty
  "Invalid type given, value should be float, string, array, boolean or integer" => "Невалидан тип, вредност треба да буде текст, број или логичка вредност",
  "Value is required and can't be empty" => "Вредност је обавезна и не сме бити празна",
  
  // Zend_Validate_PostCode
  "Invalid type given. The value should be a string or a integer" => "Невалидан тип. Вредност треба да буде текст или цео број",
  "'%value%' does not appear to be a postal code" => "'%value%' није поштански број",  
  
  // Zend_Validate_Regex
  "Invalid type given, value should be string, integer or float" => "Невалидан тип, вредност треба да буде текст или број",
  "'%value%' does not match against pattern '%pattern%'" => "'%value%' се не поклапа са форматом '%pattern%'",
  "There was an internal error while using the pattern '%pattern%'" => "Догодила се грешка при коришћењу формата '%pattern%'",

  // Zend_Validate_Sitemap_Changefreq
  "'%value%' is no valid sitemap changefreq" => "'%value%' није валидна фреквенција промене мапе сајта",
  "Invalid type given, the value should be a string" => "Невалидан тип, вредност треба да буде текст",
  
  // Zend_Validate_Sitemap_Lastmod
  "'%value%' is no valid sitemap lastmod" => "'%value%' није валидан датум измене мапе сајта",
  "Invalid type given, the value should be a string" => "Невалидан тип, вредност треба да буде текст",
  
  // Zend_Validate_Sitemap_Loc
  "'%value%' is no valid sitemap location" => "'%value%' није валидна локација мапе сајта",
  "Invalid type given, the value should be a string" => "Невалидан тип, вредност треба да буде текст",
  
  // Zend_Validate_Sitemap_Priority
  "'%value%' is no valid sitemap priority" => "'%value%' није валидан приоритет мапе сајта",
  "Invalid type given, the value should be a integer, a float or a numeric string" => "Невалидан тип, вредност треба да буде број или нумберички низ",
  
  // Zend_Validate_StringLength
  "Invalid type given, value should be a string" => "Невалидан тип, вредност треба да буде текст",
  "'%value%' is less than %min% characters long" => "'%value%' има мање од %min% карактера",
  "'%value%' is more than %max% characters long" => "'%value%' има више од %max% карактера",
);