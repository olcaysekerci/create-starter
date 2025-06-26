<?php

namespace Modules\User\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getAllUsers(): Collection
    {
        return User::latest()->get();
    }

    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // LogsActivity trait otomatik olarak log oluşturacak
        return $user;
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // LogsActivity trait otomatik olarak log oluşturacak
        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Şifre güncellemesi sadece dolu gelirse yapılır
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        // LogsActivity trait otomatik olarak log oluşturacak
        return $user->fresh();
    }

    public function deleteUser(User $user): bool
    {
        // LogsActivity trait otomatik olarak log oluşturacak
        return $user->delete();
    }
} 