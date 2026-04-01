<?php
// app/Http/Requests/StoreInvoiceRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $cleanAmount = str_replace(['R$', '.', ' '], '', $this->amount);
            $cleanAmount = str_replace(',', '.', $cleanAmount);

            $this->merge([
                'amount' => is_numeric($cleanAmount) ? (float) $cleanAmount : $this->amount,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'string', Rule::in(['pending', 'paid', 'overdue', 'cancelled'])],
            'payment_method' => ['nullable', 'required_if:status,paid', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}