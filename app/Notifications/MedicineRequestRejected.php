<?php

namespace App\Notifications;

use App\Models\MedicineRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MedicineRequestRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public MedicineRequest $medicineRequest,
        public string $rejectionReason
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Medicine Request Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your medicine request has been rejected.')
            ->line('Medicine: ' . $this->medicineRequest->medicine->name)
            ->line('Requested Quantity: ' . $this->medicineRequest->requested_quantity . ' units')
            ->line('Rejection Reason: ' . $this->rejectionReason)
            ->action('View Request', route('pharmacist.request'))
            ->line('Please submit a new request if you need to retry.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'medicine_request_id' => $this->medicineRequest->id,
            'medicine_name' => $this->medicineRequest->medicine->name,
            'requested_quantity' => $this->medicineRequest->requested_quantity,
            'rejection_reason' => $this->rejectionReason,
            'rejected_by' => $this->medicineRequest->rejector?->name ?? 'Procurement Officer',
            'rejected_at' => $this->medicineRequest->rejected_at,
            'message' => 'Your request for ' . $this->medicineRequest->medicine->name . ' has been rejected.',
        ];
    }
}
