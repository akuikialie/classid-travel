<?php

namespace App\Core\Whatsapp\WooWa;

class Image extends WooWa
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Synchronous Message Sender
     *
     * @param string $phone
     * @param string $image
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function send(string $phone, string $image, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'url' => $image,
                'message' => $text,
            ];

            $response = $this->doSend('send_image_url', $data);
            switch ($response) {
                case 1:
                case "1":
                    return "success";
                    break;
                case 0:
                case "0":
                    return "failed";
                    break;
                default:
                    return $response;
            }
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Asynchronous Message Sender
     *
     * @param string $phone
     * @param string $image
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function sendAsync(string $phone, string $image, string $text)
    {
        try {
            $data = [
                'phone_no' => $phone,
                'url' => $image,
                'message' => $text,
            ];

            return $this->doSend('async_send_image_url', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
