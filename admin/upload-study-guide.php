<?php
    require_once(dirname(__FILE__).'/init-admin.php');
?>

<?php include(dirname(__FILE__)."/../header.php"); ?>

<p><a href="./manage-study-guides.php">Back</a></p>

<h2>Upload Study Guide</h2>

<div id="upload-study-guide">
    <p>This form only accepts PDF files. The maximum file size for a PDF study guide is 10 MB.</p>
    <form action="ajax/upload-file.php" method="post">
        <div class="row">
            <div class="file-field input-field col s12 m6">
                <div class="btn blue">
                    <span>Choose Study Guide File</span>
                    <input type="file" id="file-upload" name="file-upload" accept=".pdf,application/pdf" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="input-field col s12 m4">
                <input type="text" id="display-name" name="display-name" data-length="300" required/>
                <label for="display-name">Display Name</label>
            </div>
        </div>
        <button class="btn waves-effect waves-light submit" type="submit" name="action">Upload</button>
    </form>
</div>

<?php include(dirname(__FILE__)."/../footer.php") ?>