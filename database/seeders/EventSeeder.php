<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Event 1',
                'location' => 'Location 1',
                'description' => 'Description for Event 1',
                'image' => 'event1.jpg',
                'price' => 120000,
                'discount' => 20000,
                'stock' => 10,
                'time' => now()->addDays(30),
            ],
            [
                'name' => 'Event 2',
                'location' => 'Location 2',
                'description' => 'Description for Event 2',
                'image' => 'event2.jpg',
                'price' => 99000,
                'discount' => 0,
                'stock' => 0,
                'time' => now()->addDays(15),
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
