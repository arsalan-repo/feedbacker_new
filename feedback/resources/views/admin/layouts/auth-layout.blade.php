<html>
    @include('admin.layouts.head')

    <body>
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    @yield('auth-content')
                </div>
            </div>
        </div>
    </body>
</html>