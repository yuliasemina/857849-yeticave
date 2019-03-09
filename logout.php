<?php	
session_start();
require 'db.php';								

unset($_SESSION['user']);
header("Location: /index.php");	
					