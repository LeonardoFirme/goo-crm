<?php
// database/factories/ClientFactory.php
namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * O nome do model correspondente.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // O UUID é gerado automaticamente pelo trait HasUuids no Model
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'tax_id' => fake()->unique()->numerify('##.###.###/0001-##'),
            'phone' => fake()->phoneNumber(),
            'website' => 'https://' . fake()->domainName(),
            'status' => fake()->randomElement(['active', 'inactive', 'lead']),
            'notes' => fake()->sentence(12),
        ];
    }
}