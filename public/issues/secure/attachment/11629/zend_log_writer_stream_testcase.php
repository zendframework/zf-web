    /**
     * @group ZF-3396
     */
    public function testShutdownDoesNotClosePhpOutputStreamResource()
    {
        $writer = new Zend_Log_Writer_Stream('php://output', 'w+');

        ob_start();
        $writer->write(array('message' => 'this write should succeed'));
        ob_end_clean();

        $writer->shutdown();

        ob_start();
        echo "TEST";
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals("TEST", $output);

        $fp = fopen('php://output', 'w+');
        ob_start();
        fwrite($fp, "TEST");
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("TEST", $output);
    }