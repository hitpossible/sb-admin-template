<?php
class Dashboard_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function loadProductData() {
        $sql = "SELECT * FROM product";
        $result = $this->db->query($sql);
        // $result = $this->db->select("product")->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        }
        
        return [];
    }

    public function saveProduct($data) {
        
        $insert = [
            "name"  => $data["productName"],
            "price" => $data["productPrice"],
            "qty"   => $data["productQty"],
        ];

        if($this->db->insert("product", $insert)) {
            return ["result" => true, "message" => "Save Successful."];
        } else {
            return ["result" => false, "message" => "Save failed."];
        }
    }
}
?>