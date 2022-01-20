<?php
declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Jobs\SendInvitationMail;
use App\Models\{User, Invitation};
use App\Repositories\{UserRepository, InvitationRepository};
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getUsers();
        return $users->map(fn(User $user) => new UserResource($user));
    }

    public function updateProfile(array $data, int $id): UserResource
    {
        $user = $this->userRepository->getDuplicateUserByPhoneNumber($data['phone_number'], $id);

        if ($user) {
            abort(403, 'Phone number belongs to another user');
        }

        return new UserResource($this->userRepository->updateProfile($data, $id));
    }

    public function profile(int $id): ?UserResource
    {
        $user = $this->userRepository->getUser($id);

        if (!$user) {
            abort(404, 'User not found');
        }
        return new UserResource($user);
    }

    public function updateProfilePicture(object $image, int $id): UserResource
    {
        $filename = time() . '.' . $image->extension();
        Storage::disk('s3')->put($filename, file_get_contents($image->getRealPath()));
        $user = $this->userRepository->updateProfilePicture($filename, $id);
        return new UserResource($user);
    }

    public function updatePassword(array $data, int $id): User
    {
        return $this->userRepository->updatePassword($data, $id);
    }

    public function inviteUser(string $invitee, User $user): Invitation
    {
        $token = Str::random(60);
        $invitationLink = config('app.url') . '/invitations?token=' . $token;
        $expiration = Carbon::now()->addDays(2)->toDateTimeString();

        $data = json_encode([
            'invitee'         => $invitee,
            'user'            => $user,
            'token'           => $token,
            'invitation_link' => $invitationLink,
            'expiration'      => $expiration
        ]);

        SendInvitationMail::dispatch($data);

        return app(InvitationRepository::class)->create([
            'invited_by' => $user->id,
            'invitee'    => $invitee,
            'token'      => $token,
            'expires_at' => $expiration
        ]);
    }
}
