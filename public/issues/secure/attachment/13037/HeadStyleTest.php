<?php

    /**
     * @internal Issue ZF-9532
     */
    public function testNoHtmlCommentsForConditionalStyles()
    {
        $this->helper->appendStyle('
                body {
                    behavior:url("iefix/csshover3.htc");
                }
            ',
            array('conditional' => 'IE 6')
        );

        $style = $this->helper->toString();
        $lines = explode(PHP_EOL, $style);

        $hasHtmlComments = false;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == '<!--') {
                $hasHtmlComments = true;
                break;
            }
        }

        $this->assertFalse($hasHtmlComments);
    }

?>