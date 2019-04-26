<nav id="books-main-menu" class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><img src="/images/books_logo.png" class="img-rounded" width="50"
                                                  height="50"/></a>
        </div>
        <?php echo $menu ?>
        <ul class="nav navbar-nav navbar-right">
            <li class="logged-in"><a href="/admin/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
            <li class="welcome-nav">
                <a href="/user"><span class="glyphicon glyphicon-user"></span> Welcome <?= isset($_SESSION['user']) ? $_SESSION['user']->getUsername() : "" ?> !
                </a></li>
            <li class="logged-in-user , logged-out"><a href="/logoutUser"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
            <li class="logged-out-user , logged-out"><a href="/loginUser"><span class="glyphicon glyphicon-log-in"></span> Sign In </a></li>
        </ul>
    </div>
</nav>