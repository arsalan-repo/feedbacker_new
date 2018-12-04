<!-- Sidebar  -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>feedback</h3>
        <strong>JMC</strong>
    </div>

    <ul class="list-unstyled components">



        <li ng-click="viewName.name = 'surveys' ">
            <a href="#usersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users"></i>
                Surveys
            </a>
            <ul class="collapse list-unstyled" id="usersSubmenu">
                <li  ng-click="viewName.surveys = 'all-surveys' " >
                    <a href="" >All Surveys</a>
                </li>
                <li ng-click="viewName.surveys = 'create-surveys'">
                    <a href="#">Add Survey</a>
                </li>

                <li ng-click="viewName.surveys = 'survey-results'">
                    <a href="#">show Results</a>
                </li>

            </ul>
        </li>





    </ul>


</nav>
