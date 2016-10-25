<?php
class content_update {
    public $modelid;
    public $fields;
    public $data;

    public function __construct($modelid, $fields) {
        $this->fields = $fields;
        $this->modelid = $modelid;
    }

    function update($data) {
        $info = array();
        $this->data = $data;
        foreach($data as $field=>$value) {
            if(!isset($this->fields[$field])) continue;
            $func = $this->fields[$field]['formtype'];
            $this->$func($field, $value);
        }
    }

    public function __call($name, $arguments) {
        list($field, $value) = $arguments;
        if (file_exists(MODEL_PATH . $name . DS . 'update.inc.php')) {
            include MODEL_PATH . $name . DS . 'update.inc.php';
        }
    }
}