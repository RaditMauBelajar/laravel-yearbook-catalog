<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiry_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('message_id')
                  ->constrained('inquiry_messages')
                  ->cascadeOnDelete();

            // lokasi file di storage
            $table->string('path');

            // info file
            $table->string('original_name');
            $table->string('mime', 80);
            $table->unsignedBigInteger('size');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiry_attachments');
    }
};
