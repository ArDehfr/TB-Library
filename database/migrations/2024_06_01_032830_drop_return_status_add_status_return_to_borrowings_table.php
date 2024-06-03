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
            $table->dropColumn('return_status');
            $table->string('status_return')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('status_return');
            $table->enum('return_status', ['Damaged', 'Lost', 'Late', 'Normal'])->nullable()->after('status');
        });
}
};
