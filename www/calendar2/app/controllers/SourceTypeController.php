<?php

class SourceTypeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| SourceType Controller
	|--------------------------------------------------------------------------
	|
	| Route::get('sourcetypes', 'SourceTypeController@<method name>');
	|
	*/
	
	public function postConfigViewFor($sourceType_id) {
		$source = SourceType::find($sourceType_id);
		$data = array('config'=>array());
		if($source){			
			if( Input::has('config') ){				
				$data['config'] = Input::get('config');
			}
			return Response::json( array('status'=>'success', 'html'=>View::make('sourceTypeConfigs/'.$source->type, $data)->__toString() ) );			
		}else{
			return Response::json( array('status'=>'fail') );
		}
	}
}