<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\DeviceToken;
use App\Services\FirebaseNotificationService;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseNotificationService $firebase)
    {
        $notifications = Notification::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($notifications as $notification) {

            $tokens = collect();

            switch ($notification->target) {

                case 'all':
                    $tokens = DeviceToken::where('is_active', true)
                        ->pluck('fcm_token');
                    break;

                case 'customers':
                    $tokens = DeviceToken::whereHas('user', function ($q) {
                        $q->role('Customer');
                    })
                        ->where('is_active', true)
                        ->pluck('fcm_token');
                    break;

                case 'active':
                    $tokens = DeviceToken::where('is_active', true)
                        ->pluck('fcm_token');
                    break;

                case 'inactive':
                    $tokens = DeviceToken::where('is_active', false)
                        ->pluck('fcm_token');
                    break;
            }

            $firebase->sendToTokens(
                tokens: $tokens->toArray(),
                title: $notification->title,
                body: $notification->message,
                data: [
                    'notification_id' => (string) $notification->id,
                ],
                image: $notification->image_url,
            );

            $notification->update([
                'status' => 'sent',
            ]);
        }

        return self::SUCCESS;
    }
}
