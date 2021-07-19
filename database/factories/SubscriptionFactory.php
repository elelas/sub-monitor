<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $firstPaymentDate = $this->faker->date();

        return [
            'title' => $this->faker->unique()->word(),
            'first_payment_date' => $this->faker->date(),
            'next_payment_date' => $this->faker->date('Y-m-d', $firstPaymentDate),
            'interval_type' => $this->faker->randomElement([
                Subscription::DAY_INTERVAL,
                Subscription::WEEK_INTERVAL,
                Subscription::MONTH_INTERVAL,
                Subscription::YEAR_INTERVAL,
            ]),
            'interval_value' => $this->faker->numberBetween(1, 30),
            'payment_amount' => $this->faker->randomFloat(2, 1, 999999),
            'currency_code' => $this->faker->randomElement([
                Subscription::CURRENCY_RUB,
                Subscription::CURRENCY_USD,
                Subscription::CURRENCY_EUR,
            ]),
            'image' => $this->faker->image(storage_path('app/public'), 200, 84, null, false),
            'service_id' => Service::factory(),
            'with_prolongation' => $this->faker->boolean(),
            'user_id' => User::factory(),
        ];
    }

    public function onePayment(): SubscriptionFactory
    {
        return $this->state(function () {
            return [
                'next_payment_date' => null,
                'with_prolongation' => false,
            ];
        });
    }

    public function withExistedService(): SubscriptionFactory
    {
        return $this->state(function () {
            return [
                'title' => null,
                'image' => null,
            ];
        });
    }
}
