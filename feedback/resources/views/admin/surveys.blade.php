<div class="tab-content" id="surveyApp" ng-controller="surveysController" ng-switch-when="surveys">
    <div ng-switch="viewName.surveys">
        <!--showing all surveys-->
        <div class="row" ng-switch-when="all-surveys" ng-init="getAllSurveys()">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Surveys
                    </div>

                    <div class="card-body">
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Survey Name</th>
                                <th># Of Surveyors</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            <tr ng-repeat="survey in allSurveys">
                                <td>@{{ $index + 1 }}</td>
                                <td>@{{ survey.title }}</td>
                                <td>@{{ survey.surveyors.length }}</td>
                                <td>
                                    <button class="btn btn-danger" ng-click="viewResult(survey.id)" data-toggle="modal"
                                            data-target="#surveyResult">result
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--showing all users-->
        <!-- Modal -->
        <div class="modal fade" id="surveyResult" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>browser</th>


                                <th ng-repeat="question in surveyResult.questions">@{{question.question_title}}</th>


                            </tr>
                            </thead>

                            <tbody>


                            <tr ng-repeat="surveyor in surveyResult.surveyors">
                                <td>@{{surveyor.ip}}</td>
                                <td>@{{surveyor.browser}}</td>


                                <th ng-repeat="answer in surveyor.answers">@{{answer.answer}}</th>

                            </tr>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!--adding new survey-->
        <div class="row" ng-switch-when="create-surveys">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Create New Survey
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">

                                            <label>Survey Title</label>
                                            <input type="text" class="form-control" ng-model="survey.title"/>
                                        </div>

                                        <hr>


                                    </div>


                                    <div class="form-row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    Questions
                                                </div>

                                                <div class="card-body">
                                                    <div class="mt-3"
                                                         ng-repeat="question in survey.questions track by $index">

                                                        <label>Question @{{ $index + 1 }}</label><br>
                                                        <input type="text" class="form-control"
                                                               ng-model="question.title">
                                                        <!--radio buttons-->
                                                        <div class="row">
                                                            <div class="col-12 col-md-2">
                                                                <input type="radio" class="radio-btn"
                                                                       ng-model="question.type" value="mcq"> MCQ
                                                            </div>

                                                            <div class="col-12 col-md-2">

                                                                <input type="radio" class="radio-btn"
                                                                       ng-model="question.type" value="single">Single
                                                                Answer
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <input type="radio" class="radio-btn"
                                                                       ng-model="question.type" value="emoji">Emoji
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <input type="radio" class="radio-btn"
                                                                       ng-model="question.type" value="stars">Stars
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <input type="radio" class="radio-btn"
                                                                       ng-model="question.type" value="onetoten">1-10
                                                            </div>


                                                        </div>
                                                        <!--radio buttons-->

                                                        <div class="row mt-2" ng-show="question.type == 'mcq'">

                                                            <div class="col-12 col-md-2">

                                                                <input type="text" ng-model="question.options[0]"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-12 col-md-2">

                                                                <input type="text" ng-model="question.options[1]"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-12 col-md-2">

                                                                <input type="text" ng-model="question.options[2]"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-12 col-md-2">

                                                                <input type="text" ng-model="question.options[3]"
                                                                       class="form-control">
                                                            </div>
                                                            <div class="col-12 col-md-2">

                                                                <input type="text" ng-model="question.options[4]"
                                                                       class="form-control">
                                                            </div>
                                                        </div>


                                                        <hr>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-md-4 m-auto">
                                                            <button ng-click="addNewQuestion()"
                                                                    class="btn btn-primary w-100">New Question +
                                                            </button>

                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="">
                                                <button class="btn btn-success" ng-click="saveSurvey()">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--adding new user form -->
        </div>

        <div class="row" ng-switch-when="survey-results">

            <!--adding new user form -->
        </div>
    </div>

</div>


