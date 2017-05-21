<?php
    require_once(dirname(__FILE__)."/init-admin.php");
    if ($isClubAdmin) {
        header("Location: index.php");
    }

    $query = '
        SELECT his.HomeInfoSectionID AS SectionID, his.Name AS SectionName, his.SortOrder AS SectionSortOrder,
            hil.HomeInfoLineID AS LineID,
            hii.HomeInfoItemID, hii.Text, hii.IsLink, hii.URL, hii.SortOrder AS ItemSortOrder
        FROM HomeInfoSections his 
            JOIN HomeInfoLines hil ON his.HomeInfoSectionID = hil.HomeInfoSectionID
            JOIN HomeInfoItems hii ON hil.HomeInfoLineID = hii.HomeInfoLineID
        ORDER BY SectionSortOrder, hil.SortOrder, ItemSortOrder';
    $sectionStmt = $pdo->prepare($query);
    $sectionStmt->execute([]); // will we ever need params here?
    $sections = $sectionStmt->fetchAll();
    $lastSectionID = -1;
    $lastLineID = -1;
?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href=".">Back</a></p>


<div id="users-div">
    <?php if ($isClubAdmin) { ?>
        <h5><?= $_SESSION["ClubName"] ?></h5>
    <?php } ?>
    <div id="create">
        <a class="waves-effect waves-light btn" href="create-edit-section.php?type=create">Add Section</a>
    </div>
    <?php 
        foreach ($sections as $section) { 
            $sectionID = $section["SectionID"];
            $lineID = $section["LineID"];
            if ($lastSectionID !== $sectionID) {
                if ($lastSectionID !== -1) {
                    echo "</ul>";
                }
                $lastSectionID = $sectionID;
                echo "<h5>" . $section["SectionName"] . "</h5>";
                echo "<ul>";
            }
            $isFirstLineItem = FALSE;
            if ($lastLineID !== $lineID) {
                $isFirstLineItem = TRUE;
                if ($lastSectionID !== -1) {
                    echo "</li>";
                }
                $lastLineID = $lineID;
                echo "<li>";
            }
            if (!$isFirstLineItem) {
                echo " - ";
            }
            if ($section["IsLink"]) {
                $url = $section["URL"];
                if (strpos($url, 'http://') === false && strpos($url, 'https://') === false) {
                    $url = "http://" . $url;
                }
                echo "<a href=\"" . $url . "\">" . $section["Text"] . "</a>";
            }
            else {
                echo $section["Text"];
            }
        }
    ?>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>