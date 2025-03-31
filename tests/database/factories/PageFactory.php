<?php

namespace Awcodes\Richie\Tests\Database\Factories;

use Awcodes\Richie\Support\Faker;
use Awcodes\Richie\Tests\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'content' => null,
        ];
    }

    public function withContent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'content' => Faker::make()->heading()->paragraphs()->asJson(),
            ];
        });
    }
}
