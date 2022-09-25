<?php
function delete_libraries($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM libraries WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This library has sectors in it. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=libraries');
  die();
}

function delete_sectors($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM sectors WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This sector has books in it. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=sectors');
  die();
}

function delete_publishers($id) {
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM publishers WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This publisher has published some of our books. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=publishers');
  die();
}

function delete_authors($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM authors WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This author has written some books which we have. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=authors');
  die();
}

function delete_readers($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM readers WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This reader has taken books from us. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=readers');
  die();
}

function delete_books($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM books_authors WHERE book_id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This book has been loaned to a reader. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=books');
  die();
}

function delete_loans($id)
{
  require_once('init-mysqli.php');
  try {
    $query = $mysqli->prepare("DELETE FROM books_loans WHERE loan_id = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $query = $mysqli->prepare("DELETE FROM loans WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
  } catch(Exception $e) {
    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
      require_once('header.php');
      ?>
      <h1>Error</h1>
      <p>This book has been loaned to a reader. You can't delete the record, yet!</p>
      <?php
      require_once('footer.php');
    }
    die();
  }
  header('Location: /read.php?object_type=loans');
  die();
}

$func_name = 'delete_' . $_POST['object_type'];

if (function_exists($func_name) && $_POST['id']) {
  $func_name($_POST['id']);
} else {
  header('Location: read.php?object_type=' . $_POST['object_type']);
  die();
}
