<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Plan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'stripe_id' => 'price_1TM1mSJyq2eXngGjMUdQK7Ff',
                'price' => 1900,
                'description' => 'Perfect for individuals and small projects.'
            ]
        );

        \App\Models\Plan::updateOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Professional',
                'stripe_id' => 'price_1TM1nBJyq2eXngGjAxlvBpgE',
                'price' => 4900,
                'description' => 'Ideal for growing teams and businesses.'
            ]
        );

        \App\Models\Plan::updateOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'stripe_id' => 'price_1TM1ngJyq2eXngGj4zbhZLLm',
                'price' => 19900,
                'description' => 'For organizations requiring maximum power.'
            ]
        );
    }
}
