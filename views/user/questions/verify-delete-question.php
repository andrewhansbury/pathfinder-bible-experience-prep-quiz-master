<p><a class="btn-flat blue-text waves-effect waves-blue no-uppercase" href="<?= $app->yurl('/questions') ?>">Back</a></p>

<div id="delete-user">
    <p> Are you sure you want to delete the question '<?= $question->question ?>' with answer '<?= $question->answer ?>'? </p>
    <form method="post">
        <input type="hidden" name="question-id" value="<?= $question->questionID ?>"/>
        <button class="btn waves-effect waves-light submit red white-text" type="submit" name="action">Delete Question</button>
    </form>
</div>