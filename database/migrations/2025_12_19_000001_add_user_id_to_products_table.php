<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Пополняем существующие записи: назначаем владельцем первого пользователя
        $firstUser = DB::table('users')->first();
        if (!$firstUser) {
            // Если пользователей нет, создадим временного системного пользователя
            $id = DB::table('users')->insertGetId([
                'name' => 'system',
                'email' => 'system@example.com',
                'password' => bcrypt(uniqid('sys_', true)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $id = $firstUser->id;
        }

        DB::table('products')->whereNull('user_id')->update(['user_id' => $id]);

        // Сделаем колонку обязательной
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};