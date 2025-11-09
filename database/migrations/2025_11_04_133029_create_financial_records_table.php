<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // income | expense
            $table->enum('type', ['income', 'expense']);

            // nilai uang
            $table->decimal('amount', 18, 2);

            // tanggal transaksi (bisa beda dgn created_at)
            $table->date('transacted_at')->index();

            // judul singkat + deskripsi (opsional pakai Trix)
            $table->string('title', 160);
            $table->text('notes')->nullable();

            // upload bukti/cover (opsional)
            $table->string('cover')->nullable();

            // kategori (opsional, utk filter)
            $table->string('category', 80)->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('financial_records');
    }
};
