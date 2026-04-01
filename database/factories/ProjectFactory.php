<?php
// database/factories/ProjectFactory.php
namespace Database\Factories;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define o estado padrão do modelo.
     */
    public function definition(): array
    {
        return [
            // Cria um Client via UUID Factory automaticamente se não for fornecido
            'client_id' => Client::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'start_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'deadline' => fake()->dateTimeBetween('now', '+8 months'),
            'budget' => fake()->randomFloat(2, 5000, 150000),
            'status' => fake()->randomElement(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled']),
        ];
    }
}