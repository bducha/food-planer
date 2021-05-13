<?php


namespace App\Http;


class ApiResponse
{
    /**
     * @var string
     */
    private string $message;
    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * ApiResponse constructor.
     * @param string $message
     * @param array|mixed $data
     */
    public function __construct(string $message = "", mixed $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }
}