<?php

namespace App\Notifications;

use App\Models\AssetTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTransactionSuccessNotification extends Notification
{
    use Queueable;

    public $transaction;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AssetTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Notifikasi Pemberitahuan Transaksi Berhasil')
                    ->action('Detail Transaksi', route('user.wallet.asset.detail', ['asset' => $this->transaction->asset_wallet->asset->id, 'wallet' => $this->transaction->asset_wallet->wallet->id]))
                    ->line('Terima kasih sudah menggunakan aplikasi Catat Crypto!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
