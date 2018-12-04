@extends('layout.main')

@section('style')
    <link rel="stylesheet" href="/css/emojis.css">
    <link rel="stylesheet" href="/css/viewSurvey.css">

@endsection()

@section('content')



    <div class="container card-wrapper rtl py-5" id="viewSurveyApp" ng-app="viewSurveyApp"
         ng-controller="viewSurveyController" ng-init="getSurvey()">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Launch demo modal
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Your Feed Back</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <hr class="hr-red">
                    <hr class="hr-gray">
                    <div class="modal-body">
                        <div class="row" ng-repeat="question in survey.questions">
                            <div class="col-12">

                                <!--mcq-->
                                <div class="row" ng-if="question.type == 'mcq'">
                                    <div class="col-12">

                                        <div>@{{ question.question_title }}</div>
                                        <span ng-repeat="option in question.options track by $index">

                                            <input type="radio" class="radio-btn" ng-model="surveyResult.mcq"
                                                   value="@{{ option }}">
                                             @{{ option }}
                                        </span>

                                    </div>

                                </div>
                                <!--mcq-->

                                <!--single answer-->
                                <div class="row" ng-if="question.type == 'single'">
                                    <div class="col-12">
                                        <div>@{{ question.question_title }}</div>
                                        <textarea type="text" class="form-control" ng-model="surveyResult.single"></textarea>
                                    </div>
                                </div>
                                <!--single answer-->

                                <!-- imojis-->
                                <div class="row mt-2">
                                    <div class="col-12" ng-if="question.type == 'emoji'">
                                        <div>@{{ question.question_title }}</div>

                                    <div class="row mt-3" id="emojisSection">
                                        <div class="col-2">
                                            <label for="happy" ng-click="selectEmoji($event)">  <img class="img-fluid" src="/svg/happiness.svg"></label>

                                            <input  type="radio" ng-model="surveyResult.emoji" id= "happy" value="happy">
                                        </div>
                                        <div class="col-2">
                                            <label for="sad" ng-click="selectEmoji($event)">  <img class="img-fluid" src="/svg/sad.svg"></label>
                                            <input type="radio" ng-model="surveyResult.emoji" id="sad" value="sad">
                                        </div>

                                        <div class="col-2">
                                            <label for="angry" ng-click="selectEmoji($event)">  <img class="img-fluid" src="/svg/angry.svg"></label>
                                            <input type="radio" ng-model="surveyResult.emoji" id="angry" value="angry">
                                        </div>

                                        <div class="col-2">

                                            <label for="embarrassed" ng-click="selectEmoji($event)">  <img class="img-fluid" src="/svg/embarrassed.svg"></label>
                                            <input type="radio" ng-model="surveyResult.emoji" id="embarrassed" value="bad">
                                        </div>

                                        <div class="col-2">
                                            <label for="good" ng-click="selectEmoji($event)">  <img class="img-fluid" src="/svg/happiness.svg"></label>
                                            <input type="radio" ng-model="surveyResult.emoji" id="good" value="good">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- imojis-->

                                <!--stars-->
                                <div class="row" ng-if="question.type == 'stars'">
                                    <div class="col-12">
                                        <div>@{{ question.question_title }}</div>


                                        <div class="row" id="starSection">

                                            <div class="rating-control m-auto">

                                                <input type="radio" ng-model="surveyResult.stars" value="5" id="id_rating_0" required="">
                                                <label for="id_rating_0" class="mr-4 ml-4">5</label>

                                                <input type="radio" ng-model="surveyResult.stars" value="4" id="id_rating_1" required="">
                                                <label for="id_rating_1" class="mr-4 ml-4">4</label>

                                                <input type="radio" ng-model="surveyResult.stars" value="3" id="id_rating_2" required="" >
                                                <label for="id_rating_2" class="mr-4 ml-4">3</label>

                                                <input type="radio" ng-model="surveyResult.stars" value="2" id="id_rating_3" required="">
                                                <label for="id_rating_3" class="mr-4 ml-4">2</label>

                                                <input type="radio" ng-model="surveyResult.stars" value="1" id="id_rating_4" required="">
                                                <label for="id_rating_4" class="mr-4 ml-4">1</label>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!--stars-->

                                <!--one to ten-->
                                <div class="row mr-auto ml-auto mt-4" ng-if="question.type == 'onetoten'">
                                    <div>@{{ question.question_title }}</div>
                                    <div class="col-12 m-auto p-0">

                                        <button class="btn btn-scale btn-scale-desc-10 "  ng-repeat="square in oneToTen track by $index"  ng-model="surveyResult.oneToTen" ng-style="oneToTenColor($index+1)" ng-click="addRate($index+1)">@{{ $index+1 }}</button>
                                    </div>

                                </div>
                                <!--one to ten-->



                            </div>
                        </div>
                    </div>
                    <button ng-click="saveSurveyResult()" class="btn btn-primary btn-submit">Submit</button>

                    <div id="branding" style="display:block!important;text-align:center;" class="anim">
                        <a href="https://feedbacker.me/" target="_blank" class="center-branding"
                           style="display:inline-block!important;color: #0c2847">
	    <span class="brand-text" style="display:inline-block!important;">
	        Powered by	    </span>
                            <span class="brand-logo" style="display:inline-block!important;">
	    	<img src="https://d1f8jwm5uy46l.cloudfront.net/uploads/feedback/files/6e346b8088ecba3f6376250694c652cb.png"
                 alt="Mopinion Logo" style="display:inline-block!important;"><span
                                    style="display:inline-block!important;">Feedbacker</span>
	    </span>
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>


    </div>
@endsection


@section('script')

    <script src="/js/jk-rating-stars.min.js"></script>
    <script src="/js/viewSurvey.js"></script>
    <script src="/js/emojis.js"></script>
@endsection

