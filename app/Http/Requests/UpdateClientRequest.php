<?php
// app/Http/Requests/UpdateClientRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
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
        // Obtemos o ID do cliente da rota para a exceção do Unique
        $clientId = $this->route('client')->id ?? $this->route('client');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId)
            ],
            'tax_id'   => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'tax_id')->ignore($clientId)
            ],
            'phone'    => ['nullable', 'string', 'max:20'],
            'website'  => ['nullable', 'url', 'max:255'],
            'status'   => ['required', Rule::in(['active', 'inactive', 'lead'])],
            'notes'    => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Mensagens de erro customizadas.
     */
    public function messages(): array
    {
        return [
            'tax_id.unique' => 'Este CPF/CNPJ já está cadastrado em outro cliente.',
            'email.unique'  => 'Este e-mail já pertence a outro cliente.',
        ];
    }
}