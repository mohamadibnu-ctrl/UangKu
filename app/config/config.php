<?php

// Access Environment Variables loaded in init.php
define('BASEURL', getenv('BASEURL') ?: 'http://localhost/PROJECT/UangKu/public');

// DB Constants
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'uangku');

// AI Configuration
define('AI_API_KEY', getenv('AI_API_KEY') ?: '');
