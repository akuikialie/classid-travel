<?php

namespace App\Listeners\Notification;

use App\Domains\Presence\Enums\PresenceNotificationKey;
use App\Domains\Presence\Event\StudentPresence;
use App\Domains\Presence\Models\Presence;
use App\Enums\Notification\NotificationChannel;
use App\Enums\Notification\NotificationMetaType;
use App\Enums\Notification\NotificationType;
use App\Enums\OptionReferenceType;
use App\Models\Guardian\Guardian;
use App\Models\Institution;
use App\Models\Student;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Repositories\OptionReferenceRepository;
use Classid\TemplateReplacement\TemplateReplacement;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;

class  SendStudentPresenceNotification implements ShouldQueue
{
    public Institution $institution;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @throws BindingResolutionException
     */
    public function handle(StudentPresence $event): void
    {
        if (!$event->withNotification) {
            return;
        }

        $user = $event->user;
        $userable = $event->user->userable;
        $presence = $event->presence;

        $this->institution = $userable->getInstitution();

        // Notify to User
        $this->sendNotification(
            user: $user,
            presence: $presence
        );

        // Notify to Guardian
        if ($user->userable instanceof Student) {
            /** @var Guardian $studentGuardian */
            $studentGuardian = $user->userable->guardians()->where([
                'is_guardian' => true
            ])->first();

            $userGuardian = $studentGuardian->user;
            $this->sendNotification(
                user: $userGuardian,
                presence: $presence,
                isGuardian: true
            );
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function messageReplacement(User $user, Presence $presence, bool $isGuardian = false): string
    {
        if ($isGuardian) {
            $key = PresenceNotificationKey::GUARDIAN_PRESENCE_NOTIFICATION_MESSAGE->value;
            $defaultMessage = app()->make(OptionReferenceRepository::class)->getByTypeAndCode(
                type: OptionReferenceType::ACADEMIC_NOTIFICATION,
                code: 'guardian_presence_notification_message'
            )->content;
        }else{
            $key = PresenceNotificationKey::PRESENCE_NOTIFICATION_MESSAGE->value;
            $defaultMessage = app()->make(OptionReferenceRepository::class)->getByTypeAndCode(
                type: OptionReferenceType::ACADEMIC_NOTIFICATION,
                code: 'presence_notification_message'
            )->content;
        }

        $message = institutionData(
            key: $key,
            default: $defaultMessage,
            institution: $this->institution
        );

        return TemplateReplacement::execute($message,
            priorityReplacementData: [
                'user_full_name' => $user->name,
                'presence_type' => $presence->type->content,
            ],
        );
    }

    /**
     * @throws BindingResolutionException
     */
    private function sendNotification(User $user, Presence $presence, bool $isGuardian = false): void
    {
        $title = 'Kehadiran!';
        $message = $this->messageReplacement(
            user: $user,
            presence: $presence,
            isGuardian: $isGuardian
        );

        $isNotification = filter_var(institutionData(
            key: PresenceNotificationKey::PRESENCE_IS_ENABLE_NOTIFICATION->value,
            default: 'false',
            institution: $this->institution
        ), FILTER_VALIDATE_BOOL);

        if ($isNotification) {
            $channelNames = json_decode(
                institutionData(
                    key: PresenceNotificationKey::PRESENCE_IS_ALLOWED_NOTIFICATION->value,
                    default: json_encode([]),
                    institution: $this->institution
                )
            );

            $user->notify(
                new SendNotification(
                    institution: $this->institution,
                    type: NotificationType::ACADEMIC_NOTIFICATION->value,
                    title: $title,
                    message: $message,
                    data: $presence,
                    metadata: [
                        'id' => $presence->id,
                        'type' => NotificationMetaType::STUDENT_PRESENCE->value
                    ],
                    channels: array_map(fn($name) => NotificationChannel::from($name), $channelNames),
                ),
            );
        }
    }
}
