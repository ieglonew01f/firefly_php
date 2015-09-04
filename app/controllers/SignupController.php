<?php

class SignupController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Chat Controller
	| Author: lonew01f
	| First commit: 29/8/2015 9:44 PM
	| Describtion: Contains everything related to signup
	|--------------------------------------------------------------------------
	|
	*/


	//index
	public function index($code){
		
		return View::make('pages.signup');
	}
}
