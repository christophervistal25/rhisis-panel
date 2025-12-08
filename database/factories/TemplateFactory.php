<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    protected $model = \App\Models\Template::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bundleFileName = 'avatars/' . str()->random(10) . '.zip';
        Storage::disk('public')->put($bundleFileName, 'sample_content');
        $url = Storage::disk('public')->url($bundleFileName);


        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->text(200),
            'thumbnail' => fake()->imageUrl(),
            'release_date' => fake()->dateTime(),
            'is_active' => fake()->boolean(),
            'bundle_url' =>  $url,
            'display_order' => fake()->numberBetween(1, 20),
        ];
    }
}
