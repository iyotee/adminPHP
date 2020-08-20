<?php

$pdo = new pdo('mysql:dbname=YOUR_DB_NAME;host=localhost','YOUR_DB_USESRNAME','YOUR_DB_PASSWORD');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
