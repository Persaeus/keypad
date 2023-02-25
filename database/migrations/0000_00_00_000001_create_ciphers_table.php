<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ciphers', function (Blueprint $table) {
            $table->id();
            $table->morphs('cipherable');
            $table->timestamps();
            $table->json('data')->nullable();
        });
    }
};
