<?php

namespace App\Responses;

class ApiResponse implements \JsonSerializable
{
    /**
     * @var
     */
    private $status;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $errors;
    /**
     * @var
     */
    private $messages;

    /**
     *
     */
    public function __construct($data = '', $status = 200, $errors = [], $messages = []){
        $this->setData($data);
        $this->setStatus($status);
        $this->setErrors($errors);
        $this->setMessages($messages);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return ApiResponse
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return ApiResponse
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     * @return ApiResponse
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     * @return ApiResponse
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function jsonSerialize()
    {
        $data = [];
        $data['data'] = $this->getData();
        $data['status'] = $this->getStatus();
        $data['errors'] = $this->getErrors();
        $data['messages'] = $this->getMessages();
        return $data;
    }
}
