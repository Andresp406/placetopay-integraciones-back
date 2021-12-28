<?php

namespace App\Services\User;

use LauchoIT\LaravelService\Service\BaseService;

/**
 * Class UserService.
 */
class UserService extends BaseService
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }
}
