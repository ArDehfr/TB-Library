<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_average_rating AFTER INSERT ON reviews
            FOR EACH ROW
            BEGIN
                DECLARE avg_rating FLOAT;
                SELECT AVG(rating) INTO avg_rating FROM reviews WHERE book_id = NEW.book_id;
                UPDATE books SET average_rating = avg_rating WHERE book_id = NEW.book_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_average_rating');
    }
};
