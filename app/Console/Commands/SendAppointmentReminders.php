<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders for appointments scheduled tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting reminder check...');

        $now = Carbon::now();
        $in24Hours = Carbon::now()->addHours(24);
        $count = 0;

        // Use REPLACE to handle potential 'T' characters in string columns
        // This ensures compatibility with both standard datetime and 'YYYY-MM-DDTHH:MM' formats.
        
        // 1. Find regular appointments scheduled within the next 24 hours
        $regularAppointments = Appointment::where('status', 'approved')
            ->where('reminder_sent', false)
            ->whereRaw("REPLACE(appointment_date, 'T', ' ') > ?", [$now->toDateTimeString()])
            ->whereRaw("REPLACE(appointment_date, 'T', ' ') <= ?", [$in24Hours->toDateTimeString()])
            ->get();

        // 2. Find follow-up appointments scheduled within the next 24 hours
        $followUpAppointments = Appointment::where('status', 'completed')
            ->whereNotNull('next_appointment')
            ->where('reminder_sent', false)
            ->whereRaw("REPLACE(next_appointment, 'T', ' ') > ?", [$now->toDateTimeString()])
            ->whereRaw("REPLACE(next_appointment, 'T', ' ') <= ?", [$in24Hours->toDateTimeString()])
            ->get();

        $allAppointments = $regularAppointments->concat($followUpAppointments);

        if ($allAppointments->isEmpty()) {
            $this->info('No appointments found for reminders in the next 24 hours.');
            return;
        }

        $this->info('Found ' . $allAppointments->count() . ' appointment(s) for reminders.');

        foreach ($allAppointments as $appointment) {
            try {
                $petOwner = $appointment->owner;
                
                if (!$petOwner || !$petOwner->phone) {
                    $this->warn("Skipping Appointment {$appointment->id}: No owner or phone number.");
                    continue;
                }

                $phone = $petOwner->phone;
                
                // Normalize PH phone number
                $phone = preg_replace('/[^0-9]/', '', $phone);
                if (str_starts_with($phone, '0')) {
                    $phone = '63' . substr($phone, 1);
                } elseif (str_starts_with($phone, '9')) {
                    $phone = '63' . $phone;
                }

                $ownerName = $petOwner->full_name;
                
                // Get pet names
                $petNames = $appointment->pets->pluck('name')->join(', ');
                if (empty($petNames)) {
                    if ($appointment->pet_id && $pet = \App\Models\Pet::find($appointment->pet_id)) {
                        $petNames = $pet->name;
                    } else {
                        $petNames = 'your pet';
                    }
                }

                // Determine which date/time to use for the message
                $apptDateRaw = $appointment->status === 'completed' ? $appointment->next_appointment : $appointment->appointment_date;
                $apptDate = Carbon::parse($apptDateRaw);
                $formattedDate = $apptDate->format('M d, Y');
                $formattedTime = $apptDate->format('g:i A');
                $clinicName = $appointment->clinic ? $appointment->clinic->clinic_name : 'the veterinary clinic';

                $message = "Hello {$ownerName}! This is a reminder from {$clinicName}. "
                         . "{$petNames} has an upcoming appointment on {$formattedDate} at {$formattedTime}. "
                         . "See you there!";

                $this->info("Sending SMS to {$phone} for Appointment {$appointment->id}...");

                // Send SMS via iProgSMS
                $apiToken = '72ae7e3a8c3ed0e97111f3b5a81887f4ae96a344';
                
                $response = Http::withoutVerifying()
                    ->timeout(60)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post('https://www.iprogsms.com/api/v1/sms_messages', [
                        'api_token'    => $apiToken,
                        'phone_number' => $phone,
                        'message'      => $message,
                    ]);

                $responseBody = $response->json();
                $apiStatus = 200;
                if (is_array($responseBody) && isset($responseBody['status'])) {
                    $apiStatus = $responseBody['status'];
                }

                if ($response->successful() && $apiStatus == 200) {
                    $appointment->reminder_sent = true;
                    $appointment->save();
                    $this->info("Reminder successfully sent to {$phone}.");
                    $count++;
                } else {
                    $errorMsg = $response->body();
                    $this->error("Failed to send to {$phone}: " . $errorMsg);
                    Log::error("Reminder SMS Failed for Appointment {$appointment->id}: " . $errorMsg);
                }

            } catch (\Exception $e) {
                $this->error("Error processing Appointment {$appointment->id}: " . $e->getMessage());
                Log::error("Reminder Error Appointment {$appointment->id}: " . $e->getMessage());
            }
        }

        $this->info("Done. Sent {$count} reminders.");
    }
}
