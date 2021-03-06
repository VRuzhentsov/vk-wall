<?php

namespace Tests\traits;

use Faker\Factory as Faker;
use App\Models\User;
use App\Repositories\UserRepository;

trait MakeUserTrait
{
    /**
     * Create fake instance of User and save it in database
     *
     * @param array $userFields
     *
     * @return User
     */
    public function makeUser($userFields = [])
    {
        /** @var UserRepository $userRepo */
        $userRepo = \App::make(UserRepository::class);
        $theme = $this->fakeUserData($userFields);
        
        return $userRepo->create($theme);
    }
    
    /**
     * Get fake data of User
     *
     * @param array $postFields
     *
     * @return array
     */
    public function fakeUserData($userFields = [])
    {
        $fake = Faker::create();
        
        return array_merge([
            'name'           => $fake->word,
            'email'          => $fake->email,
            'password'       => $fake->word,
            'remember_token' => $fake->word,
            'created_at'     => $fake->date('Y-m-d H:i:s'),
            'updated_at'     => $fake->date('Y-m-d H:i:s')
        ], $userFields);
    }
    
    /**
     * Get fake instance of User
     *
     * @param array $userFields
     *
     * @return User
     */
    public function fakeUser($userFields = [])
    {
        return new User($this->fakeUserData($userFields));
    }
}
