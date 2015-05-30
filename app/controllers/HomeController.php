<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Home page controller
	| Author: lonew01f
	| First commit: 5/9/2015 06:47 PM
	| Describtion: Home page controller handlers all the aspect related to
	| home page of the user
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		$feeds = new feeds;
		$data  = array("feeds" => $feeds -> get_feeds("0"));

		return View::make('pages.home', $data);
	}

}
