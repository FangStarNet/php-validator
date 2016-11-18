<?php

namespace FangStarNet\PHPValidator\Common;

class Validator
{
    private $data;

    private $rules;

    private $messages;

    private $scale = 8;

    private $rules_data = [];

    private $alias = [];

    private $to_type = [];

    private $array_str = [];

    private $float = [];

    private $files = [];

    private $err_msg = "";

    private $has_fails = false;

    private $to_type_keys = [
        'string', 'boolean', 'integer', 'float', 'array', 'object'
    ];

    // used on function : check_to_type 、toType
    private $to_type_keys_mul = [
        'str_array', 'scale'
    ];

    public function __construct(array &$data, array $rules, array $messages)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->scale = ConfigData::getConfig()['scale'];
    }

    public function has_fails()
    {
        return $this->has_fails;
    }

    public function err_msg()
    {
        return $this->err_msg;
    }

    public function data()
    {
        return $this->data;
    }

    public function dissectRuleStr()
    {
        $dissect_obj = new DissectRule();
        foreach ($this->rules as $key => $rule) {
            $dissect_obj->handle($rule, $dissect_data);
            $this->rules_data[$key] = $dissect_data;
        }
    }

    public function check()
    {
        foreach ($this->rules_data as $parameter_name => $v_d) {
            // check rule : about exists and not null
            $file_keys = [
                'file_exists', 'file_type_in', 'file_max', 'file_min', 'file_size_between',
                'present', 'required_with', 'required_with_all', 'required_without', 'required_without_all', 'same', 'different'
            ];
            foreach ($file_keys as $key) {
                if (! isset($v_d[$key])) {
                    continue;
                }
                $rule_data = $v_d[$key];
                switch ($key) {
                    case 'file_exists' : $rule_result = $this->check_file_exists($parameter_name);break;
                    case 'file_type_in' : $rule_result = $this->check_file_type_in($parameter_name, $rule_data);break;
                    case 'file_max' : $rule_result = $this->check_file_max($parameter_name, $rule_data);break;
                    case 'file_min' : $rule_result = $this->check_file_min($parameter_name, $rule_data);break;
                    case 'file_size_between' : $rule_result = $this->check_file_size_between($parameter_name, $rule_data);break;
                    case 'present' : $rule_result = $this->check_present($parameter_name);break;
                    case 'different' : $rule_result = $this->check_different($parameter_name, $rule_data);break;
                    case 'same' : $rule_result = $this->check_same($parameter_name, $rule_data);break;
                    case 'required_with' : $rule_result = $this->check_required_with($parameter_name, $rule_data);break;
                    case 'required_with_all' : $rule_result = $this->check_required_with_all($parameter_name, $rule_data);break;
                    case 'required_without' : $rule_result = $this->check_required_without($parameter_name, $rule_data);break;
                    case 'required_without_all' : $rule_result = $this->check_required_without_all($parameter_name, $rule_data);break;
                    default :
                        $rule_result = true;
                }
                if (! $rule_result) {
                    return false;
                }
            }

            // check other rule when it is exists!
            if (! array_key_exists($parameter_name, $this->data)) {
                continue;
            }

            $value = $this->data[$parameter_name];

            foreach ($v_d as $rule => $rule_data) {
                switch ($rule) {
                    case 'alpha' : $rule_result = $this->check_alpha($parameter_name, $value);break;
                    case 'num' : $rule_result = $this->check_num($parameter_name, $value);break;
                    case 'alpha_num' : $rule_result = $this->check_alpha_num($parameter_name, $value);break;
                    case 'alpha_dish' : $rule_result = $this->check_alpha_dish($parameter_name, $value);break;
                    case 'var' : $rule_result = $this->check_var($parameter_name, $value);break;
                    case 'ip' : $rule_result = $this->check_ip($parameter_name, $value);break;
                    case 'url' : $rule_result = $this->check_url($parameter_name, $value);break;
                    case 'email' : $rule_result = $this->check_email($parameter_name, $value);break;
                    case 'mobile' : $rule_result = $this->check_mobile($parameter_name, $value);break;
                    case 'json' : $rule_result = $this->check_json($parameter_name, $value);break;
                    case 'timestamp' : $rule_result = $this->check_timestamp($parameter_name, $value);break;
                    case 'date_format' : $rule_result = $this->check_date_format($parameter_name, $value, $rule_data);break;
                    case 'regex' : $rule_result = $this->check_regex($parameter_name, $value, $rule_data);break;
                    case 'string' : $rule_result = $this->check_string($parameter_name, $value);break;
                    case 'boolean' : $rule_result = $this->check_boolean($parameter_name, $value);break;
                    case 'integer' : $rule_result = $this->check_integer($parameter_name, $value);break;
                    case 'float' : $rule_result = $this->check_float($parameter_name, $value);break;
                    case 'array' : $rule_result = $this->check_array($parameter_name, $value);break;
                    case 'object' : $rule_result = $this->check_object($parameter_name, $value);break;
                    case 'object_of' : $rule_result = $this->check_object_of($parameter_name, $value, $rule_data);break;
                    case 'integer_str' : $rule_result = $this->check_integer_str($parameter_name, $value);break;
                    case 'float_str' : $rule_result = $this->check_float_str($parameter_name, $value);break;
                    case 'numeric_str' : $rule_result = $this->check_numeric_str($parameter_name, $value);break;
                    case 'array_str' : $rule_result = $this->check_array_str($parameter_name, $value);break;
                    case 'max' : $rule_result = $this->check_max($parameter_name, $value, $rule_data);break;
                    case 'length_max' : $rule_result = $this->check_length_max($parameter_name, $value, $rule_data);break;
                    case 'min' : $rule_result = $this->check_min($parameter_name, $value, $rule_data);break;
                    case 'length_min' : $rule_result = $this->check_length_min($parameter_name, $value, $rule_data);break;
                    case 'length' : $rule_result = $this->check_length($parameter_name, $value, $rule_data);break;
                    case 'between' : $rule_result = $this->check_between($parameter_name, $value, $rule_data);break;
                    case 'length_between' : $rule_result = $this->check_length_between($parameter_name, $value, $rule_data);break;
                    case 'in' : $rule_result = $this->check_in($parameter_name, $value, $rule_data);break;
                    case 'not_in' : $rule_result = $this->check_not_in($parameter_name, $value, $rule_data);break;
                    case 'filled' : $rule_result = $this->check_filled($parameter_name, $value);break;
                    case 'distinct' : $rule_result = $this->check_distinct($parameter_name, $value);break;
                    case 'alias' : $rule_result = $this->add_alias($parameter_name, $rule_data);break;
                    case 'to_type' : $rule_result = $this->check_to_type($parameter_name, $rule_data);break;

                    default :
                        $rule_result = true;
                }
                if (! $rule_result) {
                    return false;
                }
            }
        }
        return true;
    }

    public function toType()
    {
        if ($this->has_fails) {
            return false;
        }
        foreach ($this->to_type as $key => $type_data) {
            $value = array_key_exists($key, $this->data) ? $this->data[$key] : "";
            switch ($type_data) {
                case 'string' :$this->data[$key] = strval($value); break;
                case 'boolean' :$this->data[$key] = boolval($value); break;
                case 'integer' :$this->data[$key] = intval($value); break;
                case 'float' :$this->data[$key] = floatval($value); break;
                case 'array' :$this->data[$key] = (array) $value; break;
                case 'object' :$this->data[$key] = (object) $value; break;
                default :
                    $div_arr = explode(':', $type_data);
                    switch ($div_arr[0]) {
                        case 'str_array' :
                            if (! $this->toType_str_array($key, $value, $div_arr[1])) {
                                return false;
                            }
                            break;
                        case 'scale' :
                            if (! $this->toType_scale($key, $value, $div_arr[1])) {
                                return false;
                            }
                            break;
                        default :
                            break;
                    }
                    break;
            }
        }
        return true;
    }

    public function toAlias()
    {
        if ($this->has_fails) {
            return false;
        }

        $data = [];
        if (! empty($this->alias)) {
            foreach ($this->data as $key => $value) {
                $new_key = isset($this->alias[$key]) ? $this->alias[$key] : $key;
                $data[$new_key] = $value;
            }
            $this->data = $data;
        }
        return true;
    }

    private function _check_not_null($parameter_name)
    {
        if (isset($this->data[$parameter_name]) && $this->data[$parameter_name] !== "" && $this->data[$parameter_name] !== []) {
            return true;
        }
        return false;
    }

    private function getFile($parameter_name)
    {
        if (array_key_exists($parameter_name, $this->files)) {
            return $this->files[$parameter_name];
        }
        if (! isset($_FILES[$parameter_name])) {
            $this->files[$parameter_name] = [];
        } else {
            $this->files[$parameter_name] = $_FILES[$parameter_name];
            if ($this->files[$parameter_name]['error'] != 0) {
                throw new \Exception("Upload file failed");
            }
        }

        return $this->files[$parameter_name];
    }

    private function setMsg_one($rule, $parameter_name)
    {
        $this->has_fails = true;
        $custom_key = $parameter_name . "." . $rule;

        if (array_key_exists($custom_key, $this->messages)) {
            $this->err_msg = $this->messages[$custom_key];
        } else {
            $this->err_msg = str_replace('{parameter-name}', $parameter_name, Lang::lang($rule));
        }
    }

    private function setMsg_two($rule, $parameter_name, $bind_value_1)
    {
        $this->has_fails = true;
        $custom_key = $parameter_name . "." . $rule;

        if (array_key_exists($custom_key, $this->messages)) {
            $this->err_msg = $this->messages[$custom_key];
        } else {
            $replace = [
                '{parameter-name}' => $parameter_name,
                '{bind-value_1}' => $bind_value_1,
            ];
            $this->err_msg = strtr(Lang::lang($rule), $replace);
        }
    }

    private function setMsg_three($rule, $parameter_name, $bind_value_1, $bind_value_2)
    {
        $this->has_fails = true;
        $custom_key = $parameter_name . "." . $rule;

        if (array_key_exists($custom_key, $this->messages)) {
            $this->err_msg = $this->messages[$custom_key];
        } else {
            $replace = [
                '{parameter-name}' => $parameter_name,
                '{bind-value_1}' => $bind_value_1,
                '{bind-value_2}' => $bind_value_2,
            ];
            $this->err_msg = strtr(Lang::lang($rule), $replace);
        }
    }

    private function check_present($parameter_name)
    {
        if (! $this->_check_not_null($parameter_name)) {
            $this->setMsg_one('present', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_alpha($parameter_name, $value)
    {
        $rv = preg_match("/^[a-zA-Z]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('alpha', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_num($parameter_name, $value)
    {
        $rv = preg_match("/^[0-9]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('num', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_alpha_num($parameter_name, $value)
    {
        $rv = preg_match("/^[0-9A-Za-z]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('alpha_num', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_alpha_dish($parameter_name, $value)
    {
        $rv = preg_match("/^[0-9A-Za-z_-]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('alpha_dish', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_var($parameter_name, $value)
    {
        $rv = preg_match("/^[A-Za-z_]{1}[0-9A-Za-z_]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('var', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_ip($parameter_name, $value)
    {
        if(! filter_var(strval($value), FILTER_VALIDATE_IP)) {
            $this->setMsg_one('ip', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_url($parameter_name, $value)
    {
        if(! filter_var(strval($value), FILTER_VALIDATE_URL)) {
            $this->setMsg_one('url', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_email($parameter_name, $value)
    {
        if(! filter_var(strval($value), FILTER_VALIDATE_EMAIL)) {
            $this->setMsg_one('email', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_mobile($parameter_name, $value)
    {
        $rv = preg_match("/^1[3-8]{1}[0-9]{9}$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('mobile', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_json($parameter_name, $value)
    {
        if (json_decode(strval($value), true) == []) {
            $this->setMsg_one('json', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_timestamp($parameter_name, $value)
    {
        $value = strval($value);
        $rv = preg_match("/^[0-9]*$/", $value);
        if ($rv == 0) {
            $this->setMsg_one('timestamp', $parameter_name);
            return false;
        }

        @$timestamp = date("Y-m-d H:i:s", $value);
        if ($timestamp == false) {
            $this->setMsg_one('timestamp', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_date_format($parameter_name, $value, $rule_data)
    {
        $value = strval($value);
        @$timestamp = strtotime($value);
        if ($timestamp == false) {
            $this->setMsg_one('date_format', $parameter_name);
            return false;
        }

        @$date = date($rule_data, $timestamp);
        if ($date == false) {
            $this->setMsg_one('date_format', $parameter_name);
            return false;
        }

        if ($date != $value) {
            $this->setMsg_one('date_format', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_regex($parameter_name, $value, $rule_data)
    {
        $rv = preg_match($rule_data, strval($value));
        if ($rv == 0) {
            $this->setMsg_one('regex', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_string($parameter_name, $value)
    {
        if (! is_string($value)) {
            $this->setMsg_one('string', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_boolean($parameter_name, $value)
    {
        if (! is_bool($value)) {
            $this->setMsg_one('boolean', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_integer($parameter_name, $value)
    {
        if (! is_int($value)) {
            $this->setMsg_one('integer', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_float($parameter_name, $value)
    {
        if (! is_float($value)) {
            $this->setMsg_one('float', $parameter_name);
            return false;
        }
        $this->float[$parameter_name] = $value;
        return true;
    }

    private function check_array($parameter_name, $value)
    {
        if (! is_array($value)) {
            $this->setMsg_one('array', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_object($parameter_name, $value)
    {
        if (! is_object($value)) {
            $this->setMsg_one('object', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_object_of($parameter_name, $value, $rule_data)
    {
        if (! is_object($value)) {
            $this->setMsg_one('object', $parameter_name);
            return false;
        }

        if (get_class($value) != $rule_data) {
            $this->setMsg_two('object_of', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_integer_str($parameter_name, $value)
    {
        $rv = preg_match("/^[-]?[1-9]{1}[0-9]*$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('integer_str', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_float_str($parameter_name, $value)
    {
        $value = strval($value);
        $rv = preg_match("/^[-]?(([1-9]{1}[0-9]*\.[0-9]*)|(0\.[0-9]+))$/", strval($value));
        if ($rv == 0) {
            $this->setMsg_one('float_str', $parameter_name);
            return false;
        }
        $this->float[$parameter_name] = $value;
        return true;
    }

    private function check_numeric_str($parameter_name, $value)
    {
        if (! is_numeric($value)) {
            $this->setMsg_one('numeric_str', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_array_str($parameter_name, $value)
    {
        $value = strval($value);
        $len = strlen($value);
        if ($len < 3) {
            $this->setMsg_one('array_str', $parameter_name);
            return false;
        }
        if ($value[0] != "[" || $value[$len - 1] != "]") {
            $this->setMsg_one('array_str', $parameter_name);
            return false;
        }
        $this->array_str[$parameter_name] = substr($value, 1, $len - 2);
        return true;
    }

    private function check_max($parameter_name, $value, $rule_data)
    {
        $result = bcsub($rule_data, $value);
        $rv = preg_match("/^-.*$/", $result);
        if ($rv == 1) {
            $this->setMsg_one('max', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_length_max($parameter_name, $value, $rule_data)
    {
        $value = mb_strlen(strval($value));
        $len = intval($rule_data);
        if ($value > $len) {
            $this->setMsg_one('length_max', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_min($parameter_name, $value, $rule_data)
    {
        $result = bcsub($value, $rule_data);
        $rv = preg_match("/^-.*$/", $result);
        if ($rv == 1) {
            $this->setMsg_one('min', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_length_min($parameter_name, $value, $rule_data)
    {
        $value = mb_strlen(strval($value));
        $len = intval($rule_data);
        if ($value < $len) {
            $this->setMsg_one('length_min', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_length($parameter_name, $value, $rule_data)
    {
        $value = mb_strlen(strval($value));
        $len = intval($rule_data);
        if ($len != $value) {
            $this->setMsg_two('length', $parameter_name, $len);
            return false;
        }
        return true;
    }

    private function check_between($parameter_name, $value, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        if (count($div_arr) != 2) {
            throw new \Exception("Illegal PHPValidator expression: The 'between' rule's value must like ' min , max '");
        }
        $v1 = trim($div_arr[0]);
        $v2 = trim($div_arr[1]);
        if ($v2 < $v1) {
            $v = $v1;
            $v1 = $v2;
            $v2 = $v;
        }

        $max_result = bcsub($v2, $value);
        $rv = preg_match("/^-.*$/", $max_result);
        if ($rv == 1) {
            $this->setMsg_three('between', $parameter_name, $v1, $v2);
            return false;
        }

        $min_result = bcsub($value, $v1);
        $rv = preg_match("/^-.*$/", $min_result);
        if ($rv == 1) {
            $this->setMsg_three('between', $parameter_name, $v1, $v2);
            return false;
        }
        return true;
    }

    private function check_length_between($parameter_name, $value, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        if (count($div_arr) != 2) {
            throw new \Exception("Illegal PHPValidator expression: The 'length_between' rule's value must like ' min , max '");
        }
        $v1 = intval(trim($div_arr[0]));
        $v2 = intval(trim($div_arr[1]));
        if ($v2 < $v1) {
            $v = $v1;
            $v1 = $v2;
            $v2 = $v;
        }

        $len = mb_strlen($value);
        if ($len < $v1 || $len > $v2) {
            $this->setMsg_three('length_between', $parameter_name, $v1, $v2);
            return false;
        }
        return true;
    }

    private function check_in($parameter_name, $value, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        if (! in_array($value, $div_arr)) {
            $this->setMsg_two('in', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_not_in($parameter_name, $value, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        if (in_array($value, $div_arr)) {
            $this->setMsg_two('not_in', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_filled($parameter_name, $value)
    {
        if (empty($value)) {
            $this->setMsg_one('filled', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_distinct($parameter_name, $value)
    {
        if (! is_array($value)) {
            throw new \Exception("Illegal PHPValidator expression: Under the 'distinct' rule, the value must be a array");
        }
        if (count($value) != array_unique($value)) {
            $this->setMsg_one('distinct', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_different($parameter_name, $rule_data)
    {
        $v1_exists = array_key_exists($rule_data, $this->data) ? $this->data[$rule_data] : null;
        $v2_exists = array_key_exists($parameter_name, $this->data) ? $this->data[$parameter_name] : null;
        if ($v1_exists === $v2_exists) {
            $this->setMsg_two('different', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_same($parameter_name, $rule_data)
    {
        $v1_exists = array_key_exists($rule_data, $this->data) ? $this->data[$rule_data] : null;
        $v2_exists = array_key_exists($parameter_name, $this->data) ? $this->data[$parameter_name] : null;
        if ($v1_exists !== $v2_exists) {
            $this->setMsg_two('same', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_required_with($parameter_name, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        $we_need_you = false;
        $who_not_null = "";
        foreach ($div_arr as $e_key) {
            if ($this->_check_not_null($e_key)) {
                $we_need_you = true;
                $who_not_null = $e_key;
                break;
            }
        }

        if ($we_need_you && ! $this->_check_not_null($parameter_name)) {
            $this->setMsg_two('required_with', $parameter_name, $who_not_null);
            return false;
        }
        return true;
    }

    private function check_required_with_all($parameter_name, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        $we_need_you = true;
        foreach ($div_arr as $e_key) {
            if (! $this->_check_not_null($e_key)) {
                $we_need_you = false;
                break;
            }
        }
        if ($we_need_you && ! $this->_check_not_null($parameter_name)) {
            $this->setMsg_two('required_with_all', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_required_without($parameter_name, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        $we_need_you = false;
        $who_not_null = "";
        foreach ($div_arr as $e_key) {
            if (! $this->_check_not_null($e_key)) {
                $we_need_you = true;
                $who_not_null = $e_key;
                break;
            }
        }
        if ($we_need_you && ! $this->_check_not_null($parameter_name)) {
            $this->setMsg_two('required_without', $parameter_name, $who_not_null);
            return false;
        }
        return true;
    }

    private function check_required_without_all($parameter_name, $rule_data)
    {
        $div_arr = explode(',', $rule_data);
        $we_need_you = true;
        foreach ($div_arr as $e_key) {
            if ($this->_check_not_null($e_key)) {
                $we_need_you = false;
                break;
            }
        }
        if ($we_need_you && ! $this->_check_not_null($parameter_name)) {
            $this->setMsg_two('required_without_all', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function add_alias($parameter_name, $rule_data)
    {
        $this->alias[$parameter_name] = $rule_data;
        return true;
    }

    private function check_to_type($parameter_name, $rule_data)
    {
        if (! in_array($rule_data, $this->to_type_keys)) {
            $has_err = true;
            $div_arr = explode(':', $rule_data);
            if (count($div_arr) != 2) {
                goto goto_to_type_error;
            }
            $type = $div_arr[0];
            $type_value = $div_arr[1];
            switch ($type) {
                case 'str_array' :
                    if (! in_array($type_value, $this->to_type_keys)) {
                        goto goto_to_type_error;
                    }
                    break;
                case 'scale' :
                    $rule_data = $type . ":" . intval($type_value);
                    break;
                default :
                    goto goto_to_type_error;
            }
            $has_err = false;
            goto_to_type_error:
            if ($has_err) {
                throw new \Exception("Illegal PHPValidator expression: The 'to_type' rule of parameter '{$parameter_name}' , '{$rule_data}' is not a valid type expression");
            }
            $this->to_type[$parameter_name] = $rule_data;
        }
        $this->to_type[$parameter_name] = $rule_data;
        return true;
    }

    private function check_file_exists($parameter_name)
    {
        $file = $this->getFile($parameter_name);
        if ($file == []) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }
        $path = $file['tmp_name'];
        if (! file_exists($path)) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }
        return true;
    }

    private function check_file_type_in($parameter_name, $rule_data)
    {
        $file = $this->getFile($parameter_name);
        if ($file == []) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }
        $type = explode('/', $file['type'])[1];
        $type = strtolower($type);
        $allow_type_arr = explode(',', $rule_data);
        $match = false;
        foreach ($allow_type_arr as $allow_type) {
            if ($type == strtolower($allow_type)) {
                $match = true;
                break;
            }
        }
        if (! $match) {
            $this->setMsg_two('file_type_in', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_file_max($parameter_name, $rule_data)
    {
        $file = $this->getFile($parameter_name);
        if ($file == []) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }
        $size = bcdiv($file['size'], 1048576, $this->scale);
        if ($size > floatval($rule_data)) {
            $this->setMsg_two('file_max', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_file_min($parameter_name, $rule_data)
    {
        $file = $this->getFile($parameter_name);
        if ($file == []) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }
        $size = bcdiv($file['size'], 1048576, $this->scale);
        if ($size < floatval($rule_data)) {
            $this->setMsg_two('file_min', $parameter_name, $rule_data);
            return false;
        }
        return true;
    }

    private function check_file_size_between($parameter_name, $rule_data)
    {
        $file = $this->getFile($parameter_name);
        if ($file == []) {
            $this->setMsg_one('file_exists', $parameter_name);
            return false;
        }

        $div_arr = explode(',', $rule_data);
        if (count($div_arr) != 2) {
            throw new \Exception("Illegal PHPValidator expression: The 'file_size_between' rule's value must like ' min , max '");
        }
        $v1 = floatval(trim($div_arr[0]));
        $v2 = floatval(trim($div_arr[1]));
        if ($v2 < $v1) {
            $v = $v1;
            $v1 = $v2;
            $v2 = $v;
        }

        $size = bcdiv($file['size'], 1048576, $this->scale);
        if ($size < $v1 || $size > $v2) {
            $this->setMsg_three('file_size_between', $parameter_name, $v1, $v2);
            return false;
        }
        return true;
    }

    private function toType_str_array($parameter_name, $value, $type)
    {
        if (! isset($this->array_str[$parameter_name])) {
            $rule_result = $this->check_array_str($parameter_name, $value);
            if (! $rule_result) {
                return false;
            }
        }

        $this->data[$parameter_name] = explode(',', $this->array_str[$parameter_name]);
        foreach ($this->data[$parameter_name] as $key => $v) {
            switch ($type) {
                case 'string' : $this->data[$parameter_name][$key] = strval($v);break;
                case 'boolean' : $this->data[$parameter_name][$key] = boolval($v); break;
                case 'integer' : $this->data[$parameter_name][$key] = intval($v); break;
                case 'float' : $this->data[$parameter_name][$key] = floatval($v); break;
                case 'array' : $this->data[$parameter_name][$key] = (array) $v; break;
                case 'object' : $this->data[$parameter_name][$key] = (object) $v; break;
                default :
                    break;
            }
        }
        return true;
    }

    private function toType_scale($parameter_name, $value, $scale)
    {
        if (! isset($this->float[$parameter_name])) {
            if (! $this->check_float_str($parameter_name, $value)) {
                return false;
            }
        }

        $this->data[$parameter_name] = round($this->float[$parameter_name], intval($scale));
        return true;
    }
}
