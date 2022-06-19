<?php

session_start();
session_unset();
session_destroy();

//gaat terug naar index.php
header("location: index.php?error=none");