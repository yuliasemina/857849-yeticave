<?php				

require 'db.php';				
require 'data.php';				
require 'functions.php';				
session_start();				

unset($_SESSION['user']);
header("Location: /index.php");	
					