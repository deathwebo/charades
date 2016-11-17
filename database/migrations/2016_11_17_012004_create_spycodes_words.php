<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpycodesWords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spycodes_words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word');
            $table->timestamps();
        });

        $fileResource  = fopen(database_path().'/migrations/words', "r");
        if ($fileResource) {
            while (($line = fgets($fileResource)) !== false) {
                $words[] = ['word' => trim($line)];
            }
            fclose($fileResource);
        } 

        DB::table('spycodes_words')->insert($words);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('spycodes_words');
    }
}
