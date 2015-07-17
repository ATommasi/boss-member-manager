<link rel="stylesheet" type="text/css" href="css/wvwscore.css">
<?php


require_once ("../helpers/gw2api/Service.php");


// Instantiate a new service object and specify cache path and TTL settings
$gw2 = new PhpGw2Api\Service('../cache', 5);

// Set how to return the JSON
$gw2->returnAssoc(true);

$world_home_id = 1009; #-- fort aspenwood


$matches = $gw2->getMatches();

#// find the match for our world
foreach ($matches['wvw_matches'] as $match) {
   if ($match['red_world_id'] == $world_home_id || $match['green_world_id'] == $world_home_id || $match['blue_world_id'] == $world_home_id ) {
      break;
   }
}

echo "<div>";

$scores = $gw2->getMatchDetails(array('match_id' => $match['wvw_match_id']));

// Consult the name of the world through the ID (temporal API Anet crash)
$world_names = $gw2->getWorldNames();

foreach($world_names as $world) {
	if($world['id'] == $match['red_world_id']) { $red_world_name = $world['name']; }
	if($world['id'] == $match['blue_world_id']) { $blue_world_name = $world['name']; }
	if($world['id'] == $match['green_world_id']) { $green_world_name = $world['name']; }

	if($match['red_world_id'] == $world_home_id) { $red_world_home = "home-world"; } else { $red_world_home = ""; }
	if($match['blue_world_id'] == $world_home_id) { $blue_world_home = "home-world"; } else { $blue_world_home = ""; }
	if($match['green_world_id'] == $world_home_id) { $green_world_home = "home-world"; } else { $green_world_home = ""; }
}

// Ordered all results
$match_sort = array(
	"red" => array("world_score" => $scores['scores'][0], "world_color" => "red", "world_name" => $red_world_name, "world_home" => $red_world_home),
	"blue" => array("world_score" => $scores['scores'][1], "world_color" => "blue", "world_name" => $blue_world_name, "world_home" => $blue_world_home),
	"green" => array("world_score" => $scores['scores'][2], "world_color" => "green", "world_name" => $green_world_name, "world_home" => $green_world_home)
);

// Ordered from highest to lowest rated
arsort($match_sort);

echo '<div class="gw2-wvw-matchups">';

// Print the all results and format them
?><ul class="gw2-matchups"><?php
foreach ($match_sort as $match_detail) {
	?>
	<li class="match world-<?php echo $match_detail['world_color']; ?>">
		<span class="world <?php echo $match_detail['world_home']; ?>"><?php echo $match_detail['world_name']; ?></span>
		<span class="points"><?php echo number_format($match_detail['world_score']); ?></span>
	</li>
	<?php
}
?></ul><?php




/**
 * Show objectives table
 */

   $resOb = $gw2->getObjectiveNames();

   #// reformat for easy look up
   foreach ($resOb as $o) {
      $objNames[$o['id']] = $o['name'];
   }


	$red_camp = 0; $blue_camp = 0; $green_camp = 0;
	$red_tower = 0; $blue_tower = 0; $green_tower = 0;
	$red_keep = 0; $blue_keep = 0; $green_keep = 0;
	$red_castle = 0; $blue_castle = 0; $green_castle = 0;

	foreach($scores['maps'] as $v) {
		foreach($v['objectives'] as $z) {
			$objective_id = $z['id'];
			$objective_owner = $z['owner'];
			$objective_name	= $objNames[$objective_id];
			if($objective_id < 62) {
				if($objective_owner == "Red") {
					if($objective_name == "Tower") { $red_tower++; }
					elseif($objective_name == "Keep") { $red_keep++; }
					elseif($objective_name == "Castle") { $red_castle++; }
					else { $red_camp++; }
				}
				if($objective_owner == "Green") {
					if($objective_name == "Tower") { $green_tower++; }
					elseif($objective_name == "Keep") { $green_keep++; }
					elseif($objective_name == "Castle") { $green_castle++; }
					else { $green_camp++; }
				}
				if($objective_owner == "Blue") {
					if($objective_name == "Tower") { $blue_tower++; }
					elseif($objective_name == "Keep") { $blue_keep++; }
					elseif($objective_name == "Castle") { $blue_castle++; }
					else { $blue_camp++; }
				}
			}
		}
	}



	// Ordered by the score all results
	$objectives_sort = array(
		"red" => array("world_score" => $scores['scores'][0], "world_color" => "red", "camp" => $red_camp, "tower" => $red_tower, "keep" => $red_keep, "castle" => $red_castle, "world_home" => $red_world_home),
		"blue" => array("world_score" => $scores['scores'][1], "world_color" => "blue", "camp" => $blue_camp, "tower" => $blue_tower, "keep" => $blue_keep, "castle" => $blue_castle, "world_home" => $blue_world_home),
		"green" => array("world_score" => $scores['scores'][2], "world_color" => "green", "camp" => $green_camp, "tower" => $green_tower, "keep" => $green_keep, "castle" => $green_castle, "world_home" => $green_world_home)
	);
	arsort($objectives_sort);

	// Print and formating the objectives result
	?>
	<ul class="gw2-objectives">
		<li class="leyend">
			<span class="world">World</span>
			<span class="camp" title="Camp">Camp</span>
			<span class="tower" title="Tower">Tower</span>
			<span class="keep" title="Keep">Keep</span>
			<span class="castle" title="Castle">Castle</span>
		</li>
	<?php
	foreach ($objectives_sort as $objective) {
		?>
      <li class="objective <?=$objective['world-color']?> <?php echo $objective['world_home']; ?>">
			<span class="world world-<?php echo $objective['world_color']; ?>"><?php echo $objective['world_color']; ?></span>
			<span><?php echo $objective['camp']; ?></span>
			<span><?php echo $objective['tower']; ?></span>
			<span><?php echo $objective['keep']; ?></span>
			<span><?php echo $objective['castle']; ?></span>
		</li>
		<?php
	}
	?></ul><?php



echo '</div>';

?>
