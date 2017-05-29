<?php
    require_once(dirname(__FILE__)."/init-admin.php");

    if ($isClubAdmin) {
        header("Location: index.php");
    }
    $query = 'SELECT ClubID, Name FROM Clubs ORDER BY Name';
    $stmt = $pdo->prepare($query);
    $stmt->execute([]);
    $clubs = $stmt->fetchAll();
?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href=".">Back</a></p>


<div id="users-div">
    <div class="section" id="create">
        <h5>Create Club</h5>
        <form action="ajax/save-club-edits.php?type=create" method="post">
            <div class="row">
                <div class="input-field col s6 m4">
                    <input type="text" id="club-name" name="club-name" value="" required/>
                    <label for="section-name">Club Name</label>
                </div>
                <div class="input-field col s6 m4">
                    <button class="inline btn waves-effect waves-light submit" type="submit" name="action">Create Club</button>
                </div>
            </div>
        </form>
    </div>
    <div class="divider"></div>
    <table class="striped">
        <thead>
            <tr>
                <th>Club Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clubs as $club) { ?>
                    <tr>
                        <td><?= $club["Name"] ?></td>
                        <td><a href="create-edit-club.php?type=update&id=<?=$club['ClubID'] ?>">Edit Club</a></td>
                        <td><a href="delete-club.php?id=<?=$club['ClubID'] ?>">Delete Club</a></td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>