<?php				

require 'db.php';				
require 'data.php';				
require 'functions.php';				
session_start();				

$_SESSION = [];
header("Location: /index.php");	
					