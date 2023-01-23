<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourcefileAppQuestionCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_elearning_question_collections', function (Blueprint $table) {
            $table->string('sourcefile')->nullable()->after('answer_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_elearning_question_collections', function (Blueprint $table) {
            $table->dropColumn('sourcefile');
        });
    }
}
