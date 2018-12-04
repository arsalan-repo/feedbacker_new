
dashboardApp.controller('surveysController',function($scope,$http){
    console.log("survey controller");

    $scope.allSurveys = [];
    $scope.survey  = {
        'title':"",
        'questions':[{
            'title':'',
            'type':[],
            'options':[],
            'single':''
        }],
    };
    $scope.surveyResult = {};

    $scope.addNewQuestion = function (){
        $scope.survey.questions.push({});
    };

    $scope.deleteQuestion = function (question) {

        if($scope.survey.questions.length > 1)
        $scope.survey.questions.splice($scope.survey.questions.indexOf(question),1);
    };

    $scope.saveSurvey = function () {
      console.log($scope.survey);

      $http.post($scope.requestPath + "saveSurvey",$scope.survey).then(function(response){


      });


    };

    $scope.getAllSurveys = function () {
        $http.get($scope.requestPath + "getAllSurveys").then(function (response) {
            $scope.allSurveys = response.data;
        })
    };

    $scope.viewResult = function (id) {
        $http.get($scope.requestPath + "survey/result/" + id).then(function (response) {
            $scope.surveyResult = response.data;

            console.log($scope.surveyResult);
        })
    }














});



