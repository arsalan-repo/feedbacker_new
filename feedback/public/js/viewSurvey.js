var viewSurveyApp = angular.module('viewSurveyApp', []);

viewSurveyApp.controller('viewSurveyController',['$scope','$http','$element', function ($scope, $http,$element) {
    console.log("view survey us controller");


    $scope.url = {
        'url': window.location.pathname.split('/')[2]
    };
    $scope.survey = {};
    $scope.surveyResult = {};
    $scope.oneToTen = [{},{},{},{},{},{},{},{},{},{},];

    $scope.result = [];

    $scope.getSurvey = function () {
        $http.post("/view-survey/getSurvey", $scope.url).then(function (response) {
            $scope.survey = response.data;


        });
    };
    
        $scope.collectResult = function(){
        $scope.result =[];

        angular.forEach($scope.survey.questions,function (question) {


            if(question['type'] === "mcq"){
                $scope.result.push( {
                    id : question['id'],
                    answer : $scope.surveyResult['mcq']
                })
            }else if(question['type'] === 'single'){
                $scope.result.push({
                    id : question['id'],
                    answer : $scope.surveyResult['single']
                })
            }else if(question['type'] === 'emoji'){
                $scope.result.push( {
                    id : question['id'],
                    answer : $scope.surveyResult['emoji']
                })
            }else if(question['type'] === 'stars'){
                $scope.result.push( {
                    id : question['id'],
                    answer : $scope.surveyResult['stars']
                })
            }else if(question['type'] === 'onetoten'){
                $scope.result.push({
                    id : question['id'],
                    answer : $scope.surveyResult['oneToTen']
                })
            }


        });

            $scope.result.push( $scope.survey.id);

        return $scope.result;
    };

    $scope.addRate = function (number) {
        $scope.surveyResult.oneToTen = number;
    };

    $scope.saveSurveyResult = function(){
      var payload=   $scope.collectResult();



        $http.post('/view-survey/saveSurveyResult',payload).then(function (response) {
            $scope.surveyResult= {};
          window.location.href = "/view-survey/" +  window.location.pathname.split('/')[2];
        });

    };

    $scope.selectEmoji = function ($event){
     var el = $event.target;



        angular.element(el).css({
            'border-radius': '35px',
            'background': 'black',
        });
    };

    $scope.oneToTenColor = function(degree){
        var  color = '#FF'+degree+degree+degree+'6';
        if(degree >9){
            color = "#FF8886"
        }
        return {
            'background' :color
        }
    };


}]);
