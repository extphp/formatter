<?php

namespace ExtPHP\Formatter;

class Formatter
{
    protected $data;

    protected $rules;

    public function __construct($data, $rules)
    {
        $this->setData($data);
        $this->setRules($rules);    
    }

    public function format()
    {
        $this->applyRules();
        return $this->data;
    }

    protected function setData($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data must be array');
        }
        $this->data = $data;
    }

    protected function setRules($rules)
    {
        if (!is_array($rules)) {
            throw new \InvalidArgumentException('Rules must be array');
        }
        $this->rules = $rules;
    }

    protected function applyRules()
    {
        foreach ($this->rules as $key => $rules) {
            $this->handleKey($key, $rules);
        }
    }

    protected function handleKey($key, $rules)
    {
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = null;
        }
        $rules = explode('|', $rules);
        foreach ($rules as $rule) {
            $this->data[$key] = $this->applyKeyRule($key, $rule);
        }
    }

    protected function applyKeyRule($key, $rule)
    {
        list($method, $args) = $this->identifyRule($rule);
        array_unshift($args, $this->data[$key]);
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }

        if (function_exists($method)) {
            return call_user_func_array($method, $args);
        }

        return $this->data[$key];
    }

    protected function identifyRule($rule)
    {
        $args = [];
        $parts = explode(':', $rule);
        $method = $parts[0];
        if (count($parts) > 1) {
            $args = $this->identifyArguments($parts[1]);
        }
        return [$method, $args];
    }

    protected function identifyArguments($string)
    {
        $replaced = str_replace('\,', '--comma--', $string);
        $parts = explode(',', $replaced);
        return array_map(function ($item) {
            return str_replace('--comma--', ',', $item);
        }, $parts);

    }

    /**
     * Allows the use of ebool (extended boolean) type which
     * converts the string "false" into the boolean false,
     * instead of the regulear boolean true.
     */
    protected function cast($value, $type = null)
    {
        if (!$type) {
            return $value;
        }
        if ($type == 'ebool') {
            if (strtolower(trim($value)) === 'false') {
                $value = false;
            }
            $type = 'bool';
        }
        settype($value, $type);
        return $value;
    }

    protected function str_replace($subject, $search = '', $replace = '')
    {
        return str_replace($search, $replace, $subject);
    }
}
