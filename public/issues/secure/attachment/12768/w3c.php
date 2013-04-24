<?php
class W3C extends Zend_Soap_Wsdl
	{
	/**
	 * W3C XSD Type map.
	 *
	 * This maps the lowercased type to correct cased type.
	 *
	 * @var array
	 */
	static protected $XSDTypeMap = array
		(
		'xsd:anyuri'           => 'xsd:anyURI',
		'xsd:base64binary'     => 'xsd:base64Binary',
		'xsd:boolean'          => 'xsd:boolean',
		'xsd:byte'             => 'xsd:byte',
		'xsd:date'             => 'xsd:date',
		'xsd:datetime'         => 'xsd:dateTime', // By providing the optional timezone element, the dateTimeStamp type below can be supported at no additional cost.
		'xsd:decimal'          => 'xsd:decimal',
		'xsd:double'           => 'xsd:double',
		'xsd:duration'         => 'xsd:duration',
		'xsd:float'            => 'xsd:float',
		'xsd:gday'             => 'xsd:gDay',
		'xsd:gmonth'           => 'xsd:gMonth',
		'xsd:gmonthday'        => 'xsd:gMonthDay',
		'xsd:gyear'            => 'xsd:gYear',
		'xsd:gyearmonth'       => 'xsd:gYearMonth',
		'xsd:hexbinary'        => 'xsd:hexBinary',
		'xsd:int'              => 'xsd:int',
		'xsd:integer'          => 'xsd:integer',
		'xsd:long'             => 'xsd:long',
		'xsd:normalizedstring' => 'xsd:normalizedString',
		'xsd:short'            => 'xsd:short',
		'xsd:string'           => 'xsd:string',
		'xsd:time'             => 'xsd:time',
		'xsd:token'            => 'xsd:token',
		);                    
                                      
	/**
	 * Add a W3C defined type.
	 *
	 * This is based upon the built-in datatypes as defined at
	 * {@link http://www.w3.org/TR/2004/REC-xmlschema-2-20041028/#built-in-datatypes Built-in datatypes}.
	 * Not all the types are supported, specifically:
	 * 1. anySimpleType
	 * 2. ENTITIES
	 * 3. ENTITY
	 * 4. ID
	 * 5. IDREF
	 * 6. IDREFS
	 * 7. language
	 * 8. Name
	 * 9. NCName
	 * 10. negativeInteger
	 * 11. NMTOKEN
	 * 12. NMTOKENS
	 * 13. nonNegativeInteger
	 * 14. nonPositiveInteger
	 * 15. NOTATION
	 * 16. positiveInteger
	 * 17. QName
	 * 18. unsignedByte
	 * 19. unsignedInt
	 * 20. unsignedLong
	 * 21. unsignedShort
	 *
	 * Additionally, new built-in datatypes, as defined at
	 * {@link http://www.w3.org/TR/xmlschema11-2/datatypes.html#built-in-datatypes Built-in Datatypes and Their Definitions}
	 * are not supported. These include:
	 * 1. anyAtomicType
	 * 2. dayTimeDuration
	 * 3. dayTimeStamp - This type can to be supported via the dateTime type with the optional timezone element always being populated.
	 * 4. precisionDecimal
	 * 5. yearMonthDuration
	 *
	 * @param  string $type Name of the type to be specified
	 *
	 * @return string XSD Type for the given PHP type
	 *
	 * @uses Zend_Soap_Wsdl::getType() to handle all unknown types.
	 */
	public function getType($type)
		{
		return isset(self::$XSDTypeMap[strtolower($type)]) ? self::$XSDTypeMap[strtolower($type)] : parent::getType($type);
		}
	}
