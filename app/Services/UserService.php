<?php
namespace App\Services;

use App\Http\Requests\User\UserUpdateRequest;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid as UuidValidator;

class UserService implements UserServiceInterface
{

    public function getUsers(): array
    {
        return User::all()->toArray();
    }

    public function getUserById(string $id): User|string
    {
        if (UuidValidator::isValid($id)) {

            $user = User::where('id', $id);

            if ($user->exists()) return $user->first();
            else return 'The user was not found.';

        } else return 'Invalid ID format.';
    }

    public function store($payload): User
    {
        $payload = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password_hash' => Hash::make($payload['password']),
        ];

        return User::create($payload);
    }

    public function update(UserUpdateRequest $payload, string $id): array
    {
        $user = $this->getUserById($id);

        $payload = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password_hash' => Hash::make($payload['password']),
        ];

        $user->update($payload);

        return $user->toArray();
    }

    public function delete(string $id): void
    {
        $user = $this->getUserById($id);

        $user->delete();
    }
}