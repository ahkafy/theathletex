<?php

namespace App\Imports;

use App\Models\EventResult;
use App\Models\Participant;

class EventResultsImport
{
    private $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function import($filePath)
    {
        $csvData = array_map('str_getcsv', file($filePath));
        $header = array_shift($csvData); // Remove header row

        $results = [];
        foreach ($csvData as $row) {
            if (count($row) != count($header)) continue; // Skip malformed rows

            $rowData = array_combine($header, $row);
            $result = $this->processRow($rowData);

            if ($result) {
                $results[] = $result;
            }
        }

        return $results;
    }

    private function processRow(array $row)
    {
        // Skip empty rows
        if (empty($row['position']) && empty($row['participant_name']) && empty($row['participant_email'])) {
            return null;
        }

        // Find participant by name or email
        $participant = null;

        if (!empty($row['participant_name'])) {
            $participant = Participant::where('event_id', $this->eventId)
                ->where('name', 'LIKE', '%' . trim($row['participant_name']) . '%')
                ->first();
        }


        if (!$participant && !empty($row['participant_email'])) {
            $participant = Participant::where('event_id', $this->eventId)
                ->where('email', trim($row['participant_email']))
                ->first();
        }

        if (!$participant) {
            throw new \Exception("Participant not found: " . ($row['participant_name'] ?? $row['participant_email'] ?? 'Unknown'));
        }

        // Check if result already exists for this participant
        $existingResult = EventResult::where('event_id', $this->eventId)
            ->where('participant_id', $participant->id)
            ->first();

        if ($existingResult) {
            // Update existing result instead of creating new one
            $existingResult->update([
                'position' => $row['position'] ?? $existingResult->position,
                'bib_number' => $row['bib_number'] ?? $existingResult->bib_number,
                'sex' => $row['sex'] ?? $participant->gender ?? $existingResult->sex,
                'category' => $row['category'] ?? $existingResult->category,
                'laps' => $row['laps'] ?? $existingResult->laps,
                'finish_time' => $this->parseTime($row['finish_time'] ?? null) ?? $existingResult->finish_time,
                'gap' => $row['gap'] ?? $existingResult->gap,
                'distance' => $row['distance'] ?? $existingResult->distance,
                'chip_time' => $this->parseTime($row['chip_time'] ?? null) ?? $existingResult->chip_time,
                'speed' => $row['speed'] ?? $existingResult->speed,
                'best_lap' => $this->parseTime($row['best_lap'] ?? null) ?? $existingResult->best_lap,
                'dnf' => $this->parseBoolean($row['dnf'] ?? false),
                'dsq' => $this->parseBoolean($row['dsq'] ?? false),
                'notes' => $row['notes'] ?? $existingResult->notes,
            ]);
            return $existingResult;
        }

        // Calculate category position
        $categoryPosition = null;
        if (!empty($row['category']) && !empty($row['position'])) {
            $categoryPosition = EventResult::where('event_id', $this->eventId)
                ->where('category', $row['category'])
                ->where('position', '<=', $row['position'])
                ->count() + 1;
        }

        $result = EventResult::create([
            'event_id' => $this->eventId,
            'participant_id' => $participant->id,
            'position' => $row['position'] ?? null,
            'bib_number' => $row['bib_number'] ?? null,
            'sex' => $row['sex'] ?? $participant->gender,
            'category' => $row['category'] ?? null,
            'category_position' => $categoryPosition,
            'laps' => $row['laps'] ?? null,
            'finish_time' => $this->parseTime($row['finish_time'] ?? null),
            'gap' => $row['gap'] ?? null,
            'distance' => $row['distance'] ?? null,
            'chip_time' => $this->parseTime($row['chip_time'] ?? null),
            'speed' => $row['speed'] ?? null,
            'best_lap' => $this->parseTime($row['best_lap'] ?? null),
            'dnf' => $this->parseBoolean($row['dnf'] ?? false),
            'dsq' => $this->parseBoolean($row['dsq'] ?? false),
            'notes' => $row['notes'] ?? null,
        ]);

        return $result;
    }

    private function parseTime($timeString)
    {
        if (empty($timeString)) return null;

        // Handle various time formats
        $timeString = trim($timeString);

        // If it's already in H:i:s format
        if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeString)) {
            return $timeString;
        }

        // If it's in H:i format, add seconds
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
            return $timeString . ':00';
        }

        // If it's just minutes:seconds, add hour
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
            return '0:' . $timeString;
        }

        return null;
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        if (is_string($value)) {
            return in_array(strtolower($value), ['true', '1', 'yes', 'y']);
        }
        return (bool) $value;
    }
}
