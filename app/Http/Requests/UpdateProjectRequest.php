<?php
// app/Http/Requests/UpdateProjectRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepara os dados para validação, removendo a máscara monetária.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('budget')) {
            // Remove R$, pontos de milhar e espaços. Troca vírgula decimal em ponto.
            $clean = str_replace(['R$', '.', ' '], '', $this->budget);
            $clean = str_replace(',', '.', $clean);

            // Força a substituição no input para que a regra 'numeric' funcione
            $this->merge([
                'budget' => $clean,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'client_id'   => ['required', 'exists:clients,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'deadline'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget'      => ['required', 'numeric', 'min:0'],
            'status'      => ['required', Rule::in(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'])],
        ];
    }
}