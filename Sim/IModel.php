<?php

namespace Sim;

use Sim\Core\Model;

class IModel extends Model {

    protected $config = [];

    function __construct() {
        global $_CONFIG;
        $this->config = $_CONFIG;
        parent::__construct();
    }

}