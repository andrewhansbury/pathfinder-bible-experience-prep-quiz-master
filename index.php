<?php
    require_once(dirname(__FILE__)."/init.php");
    $sections = load_home_sections($pdo);
?>

<?php include(dirname(__FILE__)."/header.php"); ?>

<h3>Welcome back, <?=$_SESSION["Username"]?>!</h3>

<?php if ($isGuest) { ?>
    <h4>You are currently browsing the site in guest mode. You will be unable to add, edit, or delete questions.</h4>
<?php } ?>

<div id="user-links">
    <div class="row">
        <div class="col s12 m4">
            <ul>
                <li class="home-buttons"><a class='btn waves-effect waves-light' href="view-questions.php">Questions</a></li>
                <li class="home-buttons"><a class='btn waves-effect waves-light' href="quiz-setup.php">Quiz me!</a></li>
                <li class="home-buttons"><a class='btn waves-effect waves-light' href="active-clubs.php">Clubs</a></li>
                <li class="home-buttons"><a class='btn waves-effect waves-light' href="study-guides.php">Study Guides</a></li>
            </ul>
        </div>
        <div class="col s12 m8">
            <?php output_home_sections($sections, FALSE); ?>
        </div>
    </div>
</div>

<div id="extra-home-info">
    <div class="row">
        <p class="col s12">
            Recent website updates:
        </p>
        <ul class="col s12 browser-default">
            <li>2017-11-12: Allowed flash card generation of those questions that have been recently added (within the last 1 to 31 days)</li>
            <li>2017-09-27: Added filter for Bible book/chapter or commentary volume on the questions page</li>
        </ul>
    </div>
</div>

<?php include(dirname(__FILE__)."/footer.php") ?>