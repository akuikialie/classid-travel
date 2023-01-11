<?php

namespace App\Core\Whatsapp\WooWa;

class GroupMessage extends WooWa
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Synchronous Message Sender
     *
     * @param string $groupId
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function send(string $groupId, string $text)
    {
        try {
            $data = [
                'group_id' => $groupId,
                'message' => $text,
            ];

            return $this->doSend('send_message_group_id', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Asynchronous Message Sender
     *
     * @param string $groupId
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function sendAsync(string $groupId, string $text)
    {
        try {
            $data = [
                'group_id' => $groupId,
                'message' => $text,
            ];

            return $this->doSend('async_send_message_group_id', $data);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
