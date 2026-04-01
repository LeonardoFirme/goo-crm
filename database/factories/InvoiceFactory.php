<?php
// database/factories/InvoiceFactory.php
namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define o estado padrão do modelo.
     */
    public function definition(): array
    {
        // Criamos ou associamos um projeto para herdar o client_id correto
        $project = Project::factory()->create();

        return [
            'project_id' => $project->id,
            'client_id' => $project->client_id, // Integridade UUID mantida
            'amount' => fake()->randomFloat(2, 500, 25000),
            'due_date' => fake()->dateTimeBetween('now', '+45 days'),
            'paid_at' => null,
            'status' => 'pending',
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'notes' => fake()->sentence(),
        ];
    }

    /**
     * Estado para faturas já liquidadas.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}