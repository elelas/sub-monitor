<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subscription::factory()->count(10)->create();
        Subscription::factory()->count(10)->onePayment()->create();
        Subscription::factory()->count(10)->withExistedService()->create();
    }
}
