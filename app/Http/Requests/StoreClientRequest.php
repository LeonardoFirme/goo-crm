<?php
// app/Http/Requests/StoreClientRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * Deixamos como true pois o Middleware 'auth' nas rotas garantirá a segurança.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação aplicadas à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')],
            'tax_id' => ['required', 'string', 'max:20', Rule::unique('clients', 'tax_id')],
            'phone' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'lead'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Mensagens de erro customizadas.
     */
    public function messages(): array
    {
        return [
            'tax_id.unique' => 'Este CPF/CNPJ já está cadastrado no sistema.',
            'email.unique' => 'Este e-mail já pertence a outro cliente.',
            'status.in' => 'O status selecionado é inválido.',
        ];
    }
}