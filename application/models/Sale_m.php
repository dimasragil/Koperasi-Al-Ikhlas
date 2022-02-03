<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sale_m extends CI_Model
{

    public function invoice_no()
    {
        $sql = "SELECT MAX(MID(invoice,10,5)) AS invoice_no 
                FROM t_sale 
                WHERE MID(invoice,4,6) = DATE_FORMAT(CURDATE(), '%y%m%d')";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $n = ((int)$row->invoice_no) + 1;
            $no = sprintf("%'.04d", $n);
        } else {
            $no = "0001";
        }
        $invoice = "M2G" . date('ymd') . $no;
        return $invoice;
    }

    public function checkout($data, $item)
    {
        $query = $this->db->insert('t_sale', $data);
        foreach($item as $id => $val){
            $this->db->set("stock", "stock-$val", FALSE);
            $this->db->where("barcode", $id);
            $query = $this->db->update('p_item');            
        }
        return $query;
    }
}
