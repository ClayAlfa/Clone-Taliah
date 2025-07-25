<?php
require_once 'config.php';

echo "<h2>Test Konfigurasi Path</h2>";
echo "<p>SITE_URL: " . SITE_URL . "</p>";
echo "<p>CSS Path: " . SITE_URL . "/assets/css/style.css</p>";
echo "<p>JS Path: " . SITE_URL . "/assets/js/main.js</p>";

echo "<h3>Test File Exists:</h3>";
echo "<p>CSS exists: " . (file_exists('assets/css/style.css') ? 'YES' : 'NO') . "</p>";
echo "<p>JS exists: " . (file_exists('assets/js/main.js') ? 'YES' : 'NO') . "</p>";

echo "<h3>Test Links:</h3>";
echo '<p><a href="' . SITE_URL . '/assets/css/style.css" target="_blank">Test CSS Link</a></p>';
echo '<p><a href="' . SITE_URL . '/assets/js/main.js" target="_blank">Test JS Link</a></p>';
?>
