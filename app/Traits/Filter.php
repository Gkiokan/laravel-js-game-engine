<?php
namespace App\Traits;

use Auth;

trait Filter {

    public function scopeFilterByParams($q, $r){

        // Search by Title 
        if($r->has('q') && $r->q):
            $search = $r->q;
            $q->where(function($q) use ($search){
                $q->where('title', 'like', "%{$search}%")->orReleaseName($search);
            });          
        endif;

        // search by search 
        if($r->has('search') && $r->search):
          $search = $r->search;
          $q->where(function($q) use ($search){
              $q->where('title', 'like', "%{$search}%")->orReleaseName($search);
          });          
        endif;


        // check for types 
        if($r->has('types') && $r->types):
          $keys = explode(',', $r->types);

          if(count($keys) !== 0)
            $q->where(function($q) use ($keys){
                foreach($keys as $key):
                  $q->orWhere('type', 'like', "%{$key}%");
                endforeach;
            });
        endif;        

        // check for genres
        if($r->has('genres') && $r->genres):
            $keys = explode(',', $r->genres);

            if(count($keys) !== 0)
              $q->where(function($q) use ($keys){
                  foreach($keys as $key):
                    $q->orWhere('genre', 'like', "%{$key}%");
                  endforeach;
              });
        endif;


        // check order 
        if($r->has('sortOrder') && $r->has('sortBy')):
              // find real key first 
              $key      = $r->sortBy;
              $orderBy  = 'updated_at';

              if(in_array($key, ['latest', 'created']))
                $orderBy = 'updated_at';

              if(in_array($key, ['released', 'year']))
                $orderBy = 'released_at';

              if(in_array($key, ['updated']))
                $orderBy = 'updated_at';                

              if($key == 'rating')
                $orderBy = 'rating';             
                
              if($key == 'popular')
                $orderBy = 'downloads';        
                
              if($key == 'trends')
                $orderBy = 'views';

              // set the order typ 
              $order = 'desc';
              $key   = $r->sortOrder;

              if($key == 'asc')
                $order = 'asc';

              if($key == 'desc')
                $order = 'desc';

              $q->orderBy($orderBy, $order);
          
        // default order 
        else:
            $q->orderBy('created_at', 'desc');
        endif;

    }


    public function scopeFilterByMyApps($tasks, $r){

        // categories
        if($r->has('category') && strlen($r->category)):
            $cat = $r->category;

            if( !in_array($cat, ['all', 'favorite']) ):
              $tasks->where('type', $r->category);
            elseif($cat == 'favorite'):
              $tasks->favoriteFromUser(Auth::id());
            endif;

        endif;

        // add search by name
        if($r->has('search') && strlen($r->search) > 0):
            $tasks->where('title', 'like', "%{$r->search}%")->orWhere('fulltitle', 'like', "%{$r->search}%");
        endif;

        // Ordering
        if($r->input('orderBy') != '' && $r->has('orderDir') !== '')
            $tasks->orderBy($r->input('orderBy'), $r->input('orderDir', 'desc'));
        else
            $tasks->orderBy('title', 'asc');
    }

}
