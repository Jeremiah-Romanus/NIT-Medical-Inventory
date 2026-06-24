<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->text('approval_note')->nullable()->after('remarks')->comment('Note added during approval');
            $table->text('rejection_reason')->nullable()->after('approval_note')->comment('Reason for rejection');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason')->comment('Timestamp when request was approved');
            $table->timestamp('rejected_at')->nullable()->after('approved_at')->comment('Timestamp when request was rejected');
            $table->foreignId('approved_by')->nullable()->after('rejected_at')->constrained('users')->comment('User who approved the request');
            $table->foreignId('rejected_by')->nullable()->after('approved_by')->constrained('users')->comment('User who rejected the request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'approved_by');
            $table->dropForeignIdFor(\App\Models\User::class, 'rejected_by');
            $table->dropColumn([
                'approval_note',
                'rejection_reason',
                'approved_at',
                'rejected_at',
                'approved_by',
                'rejected_by',
            ]);
        });
    }
};
