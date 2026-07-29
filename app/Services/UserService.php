<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepository $userRepo) {}

    public function all(): AnonymousResourceCollection
    {
        return UserResource::collection($this->userRepo->all());
    }

    public function create(array $data): UserResource
    {
        $user = $this->userRepo->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return new UserResource($user);
    }

    public function update(User $user, array $data): UserResource
    {
        $payload = array_filter([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => ! empty($data['password']) ? Hash::make($data['password']) : null,
        ]);


        $user = $this->userRepo->update($user, $payload);
        $user->syncRoles([$data['role']]);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function delete(User $user): void
    {
        $this->userRepo->delete($user);
    }
}
