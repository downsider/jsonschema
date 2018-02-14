<?php

namespace Lexide\JsonSchema\Exception;

/**
 * ValidationException.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class ValidationException extends \Exception
{

    protected $data = null;

    protected $value = null;

    protected $originalMessage = null;

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        if (empty($this->data)) {
            $this->data = $data;
            $this->setMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (empty($this->value)) {
            $this->value = $value;
            $this->setMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    protected function setMessage()
    {
        if (empty($this->originalMessage)) {
            $this->originalMessage = $this->message;
        }
        $message = $this->originalMessage;
        if (!empty($this->data)) {
            $data = $this->data;
            if (!is_string($this->data) || !is_numeric($this->data)) {
                $data = print_r($data, true);
            }
            $message .= ", data = $data ";
        }
        if (!empty($this->value)) {
            $value = $this->value;
            if (!is_string($this->value) || !is_numeric($this->value)) {
                $value = print_r($value, true);
            }
            $message .= ", value = $value ";
        }
        $this->message = $message;
    }

}
