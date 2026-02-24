<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminActionNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        // Data berisi ['status' => '...', 'message' => '...']
        $this->data = $data;
    }

    public function via($notifiable)
    {
        // Menyimpan notifikasi ke dalam database
        return ['database'];
    }

    public function toArray($notifiable)
{
    return [
        'status' => $this->data['status'],
        'message' => $this->data['message'],
        'paket_id' => $this->data['paket_id'], // Tambahkan ini
    ];
}
}