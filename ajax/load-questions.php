<?php
    session_start();

    require_once("../database.php");

    $whereClause = "";
    $isFlagged = FALSE;
    //$flaggedSelectClause = "";
    $flaggedJoinClause = "";
    //$flaggedWhereClause = "";
    $questionType = "bible-qna";
    if (isset($_POST["questionFilter"])) {
        $questionFilter = $_POST["questionFilter"];
        if ($questionFilter == "recent") {
            $eightDaysAgo = date('Y-m-d 00:00:00', strtotime('-8 days'));
            $whereClause = " WHERE DateCreated >= '" . $eightDaysAgo . "' ";
        }
        else if ($questionFilter == "flagged") {
            $isFlagged = TRUE;
            $flaggedJoinClause =  " JOIN UserFlagged uf ON q.QuestionID = uf.QuestionID ";
            $whereClause = " WHERE UserID = " . $_SESSION["UserID"];
        }
    }
    if (isset($_POST["questionType"])) {
        $questionType = $_POST["questionType"];
    }
    if ($whereClause == "") {
        $whereClause = " WHERE Type = '" . $questionType . "'";
    }
    else {
        $whereClause .= " AND Type = '" . $questionType . "'";
    }

    $pageSize = 10;
    if (isset($_POST["pageSize"])) {
        $pageSize = $_POST["pageSize"];
    }

    $pageOffset = 0;
    if (isset($_POST["pageOffset"])) {
        $pageOffset = $_POST["pageOffset"];
    }
    $selectPortion = '
        SELECT q.QuestionID, Question, Answer, NumberPoints, DateCreated,
            bStart.Name AS StartBook, cStart.Number AS StartChapter, vStart.Number AS StartVerse,
            bEnd.Name AS EndBook, cEnd.Number AS EndChapter, vEnd.Number AS EndVerse,
            Type, CommentaryVolume, CommentaryStartPage, CommentaryEndPage ';
    $fromPortion = '
        FROM Questions q 
            LEFT JOIN Verses vStart ON q.StartVerseID = vStart.VerseID
            LEFT JOIN Chapters cStart on vStart.ChapterID = cStart.ChapterID
            LEFT JOIN Books bStart ON bStart.BookID = cStart.BookID

            LEFT JOIN Verses vEnd ON q.EndVerseID = vEnd.VerseID
            LEFT JOIN Chapters cEnd on vEnd.ChapterID = cEnd.ChapterID
            LEFT JOIN Books bEnd ON bEnd.BookID = cEnd.BookID
            ' . $flaggedJoinClause . '
            ' . $whereClause . '
        ORDER BY bStart.Name, cStart.Number, vStart.Number, bEnd.Name, cEnd.Number, vEnd.Number';
    $limitClause = '
        LIMIT ' . $pageOffset . ',' . $pageSize;  
    $stmt = $pdo->query($selectPortion . $fromPortion . $limitClause);
    $questions = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT COUNT(*) AS QuestionCount " . $fromPortion);
    $row = $stmt->fetch(); 
    $totalQuestions = $row["QuestionCount"];

    $output = json_encode(array(
        "questions" => $questions,
        "totalQuestions" => $totalQuestions
    ));
    header('Content-Type: application/json; charset=utf-8');
    echo $output;

?>