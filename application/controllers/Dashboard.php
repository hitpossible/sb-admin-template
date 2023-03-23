<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $another_js;
    private $another_css;
    private $data;
    

    public function __construct()
    {
        parent::__construct();

        $this->load->library('parser');
        $this->load->helper('url');
        $this->load->library('form_validation');

        $this->load->model('Dashboard_model', 'Dashboard');

        $data['base_url'] = base_url();
		$data['site_url'] = site_url();
		$data['csrf_token_name'] = $this->security->get_csrf_token_name();
		$data['csrf_cookie_name'] = $this->config->item('csrf_cookie_name');
		$data['csrf_protection_field'] = $this->insert_csrf_field(true);

        $this->data = $data;
        $this->top_navbar_data = $data;
        $this->left_sidebar_data = $data;
    }

    protected function render_view($path)
    {
        $this->data['topbar'] = $this->parser->parse('template/top_navbar', $this->top_navbar_data, TRUE);
        $this->data['left_sidebar'] = $this->parser->parse('template/left_sidebar', $this->left_sidebar_data, TRUE);
        $this->data['page_content'] = $this->parser->parse($path, $this->data, TRUE);
        $this->data['another_css'] = $this->another_css;
        $this->data['another_js'] = $this->another_js;
        $this->parser->parse('template/homepage', $this->data);
    }

    public function index()
    {
        $this->another_js = "<script src='" . base_url('assets/js/dashboard.js') . "'></script>";

        $this->data["productList"] = $this->Dashboard->loadProductData();
        $this->render_view('dashboard');
    }

    public function loadProductData() {
        $result = $this->Dashboard->loadProductData();
        echo json_encode($result);
    }

    public function saveProduct() 
    {
        $data = $this->input->get(NULL, TRUE);

        $valid = $this->validateProduct($data);
        if ($valid["result"]) {
            echo json_encode(['result' => false, 'message' => $valid["message"]]);
        } else{
            $result = $this->Dashboard->saveProduct($data);
            echo json_encode($result);
        }

        
    }

    public function validateProduct($data) {
        $this->form_validation->set_data($data);
        
        $this->form_validation->set_rules('productName', 'ชื่อสินค้า', 'required');
        $this->form_validation->set_rules('productPrice', 'ราคาสินค้า', 'required');
        $this->form_validation->set_rules('productQty', 'จำนวนสินค้า', 'required');

		$this->form_validation->set_message('required', 'กรุณากรอก %s');

        if ($this->form_validation->run() == FALSE){
            $message = '';
			$message .= form_error('productName');
			$message .= form_error('productPrice');
			$message .= form_error('productQty');
            
            return ['result' => true, 'message' => $message];
        } else {
            return ['result' => false];
        }
    }

    public function insert_csrf_field($return = false)
    {
        $CI = &get_instance();
        $csrf = array(
            'name' => $CI->security->get_csrf_token_name(),
            'hash' => $CI->security->get_csrf_hash()
        );
        $input = '<input type="hidden" name="' . $csrf['name'] . '" value="' . $csrf['hash'] . '" />';
        if ($return == true) {
            return $input;
        } else {
            echo $input;
        }
    }
}