<?php

namespace Modules\User\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\User\Requests\Web\UpdateProfileRequest;
use Modules\User\Services\UserService;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function show()
    {
        return Inertia::render('User/Profile', [
            'user' => auth()->user()
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->userService->updateProfile(auth()->user(), $request->validated());

        return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
    }
} 