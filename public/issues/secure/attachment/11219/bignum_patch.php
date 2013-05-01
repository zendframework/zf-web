#!/usr/local/bin/php
<?php
require_once("Zend/OpenId.php");
function Zend_OpenId_bigNumToBin($bn)
    {
        if (extension_loaded('gmp')) {
        	$s = gmp_strval($bn, 16);
        	if (strlen($s) % 2 != 0) {
        		$s = '0' . $s;
        	}

            return pack("H*", $s);
        } else if (extension_loaded('bcmath')) {
            $cmp = bccomp($bn, 0);
            if ($cmp == 0) {
                return (chr(0));
            } else if ($cmp < 0) {
                throw new Zend_OpenId_Exception(
                    'Big integer arithmetic error',
                    Zend_OpenId_Exception::ERROR_LONG_MATH);
            }
            $bin = "";
            while (bccomp($bn, 0) > 0) {
                $bin = chr(bcmod($bn, 256)) . $bin;
                $bn = bcdiv($bn, 256);
            }
            return $bin;
        }
        throw new Zend_OpenId_Exception(
            'The system doesn\'t have proper big integer extension',
            Zend_OpenId_Exception::UNSUPPORTED_LONG_MATH);
    }

function Zend_OpenId_bigNumToBin2($bn)
    {
			if (extension_loaded('gmp')) {
				/*The GMP conversion code in this function was liberally copied 
				 and adapted from  JanRain's Auth_OpenId_MathLibrary::longToBinary*/
				 $cmp = gmp_cmp($bn, 0);
				if ($cmp < 0) {
                throw new Zend_OpenId_Exception(
                    'Big integer arithmetic error',
                    Zend_OpenId_Exception::ERROR_LONG_MATH);
				}
				
				if ($cmp == 0) {
					return "\x00";
				}
				
				$bytes = array();
				
				while (gmp_cmp($bn, 0) > 0) {
					array_unshift($bytes, gmp_mod($bn, 256));
					$bn = gmp_div($bn, pow(2, 8));
				}
				
				if ($bytes && ($bytes[0] > 127)) {
					array_unshift($bytes, 0);
				}
				
				$string = '';
				foreach ($bytes as $byte) {
					$string .= pack('C', $byte);
				}
				
				return $string;
				
			} else if (extension_loaded('bcmath')) {
            $cmp = bccomp($bn, 0);
            if ($cmp == 0) {
                return (chr(0));
            } else if ($cmp < 0) {
                throw new Zend_OpenId_Exception(
                    'Big integer arithmetic error',
                    Zend_OpenId_Exception::ERROR_LONG_MATH);
            }
            $bin = "";
            while (bccomp($bn, 0) > 0) {
                $bin = chr(bcmod($bn, 256)) . $bin;
                $bn = bcdiv($bn, 256);
            }
            return $bin;
        }
        throw new Zend_OpenId_Exception(
            'The system doesn\'t have proper big integer extension',
            Zend_OpenId_Exception::UNSUPPORTED_LONG_MATH);
    }

$bad_num = "152817149694550674218127000880016558086495649673342109266844891848516479979208061013044826003648065314799377327863209343358374482723730715291045658792208047537656085860485321463066807033557985873980822699658935978266177231662645860424439753973392352509873163674319302330717279814688034712407083123270676444470";

$gmp1 = gmp_init($bad_num, 10);
$gmp2 = gmp_init($bad_num, 10);

print "Process 1: " . base64_encode(Zend_OpenId_bigNumToBin($gmp1)) . "\n";
print "Process 2: " . base64_encode(Zend_OpenId_bigNumToBin2($gmp2)) . "\n";
?>