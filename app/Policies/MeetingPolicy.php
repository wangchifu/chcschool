<?php

namespace App\Policies;

use App\User;
use App\Meeting;
use App\UserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function view(User $user, Meeting $meeting)
    {
        //
    }

    /**
     * Determine whether the user can create meetings.
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
     * Determine whether the user can update the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function update(User $user, Meeting $meeting)
    {
        return $user->admin === 1;
    }

    /**
     * Determine whether the user can delete the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function delete(User $user, Meeting $meeting)
    {
        return $user->admin === 1;
    }
}
