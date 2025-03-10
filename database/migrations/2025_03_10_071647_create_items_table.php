<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable(); // Stores the image filename or path
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('items');
    }
};
