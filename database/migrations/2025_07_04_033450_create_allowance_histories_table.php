<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allowance_histories', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 12, 2);
            $table->decimal('delta', 12, 2);
            $table->enum('type', ['increase', 'decrease', 'refund']);
            $table->text('description')->nullable();

            $table->foreignId('request_id')
                ->nullable()
                ->constrained('allowance_requests')
                ->nullOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->foreignId('vouchers_id')
                ->nullable()
                ->constrained('vouchers')
                ->nullOnDelete();
            $table->unique('vouchers_id', 'unique_voucher_history');

            $table->foreignId('notifications_id')
                ->nullable()
                ->constrained('notifications')
                ->nullOnDelete();
            $table->unique('notifications_id', 'unique_notification_history');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allowance_histories');
    }
};
