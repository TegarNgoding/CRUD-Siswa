<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration

{

    public function up(): void

    {

        Schema::create('siswas', function (Blueprint $table) {

            $table->id();

            $table->string('nisn')->unique();

            $table->string('nama');

            $table->enum('jenis_kelamin', ['L', 'P']);

            $table->string('kelas');

            $table->text('alamat');

            $table->timestamps();

        });

    }

    public function down(): void

    {

        Schema::dropIfExists('siswas');

    }

};
