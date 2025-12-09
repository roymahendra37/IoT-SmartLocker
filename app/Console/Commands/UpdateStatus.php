<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Models\Setting;

class UpdateStatus extends Command
{
    protected $signature = 'app:schedule';
    protected $description = 'Update otomatis status transaksi dan loker serta kirim notifikasi';

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');


        $lastPollSetting = Setting::where('key', 'esp_last_poll')->first();

        if ($lastPollSetting) {

            $lastPoll = Carbon::parse($lastPollSetting->value);
            $threshold = $lastPoll->copy()->addMinutes(5);

            if ($now->gte($threshold)) {

                $activeTitipTransactions = Transaction::where('status', 'active')
                    ->where('type', 'titip')
                    ->where('esp_notified', false)
                    ->get();

                foreach ($activeTitipTransactions as $t) {

                    if ($t->fcm_token) {

                        $this->sendFcmNotification($t->fcm_token, [
                            'title' => 'Smart Locker Offline!',
                            'body'  => 'Kemungkinan sedang terjadi mati listrik',
                        ]);

                        $t->update(['esp_notified' => true]);
                        $this->info("NOTIF ESP MATI -> transaksi {$t->id}");
                    }
                }
            }
            else {

                // reset hanya jika sebelumnya sudah diberi notifikasi mati listrik
                $reset = Transaction::where('status', 'active')
                    ->where('type', 'titip')
                    ->where('esp_notified', true)
                    ->update(['esp_notified' => false]);

                if ($reset > 0) {
                    $this->info("ESP HIDUP -> reset esp_notified pada $reset transaksi");
                }
            }
        }


        $transactions = Transaction::where('status', 'active')->get();

        foreach ($transactions as $transaction) {

            $locker = $transaction->locker;


            // Notifikasi 10 menit
            if (
                !$transaction->notified &&
                $transaction->type === 'titip' &&
                $now->gte($transaction->end_time->copy()->subMinutes(10))
            ) {
                if ($transaction->fcm_token) {

                    $this->sendFcmNotification($transaction->fcm_token, [
                        'title' => 'Waktu Titip Hampir Habis!',
                        'body'  => '10 menit lagi Loker Anda tidak dapat digunakan',
                    ]);

                    $transaction->update(['notified' => true]);
                    $this->info("NOTIF 10 menit -> transaksi {$transaction->id}");
                }
            }



            // Durasi habis
            if ($now->gt($transaction->end_time)) {

                if ($transaction->type === 'titip') {
                    $transaction->update(['status' => 'completed']);
                    $locker->update(['status' => 'available']);
                    $this->info("Titip selesai -> transaksi {$transaction->id}");

                } elseif ($transaction->type === 'kirim') {

                    if ($transaction->qr_usage < 2) {
                        $transaction->update(['status' => 'expired']);
                        $this->info("Kirim expired -> transaksi {$transaction->id}");
                    }
                }
            }
        }

        return Command::SUCCESS;
    }

    protected function sendFcmNotification(string $token, array $data)
    {
        $serviceAccountPath = storage_path('app/firebase/smart-locker-b48e8-firebase-adminsdk-fbsvc-2ab49f4bfb.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        $message = CloudMessage::fromArray([
            'token' => $token,
            'data' => [
                'title' => $data['title'],
                'body' => $data['body']
            ],
        ]);

        try {
            $messaging->send($message);
            $this->info("FCM dikirim ke token: $token");
        } catch (\Throwable $e) {
            $this->error("Gagal kirim FCM ke $token: " . $e->getMessage());
        }
    }

}