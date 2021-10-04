<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tin' => 'TIN ' . $this->faker->numberBetween(100, 999) . $this->faker->numberBetween(100, 999),
            'bir' => 'BIR ' . $this->faker->numberBetween(100, 999) . $this->faker->numberBetween(100, 999),
            'vat' => 'VAT ' . $this->faker->numberBetween(100, 999) . $this->faker->numberBetween(100, 999),
            'company_name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'address' => $this->faker->address(),
            'contact' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'contact_person' => $this->faker->name(),
        ];
    }
}
