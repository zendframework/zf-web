<?php

/** XML_RPC_Server */
require_once 'XML/RPC/Server.php';


class XmlrpcController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/');
        return;

        // nothing sent? probably not an xmlrpc client
        if ($GLOBALS['HTTP_RAW_POST_DATA'] === null) {
            return $this->_redirect('/xmlrpc/help');
        }

        // dispatch map for functions mapped to RPC method calls
        $dispMap = array();

        $dispMap['test.sayHello']      = array('function'  => 'XmlrpcController::test_sayHello',
                                               'signature' => null,
                                               'docstring' => 'Says "hello"!');

        $dispMap['phparch.getInvoice'] = array('function'  => 'XmlrpcController::phparch_getInvoice',
                                               'signature' => null,
                                               'docstring' => 'Returns a fake invoice for the phparchitect article.');

        // service XML-RPC request now
        $server = new XML_RPC_Server($dispMap);
    }


    public function helpAction()
    {
        $this->_redirect('/');
    }


    /***********************************************************
     * XML-RPC Dispatchable Methods
     ***********************************************************/


    /**
     * Returns a string, "hello".
     *
     * XML-RPC Signatures: string test.sayHello()
     *
     * @param  XML_RPC_Message      $msg
     * @return XML_RPC_Response     +XML_RPC_Value string
     */
    static public function test_sayHello($msg)
    {
        $val = new XML_RPC_Value('hello', 'string');
        return new XML_RPC_Response($val);
    }


    /**
     * Return a fake customer invoice for the php|architect article.
     *
     * XML-RPC Signatures: struct phparch.getInvoice()
     *
     * @param  XML_RPC_Message      $msg
     * @return XML_RPC_Response     +XML_RPC_Value struct
     */
    static public function phparch_getInvoice($msg)
    {
        $invoice = array('details' => array('id'            => 1,
                                            'customer_name' => 'Andi Gutmans',
                                            'date'          => '15-Mar-2006',
                                            'subtotal'      => '$1.50',
                                            'ca_sales_tax'  => '0.00%',
                                            'shipping'      => '$0.00',
                                            'grand_total'   => '$1.50'),
                         'items' =>   array('0' => array('product_name' => 'First product name',
                                                         'quantity'     => '1',
                                                         'list_price'   => '$1.50',
                                                         'discount'     => '$0.00',
                                                         'unit_price'   => '$1.50',
                                                         'total_price'  => '$1.50')));

        $invoice = XML_RPC_encode($invoice);
        return new XML_RPC_Response($invoice);
    }

}