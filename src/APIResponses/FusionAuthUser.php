<?php

namespace Mknooihuisen\LaravelFusionauth\APIResponses;

use FusionAuth\ClientResponse;
use Mknooihuisen\LaravelFusionauth\Fusionauth;

class FusionAuthUser
{
    public string $email, $username, $fullName, $lastName, $id, $mobilePhone;
    public bool $active, $verified;
    public array $data, $groups;

    public static function FindByLoginId (string $loginId) : null|FusionAuthUser
    {
        try {
            $resp = \Fusionauth::client()->retrieveUserByLoginId($loginId);
            return new FusionAuthUser($resp);
        } catch (\Exception) {}

        return null;
    }

    public static function FindByUniqueId($id) {
        try {
            $resp = \Fusionauth::client()->retrieveUser($id);
            return new FusionAuthUser($resp);
        } catch (\Exception) {}

        return null;
    }


    public function __construct(ClientResponse $response)
    {
        if (!$response->wasSuccessful()) {
            return null;
        }
        $original = $response->successResponse;

        $this->email = $original->email;
        $this->id = $original->id;

        $this->fullName = $original->fullName ?? null;
        $this->lastName = $original->lastName ?? null;
        $this->username = $original->username ?? null;
        $this->mobilePhone = $original->mobilePhone ?? null;
        $this->data = $original->data ?? null;

        $this->groups = [];
        foreach ($original->memberships as $group) {
            $this->groups[] = $group->groupId;
        }

        $this->active = $original->active;
        $this->verified = $original->verified ?? true;
    }

}
