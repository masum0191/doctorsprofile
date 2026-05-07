<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Appointment Confirmation - ' . $this->appointment->appointment_number)
                    ->view('emails.appointment-confirmation')
                    ->with([
                        'appointment' => $this->appointment,
                        'doctor' => $this->appointment->doctor,
                        'chamber' => $this->appointment->chamber,
                    ]);
    }
}
