<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DnsModel extends CI_Model
{
    private $customer_id;

    function __construct()
    {
        parent::__construct();
        $this->customer_id = $this->session->userdata('customer_id');
        $this->dns = $this->load->database('dns', TRUE);
    }

    public function getDnsDomainId($domain_id)
    {
        $this->dns->select('id');
        $this->dns->from('domains');
        $this->dns->where(
            array(
                'domain_id' => $domain_id,
                'customer_id' => $this->customer_id
               )
        );
        $result = $this->dns->get();
        if($result->num_rows()) {
            return $result->row()->id;
        }
    }

    public function DeleteDNSDomain($domain_id)
    {
        $this->dns->where(array('customer_id' => $this->customer_id, 'domain_id' => $domain_id));
        $this->dns->delete('domains');
    }

    public function addDNSRecords($data)
    {
        $this->dns->insert_batch('records', $data);
    }

    public function deleteDNSRecords($dns_id)
    {
        $this->dns->where(array('domain_id' => $dns_id));
        $this->dns->delete('records');
    }

    public function removeAliaseRecords($alias, $domain_id)
    {
        $this->dns->where(array('domain_id' => $domain_id, 'name' => $alias));
        $this->dns->delete('records');
    }

    public function addDNSDomain($data)
    {
        $this->dns->insert('domains', $data);
        if($this->dns->affected_rows() > 0) {
            return $this->dns->insert_id();
        }else{
            return false;
        }
    }

}
