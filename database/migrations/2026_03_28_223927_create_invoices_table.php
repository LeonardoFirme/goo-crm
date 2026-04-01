<?php
// database/migrations/2026_03_28_223927_create_invoices_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('client_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->date('paid_at')->nullable();

            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');

            $table->string('invoice_number')->unique();
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};