<?php

namespace Tests\Feature\app\Repositories\Eloquent;

use App\Event;
use App\Repositories\Eloquent\EloquentUserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new EloquentUserRepository();
    }

    public function testShouldGetUsersWithUpcomingEvents()
    {
        $userWithUpcomingEvent = factory(User::class)->create()
            ->each(function ($user) {
                $user->events()->save(factory(Event::class)->make([
                    'start_at' => Carbon::now(),
                    'end_at' => Carbon::now()->addWeek(),
                ]));
            });

        $userWithoutUpcomingEvent = factory(User::class)->create()
            ->each(function ($user) {
                $user->events()->save(factory(Event::class)->make([
                    'start_at' => Carbon::now()->addYear(),
                    'end_at' => Carbon::now()->addYear()->addWeek(),
                ]));
            });

        $users = $this->userRepository->getUsersWithUpcomingEvents(5);

        $this->assertCount(1, $users);
    }

    public function testShouldUpdateUser()
    {
        $user = factory(User::class)->create();

        $newData = [
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
        ];

        $this->userRepository->updateUser($user, $newData);

        $this->assertEquals($newData['name'], $user->name);
        $this->assertEquals($newData['email'], $user->email);
        $this->assertDatabaseHas('users', $newData);
    }

    public function testShouldThrowExceptionWhenUpdateToEmailInUse()
    {
        $this->expectException(\Exception::class);

        $existingEmail = 'mary@mail.com';
        $existingUser = factory(User::class)->create([
            'email' => $existingEmail,
        ]);

        $newUser = factory(User::class)->create();
        $newData = [
            'name' => 'Mary II',
            'email' => $existingEmail,
        ];

        $this->userRepository->updateUser($newUser, $newData);
    }

    public function testShouldUpdatePassword()
    {
        $user = factory(User::class)->create();
        $newPassword = 'newPassword';

        $this->userRepository->updatePassword($user, $newPassword);

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function testShouldThrowExceptionWhenUpdateToWeakPassword()
    {
        $this->expectException(\Exception::class);

        $user = factory(User::class)->create();
        $newPassword = '1234';

        $this->userRepository->updatePassword($user, $newPassword);
    }
}
