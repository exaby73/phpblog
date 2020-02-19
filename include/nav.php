<ul class="nav d-flex justify-content-between">
    <div class="d-inline-flex flex-row align-items-center">
        <a href="<?php echo URL_HREF ?>" class="navbar-brand">phpBlog</a>
        <!-- <li class="nav-item"><a href="<?php echo URL_HREF . "/" ?>" class="nav-link active">Home</a></li>
            <li class="nav-item"><a href="<?php echo URL_HREF . "/new-post/" ?>" class="nav-link">Create Post</a></li> -->
    </div>
    <?php if (isset($_SESSION['blog_login'])) : ?>
        <div class="d-inline-flex flex-row-reverse align-items-center">
            <!-- <img class="user-profile rounded-circle" src="<?php echo URL_HREF . "/assets/images/user.png" ?>" alt="user profile picture"> -->
            <div class="dropdown show">
                <a class="" href="#" role="button" id="profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-profile rounded-circle" src="<?php echo URL_HREF . "/assets/images/user.png" ?>" alt="user profile picture">
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profile-dropdown">
                    <a href="<?php echo URL_HREF . "/create" ?>" class="dropdown-item">Create Post</a>
                    <a class="dropdown-item" href="#">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo URL_HREF . "/logout" ?>">Logout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</ul>