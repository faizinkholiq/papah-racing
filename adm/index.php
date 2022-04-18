<?php
session_start(); 
if(empty($_SESSION['id'])){ 
    header('location:login');
}else{
    header('location:main');
}
