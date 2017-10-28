<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <router-link to="/" tag="li"><a>Home <span class="sr-only">(current)</span></a></router-link>
                <router-link to="/films" tag="li"><a>Films</a></router-link>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <router-link v-show="!isLogin" to="/login" tag="li"><a>Login</a></router-link>
                <router-link v-show="!isLogin" to="/register" tag="li"><a>Register</a></router-link>

                <li class="dropdown" v-show="isLogin">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        @{{ userStorage ? userStorage : 'Welcome' }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" @click.prevent="logoutUser">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
</nav>

<router-view></router-view>