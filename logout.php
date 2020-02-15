<?php

session_start();
setcookie("blog_username", "", time() - 3600, "/");
setcookie("blog_password", "", time() - 3600, "/");
session_destroy();
header("Location:login");
