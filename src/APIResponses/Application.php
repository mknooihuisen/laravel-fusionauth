<?php

namespace Mknooihuisen\LaravelFusionauth\APIResponses;

/**
 *
 */
class Application
{
    public bool $active = false;
    public string $name;
    public bool $verificationRequired;

    public array $roles = [];
    public RegistrationConfiguration $registrationConfiguration;
    public bool $loginIdType;

    public function __construct(\stdClass $original)
    {
        $this->active = $original->active;
        $this->name = $original->name;
        $this->verificationRequired = $original->verifyRegistration;
        $this->registrationConfiguration = new RegistrationConfiguration($original->registrationConfiguration);

        foreach ($original->roles as $role) {
            $this->roles[] = new Role($role);
        }

        $this->loginIdType = $original->loginIdType ?? 'email';
    }

}

class RegistrationConfiguration {
    public array $fields;
    public bool $confirmPassword, $enabled, $isEmailId, $isUsernameId;

    public function __construct(\stdClass $original)
    {
        $fields['user.birthDate'] = new RegistrationField($original->birthDate);
        $fields['user.firstName'] = new RegistrationField($original->firstName);
        $fields['user.lastName'] = new RegistrationField($original->lastName);
        $fields['user.fullName'] = new RegistrationField($original->fullName);
        $fields['user.middleName'] = new RegistrationField($original->middleName);
        $fields['user.mobilePhone'] = new RegistrationField($original->mobilePhone);
        $this->confirmPassword = $original->confirmPassword;
        $this->enabled = $original->enabled;
        $this->isEmailId = $original->loginIdType === 'email';
        $this->isUsernameId = !$this->isEmailId;
    }
}

class RegistrationField {
    public bool $required, $enabled;
    public function __construct(\stdClass $original)
    {
        $this->required = $original->required;
        $this->enabled = $original->enabled;
    }
}

class Role {
    public string $name, $id, $description;
    public bool $isDefault, $isSuperRole;

    public function __construct(\stdClass $role)
    {
        $this->name = $role->name;
        $this->id = $role->id;
        $this->description = $role->description;
        $this->isDefault = $role->isDefault;
        $this->isSuperRole = $role->isSuperRole;
    }
}
