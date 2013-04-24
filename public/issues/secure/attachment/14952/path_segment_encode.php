<?php

class PathSegmentEncode
{
    private static $_convertMap = array(
        '%00','%01','%02','%03','%04','%05','%06','%07','%08','%09','%0A','%0B','%0C','%0D','%0E','%0F',
        '%10','%11','%12','%13','%14','%15','%16','%17','%18','%19','%1A','%1B','%1C','%1D','%1E','%1F',
        '%20',  '!','%22','%23',  '$','%25',  '&', '\'',  '(',  ')',  '*',  '+',  ',',  '-',  '.','%2F',
          '0',  '1',  '2',  '3',  '4',  '5',  '6',  '7',  '8',  '9','%3A','%3B','%3C','%3D','%3E','%3F',
        '%40',  'A',  'B',  'C',  'D',  'E',  'F',  'G',  'H',  'I',  'J',  'K',  'L',  'M',  'N',  'O',
          'P',  'Q',  'R',  'S',  'T',  'U',  'V',  'W',  'X',  'Y',  'Z','%5B','%5C','%5D','%5E',  '_',
        '%60',  'a',  'b',  'c',  'd',  'e',  'f',  'g',  'h',  'i',  'j',  'k',  'l',  'm',  'n',  'o',
          'p',  'q',  'r',  's',  't',  'u',  'v',  'w',  'x',  'y',  'z','%7B','%7C','%7D',  '~','%7F',
        '%80','%81','%82','%83','%84','%85','%86','%87','%88','%89','%8A','%8B','%8C','%8D','%8E','%8F',
        '%90','%91','%92','%93','%94','%95','%96','%97','%98','%99','%9A','%9B','%9C','%9D','%9E','%9F',
        '%A0','%A1','%A2','%A3','%A4','%A5','%A6','%A7','%A8','%A9','%AA','%AB','%AC','%AD','%AE','%AF',
        '%B0','%B1','%B2','%B3','%B4','%B5','%B6','%B7','%B8','%B9','%BA','%BB','%BC','%BD','%BE','%BF',
        '%C0','%C1','%C2','%C3','%C4','%C5','%C6','%C7','%C8','%C9','%CA','%CB','%CC','%CD','%CE','%CF',
        '%D0','%D1','%D2','%D3','%D4','%D5','%D6','%D7','%D8','%D9','%DA','%DB','%DC','%DD','%DE','%DF',
        '%E0','%E1','%E2','%E3','%E4','%E5','%E6','%E7','%E8','%E9','%EA','%EB','%EC','%ED','%EE','%EF',
        '%F0','%F1','%F2','%F3','%F4','%F5','%F6','%F7','%F8','%F9','%FA','%FB','%FC','%FD','%FE','%FF',        
    );
    
    private static $_convertString = "%00%01%02%03%04%05%06%07%08%09%0A%0B%0C%0D%0E%0F%10%11%12%13%14%15%16%17%18%19%1A%1B%1C%1D%1E%1F%20%21%22%23%24%25%26%27%28%29%2A%2B%2C%2D%2E%2F%30%31%32%33%34%35%36%37%38%39%3A%3B%3C%3D%3E%3F%40%41%42%43%44%45%46%47%48%49%4A%4B%4C%4D%4E%4F%50%51%52%53%54%55%56%57%58%59%5A%5B%5C%5D%5E%5F%60%61%62%63%64%65%66%67%68%69%6A%6B%6C%6D%6E%6F%70%71%72%73%74%75%76%77%78%79%7A%7B%7C%7D%7E%7F%80%81%82%83%84%85%86%87%88%89%8A%8B%8C%8D%8E%8F%90%91%92%93%94%95%96%97%98%99%9A%9B%9C%9D%9E%9F%A0%A1%A2%A3%A4%A5%A6%A7%A8%A9%AA%AB%AC%AD%AE%AF%B0%B1%B2%B3%B4%B5%B6%B7%B8%B9%BA%BB%BC%BD%BE%BF%C0%C1%C2%C3%C4%C5%C6%C7%C8%C9%CA%CB%CC%CD%CE%CF%D0%D1%D2%D3%D4%D5%D6%D7%D8%D9%DA%DB%DC%DD%DE%DF%E0%E1%E2%E3%E4%E5%E6%E7%E8%E9%EA%EB%EC%ED%EE%EF%F0%F1%F2%F3%F4%F5%F6%F7%F8%F9%FA%FB%FC%FD%FE%FF";
    
    private static $_translateMap = array(
        "x00" => '%00',"x01" => '%01',"x02" => '%02',"x03" => '%03',"x04" => '%04',"x05" => '%05',"x06" => '%06',"x07" => '%07',
        "x08" => '%08',"x09" => '%09',"x0A" => '%0A',"x0B" => '%0B',"x0C" => '%0C',"x0D" => '%0D',"x0E" => '%0E',"x0F" => '%0F',
        "x10" => '%10',"x11" => '%11',"x12" => '%12',"x13" => '%13',"x14" => '%14',"x15" => '%15',"x16" => '%16',"x17" => '%17',
        "x18" => '%18',"x19" => '%19',"x1A" => '%1A',"x1B" => '%1B',"x1C" => '%1C',"x1D" => '%1D',"x1E" => '%1E',"x1F" => '%1F',
        "x20" => '%20',               "x22" => '%22',"x23" => '%23',               "x25" => '%25',
                                                                                                                 "x2F" => '%2F',
                                                                                                                 
                                      "x3A" => '%3A',"x3B" => '%3B',"x3C" => '%3C',"x3D" => '%3D',"x3E" => '%3E',"x3F" => '%3F',
        "x40" => '%40',
                                                     "x5B" => '%5B',"x5C" => '%5C',"x5D" => '%5D',"x5E" => '%5E',
        "x60" => '%60',
                                                     "x7B" => '%7B',"x7C" => '%7C',"x7D" => '%7D',               "x7F" => '%7F',
        "x80" => '%80',"x81" => '%81',"x82" => '%82',"x83" => '%83',"x84" => '%84',"x85" => '%85',"x86" => '%86',"x87" => '%87',
        "x88" => '%88',"x89" => '%89',"x8A" => '%8A',"x8B" => '%8B',"x8C" => '%8C',"x8D" => '%8D',"x8E" => '%8E',"x8F" => '%8F',
        "x90" => '%90',"x91" => '%91',"x92" => '%92',"x93" => '%93',"x94" => '%94',"x95" => '%95',"x96" => '%96',"x97" => '%97',
        "x98" => '%98',"x99" => '%99',"x9A" => '%9A',"x9B" => '%9B',"x9C" => '%9C',"x9D" => '%9D',"x9E" => '%9E',"x9F" => '%9F',
        "xA0" => '%A0',"xA1" => '%A1',"xA2" => '%A2',"xA3" => '%A3',"xA4" => '%A4',"xA5" => '%A5',"xA6" => '%A6',"xA7" => '%A7',
        "xA8" => '%A8',"xA9" => '%A9',"xAA" => '%AA',"xAB" => '%AB',"xAC" => '%AC',"xAD" => '%AD',"xAE" => '%AE',"xAF" => '%AF',
        "xB0" => '%B0',"xB1" => '%B1',"xB2" => '%B2',"xB3" => '%B3',"xB4" => '%B4',"xB5" => '%B5',"xB6" => '%B6',"xB7" => '%B7',
        "xB8" => '%B8',"xB9" => '%B9',"xBA" => '%BA',"xBB" => '%BB',"xBC" => '%BC',"xBD" => '%BD',"xBE" => '%BE',"xBF" => '%BF',
        "xC0" => '%C0',"xC1" => '%C1',"xC2" => '%C2',"xC3" => '%C3',"xC4" => '%C4',"xC5" => '%C5',"xC6" => '%C6',"xC7" => '%C7',
        "xC8" => '%C8',"xC9" => '%C9',"xCA" => '%CA',"xCB" => '%CB',"xCC" => '%CC',"xCD" => '%CD',"xCE" => '%CE',"xCF" => '%CF',
        "xD0" => '%D0',"xD1" => '%D1',"xD2" => '%D2',"xD3" => '%D3',"xD4" => '%D4',"xD5" => '%D5',"xD6" => '%D6',"xD7" => '%D7',
        "xD8" => '%D8',"xD9" => '%D9',"xDA" => '%DA',"xDB" => '%DB',"xDC" => '%DC',"xDD" => '%DD',"xDE" => '%DE',"xDF" => '%DF',
        "xE0" => '%E0',"xE1" => '%E1',"xE2" => '%E2',"xE3" => '%E3',"xE4" => '%E4',"xE5" => '%E5',"xE6" => '%E6',"xE7" => '%E7',
        "xE8" => '%E8',"xE9" => '%E9',"xEA" => '%EA',"xEB" => '%EB',"xEC" => '%EC',"xED" => '%ED',"xEE" => '%EE',"xEF" => '%EF',
        "xF0" => '%F0',"xF1" => '%F1',"xF2" => '%F2',"xF3" => '%F3',"xF4" => '%F4',"xF5" => '%F5',"xF6" => '%F6',"xF7" => '%F7',
        "xF8" => '%F8',"xF9" => '%F9',"xFA" => '%FA',"xFB" => '%FB',"xFC" => '%FC',"xFD" => '%FD',"xFE" => '%FE',"xFF" => '%FF',        
    );
    
    public function map($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        $map = & self::$_convertMap;
        for ($i = 0; $i < $length; ++$i) {
            $encoded .= $map[ord(substr($raw, $i, 1))];
        }
        return $encoded;
    }
    
    public function stringmap($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        $convertString = self::$_convertString;
        for ($i = 0; $i < $length; ++$i) {
            $char = $raw[$i];
            $encoded .= substr($convertString, ord($char) * 3, 3);
        }
    }
    
    public function mapAlnumFiltered($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        $map = & self::$_convertMap;
        for ($i = 0; $i < $length; ++$i) {
            $char = $raw[$i];
            $charCode = ord($char);
            if (
                (0x61 <= $charCode && $charCode <= 0x7A) || // ALPHA - lower
                (0x41 <= $charCode && $charCode <= 0x5A) || // ALPHA - upper
                (0x30 <= $charCode && $charCode <= 0x39)   // DIGIT
            ) {
                $encoded .= $char;
            } else {
                $encoded .= $map[$charCode];
            }
        }
    }
    
    public function stringmapAlnumFiltered($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        $convertString = self::$_convertString;
        for ($i = 0; $i < $length; ++$i) {
            $char = $raw[$i];
            $charCode = ord($char);
            if (
                (0x61 <= $charCode && $charCode <= 0x7A) || // ALPHA - lower
                (0x41 <= $charCode && $charCode <= 0x5A) || // ALPHA - upper
                (0x30 <= $charCode && $charCode <= 0x39)   // DIGIT
            ) {
                $encoded .= $char;
            } else {
                $encoded .= substr($convertString, $charCode * 3, 3);
            }
        }
    }
    
    public function mapAllFiltered($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        $map = & self::$_convertMap;
        for ($i = 0; $i < $length; ++$i) {
            $char = $raw[$i];
            $charCode = ord($char);
            if (
                (0x41 <= $charCode && $charCode <= 0x5A) || // ALPHA - upper
                (0x61 <= $charCode && $charCode <= 0x7A) || // ALPHA - lower
                (0x30 <= $charCode && $charCode <= 0x39) || // DIGIT
                $charCode == 0x5F || $charCode == 0x7E ||   // "_" / "~"
                $charCode == 0x21 || $charCode == 0x24 ||   // "!" / "$"
                (0x26 <= $charCode && $charCode <= 0x2E) || // "&" / "'" / "(" / ")" / "*" / "+" / "," / "-" / "."
                $charCode == 0x3A || $charCode == 0x3B ||   // ";" / ":"
                $charCode == 0x3D || $charCode == 0x40      // "=" / "@"
            ) {
                $encoded .= $char;
            } else {
                $encoded .= $map[$charCode];
            }
        }
    }
    
    public function convertFiltered($raw)
    {
        $raw = (string) $raw;
        $length = strlen($raw);
        $encoded = '';
        for ($i = 0; $i < $length; ++$i) {
            $char = $raw[$i];
            $charCode = ord($char);
            if (
                (0x41 <= $charCode && $charCode <= 0x5A) || // ALPHA - upper
                (0x61 <= $charCode && $charCode <= 0x7A) || // ALPHA - lower
                (0x30 <= $charCode && $charCode <= 0x39) || // DIGIT
                $charCode == 0x5F || $charCode == 0x7E ||   // "_" / "~"
                $charCode == 0x21 || $charCode == 0x24 ||   // "!" / "$"
                (0x26 <= $charCode && $charCode <= 0x2E) || // "&" / "'" / "(" / ")" / "*" / "+" / "," / "-" / "."
                $charCode == 0x3A || $charCode == 0x3B ||   // ";" / ":"
                $charCode == 0x3D || $charCode == 0x40      // "=" / "@"
            ) {
                $encoded .= $char;
            } else {
                $encoded .= '%' . strtoupper(bin2hex($char));
            }
        }
    }
    
    public function translate($raw)
    {
        return strtr($raw, self::$_translateMap);
    }
    
    public function replace($raw)
    {
        return str_replace(array_keys(self::$_translateMap), self::$_translateMap, $raw);
    }
}
$encoder = new PathSegmentEncode();

$functions = array(
    array(
        'name' => 'urlencode',
        'call' => 'urlencode',
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'rawurlencode',
        'call' => 'rawurlencode',
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::translate',
        'call' => array($encoder, 'translate'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::mapAlnumFiltered',
        'call' => array($encoder, 'mapAlnumFiltered'),
        'time' => 0.0,
        'rate' => 0.0,
    ),    
    array(
        'name' => 'PathSegmentEncode::stringmapAlnumFiltered',
        'call' => array($encoder, 'stringmapAlnumFiltered'),
        'time' => 0.0,
        'rate' => 0.0,
    ),    
    array(
        'name' => 'PathSegmentEncode::map',
        'call' => array($encoder, 'map'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::stringmap',
        'call' => array($encoder, 'stringmap'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::mapAllFiltered',
        'call' => array($encoder, 'mapAllFiltered'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::convertFiltered',
        'call' => array($encoder, 'convertFiltered'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
    array(
        'name' => 'PathSegmentEncode::replace',
        'call' => array($encoder, 'replace'),
        'time' => 0.0,
        'rate' => 0.0,
    ),
);

$testValues = array(
    'nothing-todo-here',
    'Hello World!',
    '!"§$%&/()=?.,;-_\'*+"',
    '0123456789',
    'this is some real long test string wich contains all some ugly chars !"§$%&/()=?.,;-_\'*+" 0123456789'
);
for ($i = 0; $i < 1000; ++$i) {
    foreach ($testValues as $testValue) {
        foreach ($functions as $key => $function) {
            $call = $function['call'];
            $time = microtime(true);
            $return = call_user_func($call, $testValue);
            $time = microtime(true) - $time;
            $functions[$key]['time'] += $time;
        }
    }
}

$minTime = microtime(true);
foreach ($functions as $function) {
    $minTime = min($function['time'], $minTime);
}
foreach ($functions as $key => $function) {
    $functions[$key]['rate'] = $functions[$key]['time'] / $minTime;
}

foreach ($functions as $function) {
    printf(
        "function: %s\n  time: %fms\n  rate: %f%%\n",
        $function['name'],
        $function['time'] * 1000,
        $function['rate'] * 100
    );
}
