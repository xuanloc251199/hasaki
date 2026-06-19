<?php
// Root entry: forward to /public
header('Location: ' . rtrim($_SERVER['REQUEST_URI'], '/') . '/public/');
exit;
