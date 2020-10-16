<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-eloquent-tags.table_names.tags'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create(config('laravel-eloquent-tags.table_names.model_has_tags'), function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->morphs('taggable');

            $table->primary(['tag_id', config('laravel-eloquent-tags.column_names.taggable_morph_key'), 'taggable_type']);

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-eloquent-tags.table_names.model_has_tags'));
        Schema::dropIfExists(config('laravel-eloquent-tags.table_names.tags'));
    }
}
