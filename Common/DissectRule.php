<?php

namespace FangStarNet\PHPValidator\Common;

class DissectRule
{
    private $keys = [];

    public function __construct()
    {
        $this->keys = new Keys();
    }

    public function handle($rule, &$dissect_data)
    {
        $sep_data = explode('|', $rule);

        $dissect_data = [];
        $key_data = [];
        for ($i = 0; $i < count($sep_data); $i++) {
            $key = $this->getKey($sep_data[$i]);
            if ($key == "") {
                $index = count($dissect_data) - 1;
                $dissect_data[$index] .= "|" . $sep_data[$i];
                continue;
            }
            $dissect_data[] = $sep_data[$i];
            $key_data[] = $key;
        }

        // handle complex regex expression
        $tmp_dissect_data = $dissect_data;
        $tmp_key_data = $key_data;
        $dissect_data = [];
        $key_data = [];
        $regex_start = false;
        $regex_index = 0;
        for ($i = 0; $i < count($tmp_dissect_data); $i++) {
            $key = $tmp_key_data[$i];
            $value = $tmp_dissect_data[$i];

            if ($key == "regex" && $regex_start == false) {
                $regex_start = true;
                $dissect_data[$regex_index] = $value;
                $key_data[] = $key;
                if ($this->isRegexEnd($value)) {
                    $regex_start = false;
                    $regex_index ++;
                }
                continue;
            }

            if ($regex_start) {
                $dissect_data[$regex_index] .= "|" . $value;
                if ($this->isRegexEnd($value)) {
                    $regex_start = false;
                    $regex_index ++;
                }
                continue;
            }

            $dissect_data[$regex_index] = $value;
            $key_data[] = $key;
            $regex_index ++;
        }

        $this->constituteDissectData($dissect_data, $key_data);
    }

    private function getKey($sp_value)
    {
        $preg = '/^([a-z_]*)/i';
        preg_match($preg, $sp_value, $match);
        $key = $match[0];

        if (! in_array($key, $this->keys->all_keys)) {
            return "";
        }

        return $key;
    }

    private function isRegexEnd($sp_value)
    {
        $pos = 0;
        $pos = strpos($sp_value, '/', $pos);
        if ($pos === false) {
            return false;
        }
        if ($pos === 0) {
            return true;
        }

        while (true) {
            $pos = strpos($sp_value, '/', $pos + 1);
            if ($pos === false) {
                return false;
            }
            if ($sp_value[$pos - 1] !== '\\') {
                return true;
            }
        }
        return true;
    }

    private function constituteDissectData(array &$dissect_data, array &$key_data)
    {
        $tmp = [];

        foreach ($dissect_data as $k => $value) {
            $key = $key_data[$k];

            $len = strlen($key);
            if (in_array($key, $this->keys->kv_keys)) {
                $len ++;
                $new_value = substr($value, $len);
                if (empty($new_value)) {
                    throw new \Exception("Illegal PHPValidator expression: The '{$key}' rule's value must not is empty");
                }
                $tmp[$key] = $new_value;
                continue;
            }

            $new_value = substr($value, $len);
            if (! empty($new_value)) {
                throw new \Exception("Illegal PHPValidator expression: The '{$key}' rule's value must is empty");
            }
            $tmp[$key] = $new_value;
        }

        $dissect_data = $tmp;
    }
}
