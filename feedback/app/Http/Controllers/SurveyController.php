<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\Survey;
use App\Surveyor;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SurveyController extends Controller
{

    public function allResources(){
        return Survey::with(['questions.answers','surveyors'])->get();
    }

    public function showSurveyResult($surveyId){

        $survey = Survey::with(['surveyors.answers','questions'])->find($surveyId);


        return $survey;
    }

    public function store(Request $request){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 30; $i++) {
            $randstring.= $characters[rand(0, strlen($characters)-1)];
        }

        $url = $randstring;
        $survey = Survey::create([
            'title'=> $request->title,
            'url'=>$url
        ]);


        foreach($request->questions as $question){

          if($question['type'] == "mcq"){
              $temp = [];
              foreach($question['options'] as $index=> $option){
                 $temp[ 'option'.($index+1)] = $option;
              }

              $temp['type'] = $question['type'];
              $temp['survey_id'] = $survey->id;
              $temp['question_title'] = $question['title'];


                  $q = Question::create($temp);

                 // $survey->questions()->attach($q->id);

          }else{
              $q = Question::create([
                  'question_title' => $question['title'],
                  'type' => $question['type'],
                  'survey_id'=>$survey->id
              ]);

             // $survey->questions()->attach($q->id);
          }



        }

    }

    public function viewSurvey($url){
       return view('viewSurvey');
    }

    public function getSurvey(Request $request){
        $url = $request->url;

        $options = [];

        $survey = Survey::with(['questions'])->where('url','=',$url)->get()->toArray()[0];



        for($i= 0 ;$i<sizeof($survey['questions']) ; $i++){
            $survey['questions'][$i]['options'] = [];

            for($j = 0;$j<sizeof($survey['questions']);$j++){
                if($survey['questions'][$j]['option' . ($i+1)] !== null){
                    array_push( $survey['questions'][$j]['options'],
                        $survey['questions'][$j]['option' . ($i+1)]);

                }
            }
        }



        return json_encode($survey);
    }

    public function saveSurveyResult(Request $request){

        //create a surveyor
        $surveyor = new Surveyor();
        $surveyor->ip = $request->ip();
        $surveyor->browser = $request->userAgent();
        $surveyor->save();
        $surveyId  = $request->all()[sizeof($request->all())-1];
        //save the answer for each question in answers table

        $data = $request->all();
        for($i = 0;$i<sizeof($data)-1; $i++){
                $answer = new Answer();
                $answer->question_id = $data[$i]['id'];
                $answer->surveyor_id =  $surveyor->id;
                $answer->answer = $data[$i]['answer'];
                $answer->survey_id = $surveyId;
                $answer->save();
        }

        $surveyor->surveys()->attach($surveyId);




    }
}
