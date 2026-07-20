<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventFee;
use App\Models\Participant;
use App\Models\EventResult;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Open Event: Running/Marathon
        $event1 = Event::create([
            'name' => 'Dhaka Half Marathon 2026',
            'slug' => 'dhaka-half-marathon-2026',
            'description' => 'The ultimate urban half marathon in Dhaka. Join us for a fast, flat, and scenic route through the heart of the capital.',
            'start_time' => Carbon::now()->addMonths(3)->setHour(6)->setMinute(0)->setSecond(0),
            'end_time' => Carbon::now()->addMonths(3)->setHour(10)->setMinute(0)->setSecond(0),
            'capacity' => '2000',
            'venue' => 'Hatirjheel, Dhaka',
            'cover_photo' => null,
            'status' => 'open',
            'additional_fields' => [
                [
                    'label' => 'Club Name',
                    'type' => 'text',
                    'required' => false
                ]
            ]
        ]);

        EventCategory::create(['event_id' => $event1->id, 'name' => '21K Half Marathon']);
        EventCategory::create(['event_id' => $event1->id, 'name' => '7.5K Run']);

        EventFee::create(['event_id' => $event1->id, 'fee_type' => 'Early Bird 21K', 'fee_amount' => 1000.00]);
        EventFee::create(['event_id' => $event1->id, 'fee_type' => 'Regular 21K', 'fee_amount' => 1500.00]);
        EventFee::create(['event_id' => $event1->id, 'fee_type' => 'Regular 7.5K', 'fee_amount' => 800.00]);


        // 2. Scheduled Event: Cycling
        $event2 = Event::create([
            'name' => 'Sylhet Cycling Championship 2026',
            'slug' => 'sylhet-cycling-championship-2026',
            'description' => 'Race through the beautiful tea gardens of Sylhet. This championship tests your endurance and speed over rolling hills.',
            'start_time' => Carbon::now()->addMonths(6)->setHour(7)->setMinute(0)->setSecond(0),
            'end_time' => Carbon::now()->addMonths(6)->setHour(13)->setMinute(0)->setSecond(0),
            'capacity' => '500',
            'venue' => 'Lakkatura Tea Garden, Sylhet',
            'cover_photo' => null,
            'status' => 'scheduled',
            'additional_fields' => [
                [
                    'label' => 'Bicycle Type',
                    'type' => 'text',
                    'required' => true
                ],
                [
                    'label' => 'Club Name',
                    'type' => 'text',
                    'required' => false
                ]
            ]
        ]);

        EventCategory::create(['event_id' => $event2->id, 'name' => 'Road Race (60K)']);
        EventCategory::create(['event_id' => $event2->id, 'name' => 'MTB Offroad (35K)']);

        EventFee::create(['event_id' => $event2->id, 'fee_type' => 'Registration Fee', 'fee_amount' => 1200.00]);


        // 3. Closed Event: Swimming
        $event3 = Event::create([
            'name' => "Cox's Bazar Swim Challenge 2026",
            'slug' => 'coxs-bazar-swim-challenge-2026',
            'description' => 'The ultimate open water swimming event in Bangladesh. Swim in the waters of the longest natural sandy beach in the world.',
            'start_time' => Carbon::now()->addMonths(1)->setHour(8)->setMinute(0)->setSecond(0),
            'end_time' => Carbon::now()->addMonths(1)->setHour(11)->setMinute(0)->setSecond(0),
            'capacity' => '300',
            'venue' => 'Laboni Beach, Cox\'s Bazar',
            'cover_photo' => null,
            'status' => 'closed',
            'additional_fields' => [
                [
                    'label' => 'Open Water Experience (Years)',
                    'type' => 'number',
                    'required' => true
                ]
            ]
        ]);

        EventCategory::create(['event_id' => $event3->id, 'name' => '1K Open Water']);
        EventCategory::create(['event_id' => $event3->id, 'name' => '3K Advanced']);

        EventFee::create(['event_id' => $event3->id, 'fee_type' => 'Entry Fee', 'fee_amount' => 1800.00]);


        // 4. Complete Event: Triathlon (With participants and results seeded)
        $event4 = Event::create([
            'name' => 'Dhaka Sprint Triathlon 2025',
            'slug' => 'dhaka-sprint-triathlon-2025',
            'description' => 'A test of multi-sport mastery combining swimming, cycling, and running. Excellent event for beginner and intermediate triathletes.',
            'start_time' => Carbon::now()->subMonths(2)->setHour(6)->setMinute(0)->setSecond(0),
            'end_time' => Carbon::now()->subMonths(2)->setHour(10)->setMinute(0)->setSecond(0),
            'capacity' => '200',
            'venue' => 'Uttara Sector 15 Lake & Roadways, Dhaka',
            'cover_photo' => null,
            'status' => 'complete',
            'additional_fields' => null
        ]);

        EventCategory::create(['event_id' => $event4->id, 'name' => 'Sprint Individual']);
        EventCategory::create(['event_id' => $event4->id, 'name' => 'Sprint Relay']);

        EventFee::create(['event_id' => $event4->id, 'fee_type' => 'Individual Entry', 'fee_amount' => 2500.00]);

        // Seed some participants for the complete event
        $names = [
            'Asif Rahman', 'Mariam Begum', 'Tanvir Islam', 'Nadia Sultana', 
            'Imran Chowdhury', 'Farhana Khan', 'Sajid Ahmed', 'Tasnim Alam'
        ];
        
        $participants = [];
        foreach ($names as $idx => $name) {
            $email = Str::slug($name) . '@example.com';
            $gender = ($idx % 2 == 0) ? 'male' : 'female';
            
            $participants[] = Participant::create([
                'event_id' => $event4->id,
                'category' => 'Sprint Individual',
                'reg_type' => 'regular',
                'fee' => '2500.00',
                'name' => $name,
                'email' => $email,
                'phone' => '+880170000000' . $idx,
                'address' => 'Mirpur, Dhaka',
                'district' => 'Dhaka',
                'thana' => 'Mirpur',
                'emergency_phone' => '+880199999999' . $idx,
                'gender' => $gender,
                'dob' => '1995-04-12',
                'nationality' => 'Bangladeshi',
                'tshirt_size' => 'M',
                'kit_option' => 'standard',
                'terms_agreed' => 'on',
                'payment_method' => 'bkash',
                'payment_status' => 'complete',
            ]);
        }

        // Seed results for these participants
        $times = ['01:05:22', '01:08:45', '01:12:10', '01:14:35', '01:18:12', '01:21:40', '01:25:05', '01:32:15'];
        $chipTimes = ['01:05:15', '01:08:30', '01:12:00', '01:14:22', '01:17:55', '01:21:28', '01:24:50', '01:31:58'];
        $speeds = [23.8, 22.7, 21.6, 20.9, 20.0, 19.1, 18.3, 16.9];
        
        foreach ($participants as $idx => $participant) {
            EventResult::create([
                'event_id' => $event4->id,
                'participant_id' => $participant->id,
                'position' => $idx + 1,
                'bib_number' => '10' . ($idx + 1),
                'sx' => null,
                'category' => 'Sprint Individual',
                'category_position' => $idx + 1,
                'laps' => 3,
                'finish_time' => $times[$idx],
                'gap' => ($idx == 0) ? '-' : '+' . (Carbon::parse($times[$idx])->diffInSeconds(Carbon::parse($times[0]))) . 's',
                'distance' => 25.75, // 750m swim, 20k bike, 5k run
                'chip_time' => $chipTimes[$idx],
                'speed' => $speeds[$idx],
                'best_lap' => '00:20:15',
                'dnf' => false,
                'dsq' => false,
                'notes' => ($idx == 0) ? 'Overall Winner' : null,
            ]);
        }


        // 5. Open Event: Obstacle Course
        $event5 = Event::create([
            'name' => 'Tough Mudder Bangladesh 2026',
            'slug' => 'tough-mudder-bangladesh-2026',
            'description' => 'Are you tough enough? Face deep mud, cold water, and high walls in this epic obstacle course race.',
            'start_time' => Carbon::now()->addMonths(2)->setHour(8)->setMinute(30)->setSecond(0),
            'end_time' => Carbon::now()->addMonths(2)->setHour(14)->setMinute(30)->setSecond(0),
            'capacity' => '1000',
            'venue' => 'Purbachal Sand Fields, Dhaka',
            'cover_photo' => null,
            'status' => 'open',
            'additional_fields' => [
                [
                    'label' => 'Team Name',
                    'type' => 'text',
                    'required' => false
                ]
            ]
        ]);

        EventCategory::create(['event_id' => $event5->id, 'name' => '10K Mud Run']);
        EventCategory::create(['event_id' => $event5->id, 'name' => '5K Fun Obstacle']);

        EventFee::create(['event_id' => $event5->id, 'fee_type' => 'Individual Pass', 'fee_amount' => 1500.00]);


        // 6. Scheduled Event: Walk/Fun Run
        $event6 = Event::create([
            'name' => "Children's Charity Fun Run 2026",
            'slug' => 'childrens-charity-fun-run-2026',
            'description' => 'A family-friendly fun run to raise funds for underprivileged children. Run, walk, or jog for a cause!',
            'start_time' => Carbon::now()->addMonths(5)->setHour(7)->setMinute(30)->setSecond(0),
            'end_time' => Carbon::now()->addMonths(5)->setHour(10)->setMinute(30)->setSecond(0),
            'capacity' => '3000',
            'venue' => 'Ramna Park, Dhaka',
            'cover_photo' => null,
            'status' => 'scheduled',
            'additional_fields' => null
        ]);

        EventCategory::create(['event_id' => $event6->id, 'name' => '5K Charity Walk/Run']);
        EventCategory::create(['event_id' => $event6->id, 'name' => '3K Kids Run']);

        EventFee::create(['event_id' => $event6->id, 'fee_type' => 'Charity Entry Fee', 'fee_amount' => 500.00]);
    }
}
