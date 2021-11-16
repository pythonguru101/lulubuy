<?php

namespace App\Observers;

use App\Models\User;
use App\Services\Firebase;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $firebaseKey = Firebase::create($user->toArray());
        if (!empty($firebaseKey)) {
            $user->firebase_key = $firebaseKey;
            $user->update(['firebase_key' => $firebaseKey]);
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if (!empty($user->firebase_key)) {
            Firebase::update($user->toArray(), 'user');
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        if (!empty($user->firebase_key)) {
            Firebase::delete($user->firebase_key, 'user');
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        if (!empty($user->firebase_key)) {
            Firebase::delete($user->firebase_key, 'user');
        }
    }
}
