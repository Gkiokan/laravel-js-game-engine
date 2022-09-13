<?php

namespace App\Traits;

trait EntryScopes
{

    public function scopeFromUser($q, $user_id){
        return $q->where('user_id', $user_id);
    }

    public function scopeFavoriteFromUser($q, $user_id){
        return $q->whereHas('favorite', function($q) use ($user_id) {
          return $q->where('user_id', $user_id);
        });
    }

    public function scopeLikedFromUser($q, $user_id){
        return $q->whereHas('likes', function($q) use ($user_id) {
          return $q->where('user_id', $user_id);
        });
    }

    public function scopeRatingFromUser($q, $user_id){
        return $q->whereHas('ratings', function($q) use ($user_id) {
          return $q->where('user_id', $user_id);
        });
    }


    // scopes
    public function scopeVerified($q){
        $q->whereNotNull('verified_at');
    }

    public function scopeActive($q){
        $q->where('active', 1);
    }

    public function scopeDisabled($q){
        $q->where('active', 0);
    }

    public function scopeUpdateable($q){
        $q->whereNull('update');
    }

}
