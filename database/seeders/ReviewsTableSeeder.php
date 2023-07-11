<?php

namespace Database\Seeders;

use App\Models\Bookable;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bookable::all()->each(function (Bookable $bookable){
            $reviews = Review::factory(random_int(5, 30))->make();
            $bookable->reviews()->saveMany($reviews);
        });
    }
}
