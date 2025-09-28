<?php
session_start();
include 'db_class.php';
include 'utilities.php';
$id = $_GET['id'];
$my_db = new sql_class;
$result = $my_db->readID($id);
?>

<?php
if (isset($_POST['update'])) {

    $update_task_title = filter($_POST['title']);
    $update_task_description = filter($_POST['description']);
    $update_task_category = filter($_POST['category']);

    if ($update_task_title != "" && $update_task_category != "") {

        $my_db->editItem($id, $update_task_title, $update_task_description, $update_task_category);

        $_SESSION["message"] = "Aggiornamento eseguito.";
        $_SESSION["status"] = "success";
    } else {

        $_SESSION["message"] = "Modifica non eseguita: non puoi usare caratteri speciali.";
        $_SESSION["status"] = "danger";
    }

    header('location: index.php');
}
?>

<?php
require_once 'header.php';
?>

<div class="container">
    <div class='row'>
        <div class='col-8 mx-auto mt-5'>
            <h2 class="display-4 mx-auto mt-2 text-center">Update Task</h2>
            <form class="" action="" method="post">
                <div class="form-group">
                    <div class="form-group row">
                        <label for="select_category" class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" id="inlineRadio1" value="1-DO">
                                <label class="form-check-label" for="inlineRadio1">Urgent and important</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" id="inlineRadio3" value="2-PLAN" checked>
                                <label class="form-check-label" for="inlineRadio3">Important</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" id="inlineRadio2" value="3-DELEGATE">
                                <label class="form-check-label" for="inlineRadio2">Urgent</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category" id="inlineRadio4" value="4-DEFAULT">
                                <label class="form-check-label" for="inlineRadio4">Not important</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="text_title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input id="text_title" class="form-control form-control-lg" type="text" name="title" maxlength="50" autocomplete="off" placeholder="Task title" value="<?= $result['task_title'] ?>" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="text_description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input id="text_description" class="form-control form-control-lg" type="text" maxlength="200" autocomplete="off" name="description" placeholder="Task description" value="<?= $result['task_description'] ?>">
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    <input class="btn btn-warning btn-block" type="submit" name="update" value="Update">
                    <input class="btn btn-danger btn-block" onclick="location.href='index.php';" value="Cancel">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var elements = document.getElementsByClassName("form-check-input");
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].value == "<?= $result['task_category'] ?>") {
            elements[i].checked = true;
        }
    }
</script>

</body>

</html>