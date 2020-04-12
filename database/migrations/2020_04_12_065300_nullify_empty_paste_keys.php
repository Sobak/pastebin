<?php

use App\Models\Paste;
use Illuminate\Database\Migrations\Migration;

class NullifyEmptyPasteKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Paste::all()->chunk(50) as $chunk) {
            /** @var Paste $paste */
            foreach ($chunk as $paste) {
                if (password_verify('', $paste->key)) {
                    $paste->key = null;
                    $paste->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Not reversible
    }
}
