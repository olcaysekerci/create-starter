<?php

namespace Modules\User\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\User\Requests\Admin\CreateUserRequest;
use Modules\User\Services\UserService;
use App\Models\User;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return Inertia::render('Admin/UserIndex', [
            'users' => $users
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('admin.users.index')
                        ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/UserEdit', [
            'user' => $user
        ]);
    }

    public function update(CreateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('admin.users.index')
                        ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Kullanıcı başarıyla silindi.');
    }
} 