<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Participant;

class UpdateParticipantIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:update-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing participants with new participant ID format (EventID + 8-digit serial)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting participant ID update...');

        // Get all participants that don't have a participant_id yet
        $participants = Participant::whereNull('participant_id')->get();

        if ($participants->isEmpty()) {
            $this->info('No participants need ID updates.');
            return 0;
        }

        $this->info("Found {$participants->count()} participants to update.");

        // Group participants by event_id for proper serial numbering
        $participantsByEvent = $participants->groupBy('event_id');

        $progressBar = $this->output->createProgressBar($participants->count());
        $progressBar->start();

        foreach ($participantsByEvent as $eventId => $eventParticipants) {
            $serial = 1;

            foreach ($eventParticipants as $participant) {
                // Generate participant ID: EventID + 8-digit serial
                $serialNumber = str_pad($serial, 8, '0', STR_PAD_LEFT);
                $participantId = $eventId . $serialNumber;

                // Check if this ID already exists
                while (Participant::where('participant_id', $participantId)->exists()) {
                    $serial++;
                    $serialNumber = str_pad($serial, 8, '0', STR_PAD_LEFT);
                    $participantId = $eventId . $serialNumber;
                }

                // Update the participant
                $participant->participant_id = $participantId;
                $participant->save();

                $serial++;
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine();
        $this->info('Participant ID update completed successfully!');

        return 0;
    }
}
