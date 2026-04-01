<?php
// app/Http/Requests/StoreProjectRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('budget')) {
            // Remove "R$", espaços, e pontos de milhar, depois troca vírgula por ponto
            $cleanBudget = str_replace(['R$', '.', ' '], '', $this->budget);
            $cleanBudget = str_replace(',', '.', $cleanBudget);

            $this->merge([
                'budget' => is_numeric($cleanBudget) ? (float) $cleanBudget : $this->budget,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'deadline' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['required', 'numeric', 'min:0'], // Agora receberá o valor limpo
            'status' => ['required', Rule::in(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.exists' => 'O cliente selecionado é inválido.',
            'deadline.after_or_equal' => 'O prazo final não pode ser anterior à data de início.',
            'budget.numeric' => 'O orçamento deve ser um valor numérico válido.',
            'budget.min' => 'O orçamento não pode ser um valor negativo.',
        ];
    }
}