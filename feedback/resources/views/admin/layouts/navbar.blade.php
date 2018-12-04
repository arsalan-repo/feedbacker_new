<nav class="navbar navbar-expand-lg navbar-light bg-light" id="">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>

        </button>
        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#admin-navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
        </button>

        <div class="collapse navbar-collapse" id="admin-navbar">
            <ul class="nav navbar-nav ml-auto">


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" id="admin-navbar-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-shield"></i>
                        @{{ admin.name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="admin-navbar-dropdown">

                        <div class="dropdown-divider"></div>
                        <form method="post" action="/admin/dashboard">
                            {{csrf_field()}}
                            <input type="submit" value="Log out" class="btn btn-link w-100">
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>

</script>
