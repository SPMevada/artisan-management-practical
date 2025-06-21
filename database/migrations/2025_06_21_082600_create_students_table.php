<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('class')->nullable();
            $table->string('roll_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER tr_students_roll_number BEFORE INSERT ON students
            FOR EACH ROW
            BEGIN
                IF NEW.roll_number IS NULL THEN
                    SET NEW.roll_number = CONCAT("R", LPAD(
                        (SELECT IFNULL(MAX(CAST(SUBSTRING(roll_number, 2) AS UNSIGNED)), 0) + 1 FROM students),
                    4, "0"));
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
