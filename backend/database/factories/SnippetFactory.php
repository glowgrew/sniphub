<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Snippet>
 */
class SnippetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hashids = new Hashids();
        return [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->text(),
            'category_id' => Category::query()->inRandomOrder()->first()->id,
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'is_public' => $this->faker->boolean(),
            'expiration_time' => now()->addSeconds(10),
            'unique_id' => $hashids->encode(random_int(0, 100000))
        ];
    }
}
