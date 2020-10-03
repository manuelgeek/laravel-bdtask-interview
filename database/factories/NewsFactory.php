<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'title' => $title,
            'body' => $this->faker->paragraph,
            'slug' => Str::slug($title, '-'). '-' . \Illuminate\Support\Str::random(5),
            'topic' => array_rand(config('settings.topics')),
        ];
    }
}
