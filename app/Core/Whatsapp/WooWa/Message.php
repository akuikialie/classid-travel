<?php

namespace App\Core\Whatsapp\WooWa;

class Message extends WooWa
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Synchronous Message Sender
     *
     * @param string $phone
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function send(string $phone, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'message' => $text,
            ];

            return $this->doSend('send_message', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Asynchronous Message Sender
     *
     * @param string $phone
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function sendAsync(string $phone, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'message' => $text,
            ];

            return $this->doSend('async_send_message', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
