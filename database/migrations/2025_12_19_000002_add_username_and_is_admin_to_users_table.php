<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->boolean('is_admin')->default(false)->after('remember_token');
        });

        // Заполним username для существующих пользователей
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $base = preg_replace('/[^a-z0-9_]+/i', '_', strtolower($user->name ?: explode('@', $user->email)[0]));
            $username = $base;
            $i = 1;
            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $base . '_' . $i++;
            }
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        // Сделаем username обязательным
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('is_admin');
        });
    }
};