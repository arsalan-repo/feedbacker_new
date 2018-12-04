<!doctype html>
<html lang="en">

@include('admin.layouts.head')

<body>


<div class="wrapper" id="dashboardApp" ng-controller="dashboardAppController" ng-cloak="" >

    @include('admin.layouts.side-nav')

    <!-- Page Content  -->
    <div class="content" ng-switch="viewName.name" >

    <!--navbar-->
        @include('admin.layouts.navbar')
        @yield('content')
    </div>

        @include('admin.layouts.scripts')
</div>





</body>

</html>
