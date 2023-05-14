<?php
require "assets/php/header.php";


unset($_SESSION["user"]);
unset($_SESSION["createfiche"]);

header("Location: login.php");