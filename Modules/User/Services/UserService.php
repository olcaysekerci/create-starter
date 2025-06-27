<?php

namespace Modules\User\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Modules\Log\Models\Activity;

class UserService
{
    public function getAllUsers(): Collection
    {
        $users = User::latest()->get();
        
        // Performans için toplu olarak son giriş zamanlarını çek
        $userIds = $users->pluck('id')->toArray();
        $lastLogins = $this->getLastLoginsForUsers($userIds);
        
        // Her kullanıcı için son giriş zamanını ekle
        $users->each(function ($user) use ($lastLogins) {
            $user->last_login = $lastLogins[$user->id] ?? null;
        });
        
        return $users;
    }

    /**
     * Kullanıcının son giriş zamanını döndür
     */
    public function getLastLoginForUser(int $userId): ?string
    {
        $lastLogin = Activity::where('causer_id', $userId)
            ->where('event', 'login')
            ->latest('created_at')
            ->first();
            
        return $lastLogin ? $lastLogin->created_at->toISOString() : null;
    }

    /**
     * Birden fazla kullanıcının son giriş zamanlarını toplu olarak döndür
     */
    public function getLastLoginsForUsers(array $userIds): array
    {
        $lastLogins = Activity::whereIn('causer_id', $userIds)
            ->where('event', 'login')
            ->select('causer_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('causer_id')
            ->map(function ($activities) {
                return $activities->first()->created_at->toISOString();
            });
            
        return $lastLogins->toArray();
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