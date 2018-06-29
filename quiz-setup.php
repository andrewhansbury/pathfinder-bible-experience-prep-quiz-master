<?php
    require_once(dirname(__FILE__)."/init.php");
    
    $title = 'Quiz Setup';
    
    // load possible books and commentary volumes

    $currentYear = get_active_year($pdo)["YearID"];
    $chapterQuery = '
        SELECT DISTINCT b.BookID, b.Name, b.NumberChapters,
            c.ChapterID, c.Number AS ChapterNumber, c.NumberVerses
        FROM Books b 
            JOIN Chapters c ON b.BookID = c.BookID
            JOIN Verses v ON c.ChapterID = v.ChapterID
            JOIN Questions q ON v.VerseID = q.StartVerseID
        WHERE b.YearID = ' . $currentYear . ' AND q.IsDeleted = 0
        ORDER BY b.Name, ChapterNumber';
    $chapterData = $pdo->query($chapterQuery)->fetchAll();
    $chapters = array();
    foreach ($chapterData as $chapter) {
        $chapters[] =  array('id' => $chapter["ChapterID"], 'name' => $chapter["Name"], 'chapter' => $chapter["ChapterNumber"]);
    }

    $volumes = load_commentaries($pdo, true);
    $lastBookSeen = "";

    $areAnyQuestionsAvailable = count($chapters) > 0 || count($volumes) > 0;
?>

<?php include(dirname(__FILE__)."/header.php"); ?>

<p><a class="btn-flat blue-text waves-effect waves-blue no-uppercase" href=".">Back</a></p>

<?php if ($areAnyQuestionsAvailable) { ?>
<div id="start-quiz">
    <h4>Quiz Setup</h4>
    <form id="quiz-setup-form" method="post">
        <p><b>Choose Bible Chapters &amp; Commentary Volumes to Be Quizzed On -- for Bible Q&amp;A questions, questions are loaded by chapter based on the question's start verse</b></p>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <select multiple id="quiz-items" name="quiz-items[]">
                    <option value="" disabled selected>All</option>
                    <?php 
                        foreach ($chapters as $chapter) { 
                            if ($lastBookSeen != $chapter['name']) {
                                if ($lastBookSeen != "" && $lastBookSeen != $chapter['name']) {
                                    echo '</optgroup>';
                                }
                                echo '<optgroup label="' . $chapter['name'] . '">';
                                $lastBookSeen = $chapter['name'];
                            }
                    ?>
                            <option value="chapter-<?= $chapter['id'] ?>"><?= $chapter['name'] ?>&nbsp;<?= $chapter['chapter'] ?></option>
                    <?php } 
                        echo '</optgroup>';
                    ?>
                    <optgroup label="SDA Bible Commentary">
                        <?php foreach ($volumes as $volume) { ?>
                            <option value="commentary-<?= $volume['id'] ?>"><?= $volume['name'] ?> (<?= $volume['topic'] ?>)</option>
                        <?php } ?>
                    </optgroup>
                </select>
                <label>Bible Chapters &amp; Commentary Volumes with Created Questions</label>
            </div>
        </div>
        <p class="negative-top-margin"><b>Weighted Question Distribution</b></p>
        <div class="row negative-top-margin">
            <div class="input-field col s12">
                <input type="checkbox" id="enable-question-distribution" name="enable-question-distribution"/>
                <label class="black-text" for="enable-question-distribution">Enable weighted question distribution</label>
            </div>
        </div>
        <p class="weighted-distribution-help">For weighted question distribution, questions will be drawn from all of the chosen chapters or commentaries with the given percentage weights applied to the number of questions for the quiz. For instance, if there are 90 total questions, and the weight for 2 Kings 3 is 10%, then 9 questions from 2 Kings 3 (10% of 90) will show up in the quiz. For any chapter/commentary weight percentages left blank, the quiz generator will <em>attempt</em> to select an equal amount of questions for each area. As another example, for an even question distrubution between 3 chapters, simply choose those three chapters and do not enter any weights by leaving them blank (0% is treated as blank).</p>
        <p class="weighted-distribution-help">You must have at least one Bible chapter or commentary selected for the weighted distribution option to work. If weights do not equal 100% or there is no chapter with a weight left blank or at 0% for the quiz engine to know which chapters/commentaries to choose for the remaining questions, the total number of questions in the quiz will not be equal to the total amount of quiz questions requested below.</p>
        <div class="row">
            <div class="col l5 m7 s12">
                <table id="weighted-question-table" class="bordered highlight responsive-table">
                    <thead id="weighted-question-table-header">
                        <tr>
                            <th>Chapter/Commentary</th>
                            <th>Weight (%)</th>
                        </tr>
                    </thead>
                    <tbody id="weighted-question-table-body">
                        <?php foreach ($chapters as $chapter) { ?>
                            <tr id="table-chapter-<?= $chapter['id'] ?>">
                                <td><?= $chapter['name'] ?>&nbsp;<?= $chapter['chapter'] ?></td>
                                <td><input name="table-input-chapter-<?= $chapter['id'] ?>" class="table-input" 
                                           type="number" value="" min="0" max="100"></input></td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($volumes as $volume) { ?>
                            <tr id="table-commentary-<?= $volume['id'] ?>">
                                <td><?= $volume['name'] ?> (<?= $volume['topic'] ?>)</td>
                                <td><input name="table-input-commentary-<?= $volume['id'] ?>" class="table-input" 
                                           type="number" value="" min="0" max="100"></input></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>    
            </div>
        </div>
        <p class=""><b>Maximum number of questions and maximum number of points per question</b></p>
        <div class="row">
            <div class="input-field col s6 m3">
                <input type="number" id="max-questions" name="max-questions" required value="30" max="500" min="1"/>
                <label for="max-questions">Maximum Questions</label>
            </div>
            <div class="input-field col s6 m3">
                <input type="number" id="max-points" name="max-points" required value="25" max="500" min="0"/>
                <label for="max-points">Maximum Points</label>
            </div>
        </div>
        <p id="question-types"><b>Question types</b></p>
        <div class="row">
            <div class="input-field col s12">
                <input type="radio" class="with-gap" name="question-types" id="both" value="both"/>
                <label class="black-text" for="both">Both Q&amp;A and fill in the blank</label>
            </div>
            <div class="input-field col s12">
                <input type="radio" class="with-gap" name="question-types" id="qa-only" value="qa-only" checked/>
                <label class="black-text" for="qa-only">Q&amp;A only</label>
            </div>
            <div class="input-field col s12">
                <input type="radio" class="with-gap" name="question-types" id="fill-in-only" value="fill-in-only"/>
                <label class="black-text" for="fill-in-only">Fill in the blank only</label>
            </div>
            <div class="input-field col s2" id="fill-in-percent-div">
                <input type="number" name="fill-in-percent" id="fill-in-percent" value="30" min="1" max="100"/>
                <label class="" for="fill-in-percent">% Blanks</label>
            </div>
        </div>
        <p id="question-order"><b>Question selection and order</b></p>
        <div class="row">
            <div class="input-field col s12">
                <input type="radio" class="with-gap text-blue" name="order" id="sequential-sequential" value="sequential-sequential" />
                <label class="black-text" for="sequential-sequential">Sequential</label>
            </div>
            <div class="input-field col s12">
                <input type="radio" class="with-gap" name="order" id="random-sequential" value="random-sequential" checked/>
                <label class="black-text" for="random-sequential">Random selection and sequential order</label>
            </div>
            <div class="input-field col s12">
                <input type="radio" class="with-gap" name="order" id="random-random" value="random-random"  />
                <label class="black-text" for="random-random">Random selection and random order</label>
            </div>
        </div>
        <p id="question-filtering"><b>Question history</b></p>
        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="no-questions-answered-correct" name="no-questions-answered-correct"/>
                <label class="black-text" for="no-questions-answered-correct">Don't see questions answered correctly in the past</label>
            </div>
        </div>
        <p id="question-filtering"><b>Flash card options (<em>Only apply to flash cards</em>)</b></p>
        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="flash-show-recently-added" name="flash-show-recently-added"/>
                <label class="black-text" for="flash-show-recently-added">Show recently added questions (overrides ALL above settings, including weighted question distribution!)</label>
            </div>
            <div class="input-field col s12 m4" id="fill-in-percent-div">
                <input type="number" name="flash-recently-added-days" id="flash-recently-added-days" value="30" min="1" max="31"/>
                <label class="" for="flash-recently-added-days">Number of days to go back in time for recently added questions</label>
            </div>
        </div>
        <div class="negative-top-margin row">
            <div class="input-field col s12">
                <input type="checkbox" id="flash-full-fill-in" name="flash-full-fill-in"/>
                <label class="black-text" for="flash-full-fill-in">View fill in the blank as full text with answers in <b>bold</b></label>
            </div>
        </div>
        <div class="divider"></div>
        <div class="row" id="quiz-setup-button-row">
            <div class="input-field col s12 m10">
                <button id="start-quiz-btn" class="btn waves-effect waves-light submit" type="button">Start Quiz</button>
                <button id="lr-flash-cards-btn" class="flash-cards-btn btn waves-effect waves-light submit" type="button">Left/Right Flash Cards</button>
                <button id="fb-flash-cards-btn" class="flash-cards-btn btn waves-effect waves-light submit" type="button">Front/Back Flash Cards</button>
            </div>
        </div>
        <div class="divider"></div>
        <div class="input-field col s6">
            <a id="save-data" class="btn btn-flat red white-text waves-effect red-waves right-margin" href="delete-user-answers.php">Erase previously saved answers</a>
        </div>
    </form>
</div>
<?php } else { ?>
<div id="start-quiz">
    <h4>Quiz Setup</h4>
    <p>Sorry! No quiz questions have been created yet! Why don't you go <a href="add-edit-question.php?type=create">create one</a>?</p>
</div>
<?php } ?>

<?php include(dirname(__FILE__)."/footer.php") ?>


<script type="text/javascript">
    $(document).ready(function() {
        var bibleQuestionType = document.getElementById('quiz-items');
        $(bibleQuestionType).material_select();
        fixRequiredSelectorCSS();

        var quizForm = document.getElementById('quiz-setup-form');
        var quizItemSelector = document.getElementById('quiz-items');

        var buttonID = '';
        $(':submit').click(function() {
            buttonID = $(this).attr('id'); // ...I can't remember what this is for...
        })
        
        var enableQuestionDistributionCheckbox = document.getElementById('enable-question-distribution');

        function calculateQuestionDistributionTotal() {
            if (enableQuestionDistributionCheckbox.checked) {
                var total = 0;
                $('#weighted-question-table input:visible').each(function(index, element) {
                    if (element.value != "") {
                        total += parseInt(element.value);
                    }
                });
                return total;
            }
            return 0;
        }

        function hasNegativeQuestionDistribution() {
            if (enableQuestionDistributionCheckbox.checked) {
                var hasNegativeQuestionDistribution = false;
                $('#weighted-question-table input:visible').each(function(index, element) {
                    if (element.value != "") {
                        var number = parseInt(element.value);
                        if (number < 0) {
                            hasNegativeQuestionDistribution = true;
                            return false;
                        }
                    }
                });
                return hasNegativeQuestionDistribution;
            }
            return false;
        }

        // return true if OK to go ahead with form submit; false otherwise
        function checkQuestionDistributionTotal() {
            if (enableQuestionDistributionCheckbox.checked) {
                var hasItemSelected = false;
                for (var i = 0; i < quizItemSelector.options.length; i++) {
                    var option = quizItemSelector.options[i];
                    if (option.value !== "" && option.value !== "All" && option.selected) {
                        hasItemSelected = true;
                        break;
                    }
                }
                if (hasItemSelected) {
                    if (hasNegativeQuestionDistribution()) {
                        alert('Negative question distribution weights are not allowed.');
                        return false;
                    }
                    var total = calculateQuestionDistributionTotal();
                    if (total < 0 || total > 100) {
                        alert('Question distribution total must be between 0% and 100%. It is currently at ' + total + '%.');
                        return false;
                    }
                }
            }
            return true;
        }

        $('#start-quiz-btn').on("click", function() {
            if (checkQuestionDistributionTotal()) {
                $(quizForm).attr('target', '_blank');
                $(quizForm).attr('action', 'quiz.php');
                $(quizForm).submit();
            }
        });
        $('#lr-flash-cards-btn').on("click", function() {
            if (checkQuestionDistributionTotal()) {
                $(quizForm).attr('target', '_blank');
                $(quizForm).attr('action', 'study-guide-pdf.php?type=lr');
                $(quizForm).submit();
            }
        });
        $('#fb-flash-cards-btn').on("click", function() {
            if (checkQuestionDistributionTotal()) {
                $(quizForm).attr('target', '_blank');
                $(quizForm).attr('action', 'study-guide-pdf.php?type=fb');
                $(quizForm).submit();
            }
        });

        $(enableQuestionDistributionCheckbox).change(function() {
            if (this.checked) {
                $('#weighted-question-table').show();
                $('.weighted-distribution-help').show();
            }
            else {
                $('#weighted-question-table').hide();
                $('.weighted-distribution-help').hide();
            }
        });

        $(quizItemSelector).change(function() {
            var isShowingWeightedDistributionItem = false;
            for (var i = 0; i < this.options.length; i++) {
                var option = this.options[i];
                var value = option.value;
                if (value !== "" && value !== "All") {
                    var tableSelector = 'table-' + value;
                    if (option.selected) {
                        $('#' + tableSelector).show();
                        isShowingWeightedDistributionItem = true;
                    }
                    else {
                        $('#' + tableSelector).hide();
                    }
                }
            }
            if (isShowingWeightedDistributionItem) {
                $('#weighted-question-table-header').show();
            }
            else {
                $('#weighted-question-table-header').hide();
            }
        });

        $("#enable-question-distribution").trigger("change");
        $("#quiz-items").trigger("change");
    });
</script>