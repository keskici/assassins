<?php

include("../includes/config.php"); 
extract($_POST); 

$user = query("SELECT * FROM users WHERE userid=?", $userid); 
$user = $user[0]; 

if ($hash != $user["password"]) {
	die("GTFO"); 
} else if ($user["dead"] == 1) {
	die("you dead doe..."); 
}
if($action == "dead") {
	mail(ADMIN_EMAIL, "Death Report", $user["name"] . " died..."); 
	query("UPDATE users SET dead=1 WHERE userid= ? ", $userid);
	$assassin = query("SELECT * FROM users WHERE to_kill=? AND dead != 1", $userid);
	$assassin = $assassin[0]; 
	query("INSERT INTO killstory (killer, dead, is_kill_story, story) VALUES (?,?,?,?)", $assassin["userid"], $userid, 0, $story); 
	if ( $assassin["killed"] == 1) {
		query("UPDATE users SET killed=0, to_kill=? WHERE userid=?", $user["to_kill"], $assassin["userid"]); 
	}
} else if ($action == "kill") {
	mail(ADMIN_EMAIL, "Kill Report", $user["name"] . " killed..."); 
	query("UPDATE users SET killed=1 WHERE userid=?", $userid); 
	$target = query("SELECT * FROM users WHERE userid=?", $user["to_kill"]); 
	$target = $target[0]; 
	query("INSERT INTO killstory (killer, dead, is_kill_story, story) VALUES (?,?,?,?)", $userid, $target["userid"], 1, $story); 
	if ($target["dead"] == 1) {
		query("UPDATE users SET killed=0, to_kill=? WHERE userid=?", $target["to_kill"], $userid); 
	}
}



?>
