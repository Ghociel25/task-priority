<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {

        // 1. Hapus foreign key constraint-nya terlebih dahulu
        $table->dropForeign(['course_id']);
        
        // 2. Baru hapus kolom course_id
        $table->dropColumn('course_id');
        
        // 3. Tambahkan kolom course baru dengan tipe data string
        $table->string('course')->after('id');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('course');
            $table->unsignedBigInteger('course_id')->nullable();
        });
    }
};