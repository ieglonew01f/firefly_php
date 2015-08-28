<?php

class Jenkins extends Eloquent {
	protected $fillable = ['question_title', 'question'];
	public $timestamps  = false;
	protected $table    = 'jenkins_stack';

	public function users()
	{
		return $this->belongs_to('user');
	}
	
	//add data to db 
	public function add_jenkins($input){
		
		$jenkins = $this::where('question_title', $input['question_title'])->first();
			
			if($jenkins) {
			return 0; 
		}
		else {
		

			//saving new question
			$this::create($input);
			$question_title = $this::where('question', $input['question']) ->first();;
			if($question_title){
				$session_data  = array(
					"question_title"  	=> $question_title -> question_title,
					"question"    		=> $question_title -> question,
				);

				Session::put($session_data);

								
				return View::make('pages.thankyou');
			}
			else
				return 0;		//if insertion into DB failed
		}
	}



	}


?>