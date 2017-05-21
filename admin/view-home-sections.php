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
            LEFT JOIN HomeInfoLines hil ON his.HomeInfoSectionID = hil.HomeInfoSectionID
            LEFT JOIN HomeInfoItems hii ON hil.HomeInfoLineID = hii.HomeInfoLineID
        ORDER BY SectionSortOrder, hil.SortOrder, ItemSortOrder';
    $sectionStmt = $pdo->prepare($query);
    $sectionStmt->execute([]); // will we ever need params here?
    $sections = $sectionStmt->fetchAll();
    $lastSectionID = -1;
    $lastLineID = -1;
?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href=".">Back</a></p>


<div id="sections-div">
    <div class="section" id="create">
        <h5>Create Section</h5>
        <form action="ajax/save-section-edits.php?type=create" method="post">
            <div class="row">
                <div class="input-field col s6 m4">
                    <input type="text" id="section-name" name="section-name" value="" required/>
                    <label for="section-name">Section Name</label>
                </div>
                <div class="input-field col s6 m4">
                    <button class="inline btn waves-effect waves-light submit" type="submit" name="action">Create Section</button>
                </div>
            </div>
        </form>
    </div>
    <div class="divider"></div>
    <div class="section" id="section-list">
        <h5>Modify Sections</h5>
        <?php 
            // TODO: refactor to function for home page~
            $isAdminPage = TRUE; // for eventual function
            foreach ($sections as $section) { 
                $sectionID = $section["SectionID"];
                $lineID = $section["LineID"];
                if ($lastSectionID !== $sectionID) {
                    if ($lastSectionID !== -1) {
                        echo "</ul>";
                    }
                    $lastSectionID = $sectionID;
                    echo "<h5>" . $section["SectionName"] . "</h5>";
                    if ($isAdminPage) {
                        echo "<div class='section-buttons'>";
                            echo "<div class='row'>";
                                echo "<a class='add waves-effect waves-teal btn-flat teal-text col s12 m2 center-align' href='create-edit-section.php?type=update&id=$sectionID'>Edit Section Name</a>";
                                echo "<a class='add waves-effect waves-teal btn-flat teal-text col s12 m2 center-align' href='view-home-section-items.php?sectionID=$sectionID'>Edit Line Items</a>";
                                echo "<a class='add waves-effect waves-teal btn-flat red white-text col s12 m2 center-align' href='delete-section.php?id=$sectionID'>Delete Section</a>";
                            echo "</div>";
                        echo "</div>";
                    }
                    echo "<ul class='section-items'>";
                }
                if ($section["Text"] != NULL) {
                    $isFirstLineItem = FALSE;
                    if ($lastLineID !== $lineID) {
                        $isFirstLineItem = TRUE;
                        if ($lastLineID !== -1) {
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
                else {
                    // make sure we finish off the last line item
                    if ($lastLineID !== -1) {
                        echo "</li>";
                    }
                    $lastLineID = -1;
                }
            }
            if ($lastLineID !== -1) {
                echo "</li>";
            }
            if ($lastSectionID !== -1) {
                echo "</ul>";
            }
        ?>
    </div>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>