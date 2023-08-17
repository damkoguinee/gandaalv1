<?php
require '_header.php';


$prodenseig=$DB->query('SELECT * from personnelresto');

foreach ($prodenseig as $value) {

	$mdp=password_hash($value->mdp, PASSWORD_DEFAULT);

	$DB->insert("UPDATE personnelresto SET mdp='{$mdp}' where id='{$value->id}'")
	;
}