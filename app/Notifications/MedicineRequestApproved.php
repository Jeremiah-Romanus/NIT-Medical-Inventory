<?php

namespace App\Notifications;

use App\Models\MedicineRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicineRequestApproved extends Notification
{
    use Queueable;

    public function __construct(
        public MedicineRequest $medicineRequest
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Medicine Request Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your medicine request has been approved.')
            ->line('Medicine: ' . $this->medicineRequest->medicine->name)
            ->line('Requested Quantity: ' . $this->medicineRequest->requested_quantity . ' units')
            ->line('The approved quantity has been added to your pharmacy stock.')
            ->action('View Received Medicines', route('pharmacist.received'))
            ->line('You can review the updated inventory from the pharmacist dashboard.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'medicine_request_id' => $this->medicineRequest->id,
            'medicine_name' => $this->medicineRequest->medicine->name,
            'requested_quantity' => $this->medicineRequest->requested_quantity,
            'approval_note' => $this->medicineRequest->approval_note,
            'approved_by' => $this->medicineRequest->approver?->name ?? 'Procurement Officer',
            'approved_at' => $this->medicineRequest->approved_at,
            'message' => 'Your request for ' . $this->medicineRequest->medicine->name . ' has been approved.',
        ];
    }
}
