<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();

            // identitas calon pelanggan
            $table->string('name', 80);
            $table->string('email', 120);
            $table->string('phone', 30);

            // judul/topik request
            $table->string('subject', 120);

            // status penanganan
            $table->string('status', 20)->default('new');
            // new | replied | closed

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
