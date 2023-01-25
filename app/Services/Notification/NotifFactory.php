<?php

namespace App\Services\Notification;

use Illuminate\Support\Str;

class NotifFactory
{
    private string $id = '';
    private string $channel = '';
    private string $sender = '';
    private string $recipient = '';
    private string $content = '';
    private array $attachments = [];

    protected NotifCollector $collector;

    public function __construct(string $channel, array $param = [])
    {
        $this->id = Str::uuid()->toString();
        $this->channel = $channel;
        collect($param)
            ->only(['sender', 'recipient', 'content', 'attachments'])
            ->each(fn ($value, $key) => $this->{$key} = $value);

        $this->setCollector();
    }

    public function sender(string $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function recipient(string $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function attachments(array $attachments): self
    {
        $this->attachments = $attachments;
        return $this;
    }

    protected function getCollector()
    {

    }
}
