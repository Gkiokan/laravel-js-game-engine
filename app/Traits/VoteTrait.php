<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Auth;

trait VoteTrait
{

      // add vote to item
      public function add(Request $r, $rel, $model, $options=[]){
          if(!Auth::user())
            return response()->json(['status' => 'NO_AUTH'], 401);

          $uid      = $r->input('id');
          $vote     = $r->input('vote');
          $user_id  = Auth::id();

          if(count($options))
          if(!in_array($vote, $options))
            return response()->json(['status' => 'NO_VALID_OPTION'], 413);

          // get item
          $item = $model->where('id', $uid)->firstOrFail();

          // $vote    = new Vote(['vote' => $vote]);
          $item->{$rel}()->updateOrCreate(['user_id' => $user_id], ['vote' => $vote ]);

          // refresh model
          $item = $model->where('id', $uid)->firstOrFail();

          return response()->json([
              'vote' => $vote,
              'item' => $item,
          ]);
      }



      // remove vote from item
      public function remove(Request $r, $rel, $model){
          if(!Auth::user())
            return response()->json(['status' => 'NO_AUTH'], 401);

          $user_id = Auth::id();
          $uid  = $r->input('id');

          // get item
          $item = $model->where('id', $uid)->firstOrFail();

          // remove vote
          $item->{$rel}()->where('user_id', $user_id)->delete();

          // refresh model
          $item = $model->where('id', $uid)->firstOrFail();

          return response()->json([
              'vote' => 'devote',
              'item' => $item,
          ]);
      }


}
