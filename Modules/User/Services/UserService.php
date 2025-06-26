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

        // Manuel log - kim oluşturdu
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->event('created')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'admin_action' => true,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ])
            ->log('Yeni kullanıcı oluşturdu');

        return $user;
    }

    public function updateProfile(User $user, array $data): User
    {
        $oldData = $user->only(['name', 'email']);
        
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Profil güncelleme logu
        activity()
            ->causedBy($user) // Kendisi güncelledi
            ->performedOn($user)
            ->event('profile_updated')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'old' => $oldData,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ])
            ->log('Profil bilgilerini güncelledi');

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $oldData = $user->only(['name', 'email']);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        $logDescription = 'Kullanıcı bilgilerini güncelledi';
        
        // Şifre güncellemesi sadece dolu gelirse yapılır
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
            $logDescription = 'Kullanıcı bilgilerini ve şifresini güncelledi';
        }

        $user->update($updateData);

        // Admin tarafından güncelleme logu
        activity()
            ->causedBy(Auth::user()) // Admin güncelledi
            ->performedOn($user)
            ->event('updated')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'admin_action' => true,
                'old' => $oldData,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'password_changed' => !empty($data['password'])
            ])
            ->log($logDescription);

        return $user->fresh();
    }

    public function deleteUser(User $user): bool
    {
        // Silme işlemi öncesi log
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->event('deleted')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'admin_action' => true,
                'deleted_user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ])
            ->log('Kullanıcıyı sildi');

        return $user->delete();
    }
} 