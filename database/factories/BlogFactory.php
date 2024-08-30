<?php

namespace Database\Factories;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
class BlogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'=>fake()->name(),
            'content'=>fake()->text(),
            'status'=>rand(0,1),
        ];
    }
}
