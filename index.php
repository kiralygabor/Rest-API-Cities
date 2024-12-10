<?php
 /**
 * @author Nagy Gergely, Király Gábor 
 **/
session_start();
include './vendor/autoload.php';

use App\Html\Request;

Request::handle();
