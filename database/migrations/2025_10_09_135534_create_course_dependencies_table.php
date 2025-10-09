<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseDependenciesTable extends Migration
{
    public function up()
    {
        Schema::create('course_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('prerequisite_id')->references('id')->on('courses')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['course_id', 'prerequisite_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_dependencies');
    }
}