<?php

namespace App\Core\Whatsapp\Messages;

use Illuminate\Support\Collection;

class WhatsappMessage
{
    public ?Collection $collection;

    public string $key;

    public bool $isGroup = false;

    public string $groupId;

    public array $to;

    public string $content = '';

    /**
     * @param string|null $key
     * @throws \Exception
     */
    public function __construct(?string $key = null)
    {
        $this->getKeyBySchool($key);
        $this->collection = collect();
    }

    /**
     * @param string $key
     * @return $this
     * @throws \Exception
     */
    public function key(string $key): WhatsappMessage
    {
        $this->key = trimAll($key, 'all');
        return $this;
    }

    /**
     * @param string|array $to
     * @return $this
     * @throws \Exception
     */
    public function to($to): WhatsappMessage
    {
        $this->to = explode(',', trimAll($to, 'all', '[^a-zA-Z\d:,]+'));
        return $this;
    }

    /**
     * @param string $groupId
     * @return $this
     */
    public function groupId(string $groupId): WhatsappMessage
    {
        $this->isGroup = true;
        $this->groupId = $groupId;
        $this->to = [$groupId];
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function content(string $content): WhatsappMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param array|Collection $message
     * @return $this
     * @throws \Exception
     */
    public function collection($message = []): WhatsappMessage
    {
        $this->collection = $message instanceof Collection ? $message : collect((array) $message);
        if ($this->collection->isNotEmpty() && $this->collection->first(fn ($m) => $m instanceof WhatsappMessage == false)) {
            $this->collection = collect();
            throw new \Exception("collection entry must instance of WhatsappMessage");
        }
        return $this;
    }

    /**
     * @param WhatsappMessage $message
     * @return $this
     */
    public function addCollection(WhatsappMessage $message): WhatsappMessage
    {
        $this->collection->push($message);
        return $this;
    }

    /**
     * @return string
     */
    public function toHTML(): string
    {
        return preg_replace([
            '/\*(.*)\*/gi', '/\n/gi', '/_(.*)_/gi', '/~(.*)~/gi', '/```(.*)```/gi'
        ], [
            '<b>$1</b>', '<br>', '<i>$1</i>', '<strike>$1</strike>', '<font face="monospace">$1</font>'
        ], $this->content);
    }

    /**
     * @return string
     */
    public function toWhatsapp(): string
    {
        return preg_replace([
            '/<b>|<\/b>/gi', '/<p>|<div>|<br>/gi', '/<\/p>|<\/div>/gi', '/<i>|<\/i>/gi', '/<strike>|<\/strike>/gi', '/<font face="monospace" style="">|<font face="monospace">|<\/font>/gi'
        ], [
            '*', '\n', '', '_', '~', '```'
        ], $this->content);
    }

    /**
     * @param string|null $key
     * @return void
     * @throws \Exception
     */
    private function getKeyBySchool(?string $key = null): void
    {
        $key = trimAll($key, 'all');
        if (!empty($key)) {
            $this->key = $key;
        }
    }
}
