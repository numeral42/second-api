<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence();
        static $order = 0;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => $this->faker->text(),
            'body' => $this->faker->paragraphs(3, true),
            'questions' => $this->faker->paragraphs(2, true),
            'sources' => $this->faker->paragraphs(2, true),
            /* 'is_published' => $this->faker->boolean(),
            'published_at' => $this->faker->dateTime(), */
            'order' => ++$order,
            'category_id' => \App\Models\Category::all()->random()->id,
            'user_id' => \App\Models\User::all()->random()->id,
        ];
    }
}
