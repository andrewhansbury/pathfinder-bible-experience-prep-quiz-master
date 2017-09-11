<?php

// TODO:
// Error messages if server fails

    require_once(dirname(__FILE__)."/init-admin.php");

    if ($isClubAdmin) {
        die("invalid user type");
    }

    if ($_GET["type"] == "update") {
        $query = '
            SELECT WordID, Word
            FROM BlankableWords
            WHERE WordID = ?';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_GET["id"]]);
        $word = $stmt->fetch();
        if ($word == NULL) {
            die("invalid word id");
        }
        $wordID = $_GET["id"];
        $word = $word["Word"];
        $postType = "update";
        $titleString = "Edit";
    }
    else {
        $wordID = "";
        $word = "";
        $postType = "create";
        $titleString = "Create";
    }

?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href="./view-non-blankable-words.php">Back</a></p>

<h4><?= $titleString ?> Non-Blankable Word</h4>

<div id="edit-word">
    <form action="ajax/save-blankable-word-edits.php?type=<?= $postType ?>" method="post">
        <input type="hidden" name="word-id" value="<?= $wordID ?>"/>
        <div class="row">
            <div class="input-field col s12 m4">
                <input type="text" id="blankable-word" name="blankable-word" value="<?= $word ?>" required data-length="150"/>
                <label for="blankable-word">Word</label>
            </div>
        </div>
        <button class="btn waves-effect waves-light submit" type="submit" name="action">Save</button>
    </form>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>