<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestFcm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-fcm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = 'dxo88GPohEA--BtjJZacLZ:APA91bFoVuD3nS1Pa9kgNukvT4MXkT82d8rLhTRxbX_CMTHg0lNkZAld6l3I6NE9T4hqHpbQpNO3lyuioOuwLvJwqK-8r0Gbhr20WGwWz4SEaS3-4fikSro';

        $serviceAccountPath = storage_path('app/firebase/smart-locker-b48e8-firebase-adminsdk-fbsvc-2ab49f4bfb.json');
        $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        $message = \Kreait\Firebase\Messaging\CloudMessage::fromArray([
            'token' => $token,
            'data' => [
                    'title' => 'Smart Locker Offline!',
                    'body'  => 'Kemungkinan sedang terjadi mati listrik',
            ],
        ]);

        try {
            $messaging->send($message);
            $this->info("Test FCM dikirim ke token: $token");
        } catch (\Throwable $e) {
            $this->error("Gagal kirim FCM: " . $e->getMessage());
        }
    }

}
