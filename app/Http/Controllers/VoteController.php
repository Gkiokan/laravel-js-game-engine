<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PKG;

use App\Traits\VoteTrait;

class VoteController extends Controller
{
    use VoteTrait;

    public $item = null;

    public function __construct(){
        $this->item = PKG::with('votes', 'favorite', 'likes', 'ratings');
    }


    // vote 1
    public function voteComplaint(Request $r){
        return $this->add($r, 'votes', $this->item, ['up', 'down']);
    }

    // vote -1
    public function devoteComplaint (Request $r){
        return $this->remove($r, 'votes', $this->item);
    }


    // favorite 1
    public function favoriteComplaint(Request $r){
        return $this->add($r, 'favorite', $this->item);
    }

    // favorite -1
    public function defavoriteComplaint(Request $r){
        return $this->remove($r, 'favorite', $this->item);
    }


    // like +1
    public function likeComplaint(Request $r){
        return $this->add($r, 'likes', $this->item);
    }

    // like -1
    public function delikeComplaint(Request $r){
        return $this->remove($r, 'likes', $this->item);
    }


    // rating
    // vote may be integer from 1-5 usually
    public function rateComplaint(Request $r){
        return $this->add($r, 'ratings', $this->item, [1,2,3,4,5]);
    }

    // derate
    public function derateComplaint(Request $r){
        return $this->remove($r, 'ratings', $this->item);
    }

}
