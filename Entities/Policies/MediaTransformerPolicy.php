<?php

namespace Pingu\Media\Entities\Policies;

use Pingu\User\Entities\User;
use Pingu\Entity\Entities\Entity;
use Pingu\Media\Entities\MediaTransformer;

class MediaTransformerPolicy
{
    protected function userOrGuest(?User $user)
    {
        return $user ? $user : \Permissions::guestRole();
    }

    public function index(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    public function view(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    public function edit(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    public function delete(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    public function create(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }
}