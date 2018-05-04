<?php
    require_once(dirname(__FILE__)."/init-admin.php");
    
    $title = 'Delete Book';

    if (!$isWebAdmin) {
        header("Location: $basePath/index.php");
        die();
    }

    $bookID = $_GET["id"];
    $query = '
        SELECT Name, Year 
        FROM Books b JOIN Years y ON b.YearID = y.YearID
        WHERE BookID = ?';
    $stmt = $pdo->prepare($query);
    $stmt->execute([$bookID]);
    $book = $stmt->fetch();

    if ($book == NULL) {
        die("Invalid book id");
    }
    
    if ($isPostRequest && $bookID == $_POST["book-id"]) {
        $query = 'DELETE FROM Books WHERE BookID = ?';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$bookID]);
        header("Location: view-books.php");
        die();
    }

?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a class="btn-flat blue-text waves-effect waves-blue no-uppercase" href="./view-books.php">Back</a></p>

<div id="delete-book">
    <h4> Are you sure you want to remove the book <?= $book["Name"] ?> for the year <?= $book["Year"] ?> from the available Bible books list?</h4>
    <form method="post">
        <input type="hidden" name="book-id" value="<?= $bookID ?>"/>
        <button class="btn waves-effect waves-light submit red white-text" type="submit" name="action">Remove Book</button>
    </form>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>