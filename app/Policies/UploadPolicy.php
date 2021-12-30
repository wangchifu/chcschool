<?php

namespace App\Policies;

use App\Upload;
use App\User;
use App\UserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class UploadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function view(User $user, Upload $upload)
    {
        //
    }

    /**
     * Determine whether the user can create upload.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $check = UserGroup::where('user_id',$user->id)
            ->where('group_id',1)
            ->first();
        return !empty($check);
    }

    /**
     * Determine whether the user can update the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function update(User $user, Upload $upload)
    {
        //
    }

    /**
     * Determine whether the user can delete the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function delete(User $user, Upload $upload)
    {
        //
    }
}
