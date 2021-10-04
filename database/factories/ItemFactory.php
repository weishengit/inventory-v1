<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_id' => $this->faker->numberBetween(1, 10),
            'type_id' => $this->faker->numberBetween(1, 10),
            'sku' => $this->faker->ean13(),
            'name' => 'Product ' . $this->faker->numberBetween(1, 999),
            'unit_price' => $this->faker->numberBetween(1, 999) . '.' . $this->faker->numberBetween(100, 999) . $this->faker->numberBetween(0, 99),
            'quantity' => $this->faker->numberBetween(1000, 9000),
            'critical_level' => $this->faker->numberBetween(100, 600)
        ];
    }
}
