<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends CI_Controller {

    function __construct(){
        parent ::__construct();
        check_not_login();
        $this->load->model(['sale_m', 'item_m']);
        $this->load->helper('html');
    }
	
    public function index()
	{
        $this->load->model('customer_m');
        echo link_tag('assets/css/main-style.css');
        $data = array(
            'name' => $this->session->name,
            'userid' => $this->session->userid,
            'invoice' => $this->sale_m->invoice_no(),
        );
		$this->template->load('template', 'transaction/sale/sale_form', $data);
	}

    public function item($barcode)
    {
        $item = $this->item_m->check_barcode($barcode)->result();
        echo json_encode($item);
    }
    
    public function checkout()
    {
        $item = json_decode($_POST['item']);
        $itemQuantity = array();

        foreach($item as $v){
            $itemQuantity[$v->id] = $v->quantity;
        }
        $checkout = $this->sale_m->checkout($_POST, $itemQuantity);
        echo $checkout;
    }
}

