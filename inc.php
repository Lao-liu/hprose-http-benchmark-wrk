<?php
require_once dirname(__FILE__) . "/vendor/autoload.php";

use Hprose\Client;
use Hprose\Filter;

class logCall implements Filter{
	public function inputFilter($data, stdClass $context) {
        return $data;
    }
    public function outputFilter($data, stdClass $context) {
        error_log($this->log($data), 3, dirname(__FILE__) . "/hprose.lua");
        return $data;
    }

    public function log($param)
    {
    	$tmpl = <<<EOF
wrk.method = "POST"
wrk.body   = 'CALL-PARAMS'
wrk.headers["Content-Type"] = "text/plain;charset=UTF-8"
EOF;
		return str_replace('CALL-PARAMS', $param, $tmpl);
    }
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$serverUrl = getenv('URL');
$client = Client::create($serverUrl, false);
$client->addFilter(new logCall());

