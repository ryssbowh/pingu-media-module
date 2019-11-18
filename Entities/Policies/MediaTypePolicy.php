<?php

namespace Pingu\Media\Entities\Policies;

use Pingu\User\Entities\User;
use Pingu\Entity\Entities\Entity;
use Pingu\Media\Entities\MediaType;

class MediaTypePolicy
{
    protected function userOrGuest(?User $user)
    {
        return $user ? $user : \Permissions::guestRole();
    }

    public function index(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view media types');
    }

    public function view(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view media types');
    }

    public function edit(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit media types');
    }

    public function delete(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('delete media types');
    }

    public function create(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('create media types');
    }
}