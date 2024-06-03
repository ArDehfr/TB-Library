<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('report');
            $table->text('return_report')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('return_report');
            $table->text('report')->nullable();
        });
    }
};
