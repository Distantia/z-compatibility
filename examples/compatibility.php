<?php
use Distantia\ZCompatibility\ZCompatibility;

require_once __DIR__.'/../vendor/autoload.php';

$ZCompatibility = new ZCompatibility();

$requirements = $ZCompatibility->getRequirements();
$recommendations = $ZCompatibility->getRecommendations();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Z Compatibility</title>
</head>
<body>
<h1>Z Compatibility</h1>
<h2>Requirements</h2>
<?php
if ($requirements) {
    echo '<ul id="requirements">';

    foreach ($requirements as $requirement) {
        echo '<li>'.$requirement.'</li>';
    }

    echo '</ul>';
}
?>
<h2>Recommendations</h2>
<?php
if ($recommendations) {
    echo '<ul id="recommendations">';

    foreach ($recommendations as $recommendation) {
        echo '<li>'.$recommendation.'</li>';
    }

    echo '</ul>';
}
?>
</body>
</html>
