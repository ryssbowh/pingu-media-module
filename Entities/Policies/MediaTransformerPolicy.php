<?php

namespace Pingu\Media\Entities\Policies;

use Pingu\Core\Support\Policy;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Support\Entity;
use Pingu\Entity\Support\Policies\BaseEntityPolicy;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\User\Entities\User;

class MediaTransformerPolicy extends BaseEntityPolicy
{
    /**
     * @inheritDoc
     */
    public function index(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    /**
     * @inheritDoc
     */
    public function view(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    /**
     * @inheritDoc
     */
    public function edit(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    /**
     * @inheritDoc
     */
    public function delete(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }

    /**
     * @inheritDoc
     */
    public function create(?User $user, ?BundleContract $bundle = null)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit images styles');
    }
}