<?php

namespace App\Core\Whatsapp\WooWa;

class File extends WooWa
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Synchronous Message Sender
     *
     * @param string $phone
     * @param string $file
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function send(string $phone, string $file, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'url' => $file,
                'message' => $text,
            ];

            return $this->doSend('send_file_url', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Asynchronous Message Sender
     *
     * @param string $phone
     * @param string $file
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function sendAsync(string $phone, string $file, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'url' => $file,
                'message' => $text,
            ];

            return $this->doSend('async_send_file_url', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
