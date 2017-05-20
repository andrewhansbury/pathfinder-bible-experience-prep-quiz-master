<?php
    require_once(dirname(__FILE__)."/init-admin.php");

    $stmt = $pdo->query('
        SELECT UserID, FirstName, LastName, EntryCode, c.Name AS ClubName
        FROM Users u LEFT JOIN Clubs c ON u.ClubID = c.ClubID ');

?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href=".">Back</a></p>

<div id="create">
    <a class="waves-effect waves-light btn" href="create-edit-user.php?type=create">Add User</a>
</div>

<div id="users-div">
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Entry Code</th>
                <th>Club</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch()) { ?>
                    <tr>
                        <td><?= $row["FirstName"] ?></td>
                        <td><?= $row["LastName"] ?></td>
                        <td><?= $row["EntryCode"] ?></td>
                        <td><?= $row["ClubName"] ?></td>
                        <td><a href="create-edit-user.php?type=update&id=<?=$row['UserID'] ?>">Edit User</a></td>
                        <td><?php if ($_SESSION["UserID"] != $row["UserID"]) { ?> 
                                <a href="delete-user.php?id=<?=$row['UserID'] ?>">Delete User</a>
                            <?php } ?> 
                         </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include(dirname(__FILE__)."/../footer.php"); ?>