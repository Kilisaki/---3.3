<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        // Any authenticated user can view their own products; admins handled in before
        return true;
    }

    public function view(User $user, Product $product)
    {
        // Любой авторизованный пользователь может просматривать товары
        return true;
    }

    public function create(User $user)
    {
        return $user->id !== null;
    }

    public function update(User $user, Product $product)
    {
        return $product->user_id === $user->id;
    }

    public function delete(User $user, Product $product)
    {
        return $product->user_id === $user->id;
    }

    public function restore(User $user, Product $product)
    {
        // Only admins can restore (handled by before())
        return false;
    }

    public function forceDelete(User $user, Product $product)
    {
        // Only admins can force delete (handled by before())
        return false;
    }
}
