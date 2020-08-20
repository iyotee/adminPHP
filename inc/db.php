<?php

$pdo = new pdo('mysql:dbname=tuto;host=localhost','root','mysql-T00rT00r');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);