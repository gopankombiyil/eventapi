<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class EventsController extends Controller
{

    private $events;

    public function __construct()
    {
        //import the events json as an array
        $this->events = json_decode(file_get_contents(storage_path('app/event.json')),true);
    }


    /**
     * Show all the events
     * @param  Request $request allow filters to filter results
     * @return json           json object of events
     */
    public function index(Request $request)
    {
      $keyword = $request->keyword;
      $city = $request->city;

      if($request->date) {
        $eventDate = Carbon::createFromFormat('d/m/Y', $request->date);
      }

      $result = $this->events;

      //filter the data with the selected keyword if the request contains keyword
      if($keyword) {
        $result = array_filter($result, function($el) use ($keyword) {
          return ( strpos($el['Title'], $keyword) !== false );
        });
      }

      //filter the data for city if the requst contains city
      if($city) {
        $result = array_filter($result, function($el) use ($city) {
          return ( $el['Location']['City'] === $city) ;
        });
      }

      //filter the data for the date of the request contains a date
      if(isset($eventDate)) {
        $result = array_filter($result, function($el) use ($eventDate) {
          return ( Carbon::parse($el['Time'])->toDateString() === $eventDate->toDateString()) ;
        });
      }

      return $result;
    }

    public function show($slug)
    {
      $slug = ucwords(str_replace('-', ' ', $slug));
      $result = $this->events;

      $result = array_filter($result, function($el) use ($slug) {
        return ( $el['Title'] === $slug) ;
      });

      return collect($result)->first();
    }
}
