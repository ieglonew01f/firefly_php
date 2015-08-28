<?php

class JenkinsController extends BaseController {

public function new_question()
    {
         return View::make('pages.content')
            -> with('title','Add new question');    
    }


public function add_question()

	{
		$input = Input::all();

		$jenkins = new jenkins;
		return $jenkins -> add_jenkins($input);
	}




}

?>