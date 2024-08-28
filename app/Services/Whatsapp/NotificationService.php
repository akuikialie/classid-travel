<?php

namespace App\Services\Whatsapp;

use App\Exceptions\HandleCatchableException;
use App\Http\Notifications\WaTestNotif;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;

class NotificationService
{
    private string|null $message = null;

    public function __construct(
    )
    {}

    private ?User $receiver = null;
    private Model|Collection|array $subject;

    /**
     * @param Model|Collection|array $subject
     * @return $this
     */
    public function setSubject(Model|Collection|array $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(
        string $message = 'Selamat *:receiver.name*, Anda telah terdaftar '
    ): static
    {
        $this->message = $this->replacePlaceholders($message);
        return $this;
    }

    /**
     * @param User $receiver
     * @return $this
     */
    public function setReceiver(
        User $receiver
    ): static
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function sendMessage(?callable $callback = null): void
    {
        if ($callback) {
            $callback(new NotificationService());
        } else {
            $this->notify();
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function notify(): void
    {
        if (!$this->receiver instanceof User){
            throw HandleCatchableException::catchable('Penerima tidak boleh kosong!');
        }
        dispatch_sync(new WaTestNotif($this->receiver, $this->message));
    }

    protected function replacePlaceholders(string $description): string
    {
        return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) {

            $match = $match[0];

            $attribute = Str::before(Str::after($match, ':'), '.');

            if (!in_array($attribute, ['receiver', 'subject', 'properties'])) {
                return $match;
            }

            $propertyName = substr($match, strpos($match, '.') + 1);

            return data_get($this->$attribute, $propertyName, $match);
        }, $description);
    }
}
