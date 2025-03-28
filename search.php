<?php
if (isset($_GET['query'])) {
    $query = $_GET['query']; // No sanitization
    echo "<h3>Results for: $query</h3>";
}
?>
<form method="GET">
    <input type="text" name="query" placeholder="Search...">
    <button type="submit">Search</button>
</form>

