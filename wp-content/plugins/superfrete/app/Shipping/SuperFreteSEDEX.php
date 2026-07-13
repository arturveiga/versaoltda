<?php

namespace SuperFrete_API\Shipping;

if (!defined('ABSPATH'))
    exit; // Segurança

require_once plugin_dir_path(__FILE__) . 'SuperFreteBase.php';

class SuperFreteSEDEX extends SuperFreteBase {

    public function __construct($instance_id = 0) {
        $this->id = 'superfrete_sedex';
        $this->method_title = __('SEDEX SuperFrete');
        $this->method_description = __('Envia utilizando SEDEX');
        
        parent::__construct($instance_id);
    }

    protected function get_service_id() {
        return 2; // ID do SEDEX na API
    }
}
