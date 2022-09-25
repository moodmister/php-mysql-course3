<?php

function render_table($columns = null, $rows = null, $render_actions = false) {
  $actions_html = '';
  ?>
  <div class="add-new">
    <a href="/create.php?object_type=<?= $_GET['object_type']; ?>">Add new</a>
  </div>
  <table>
    <thead>
      <tr>
        <?php
        foreach ($columns as $column) {
        ?>
          <td><?= $column ?></td>
        <?php
        }
        if ($render_actions) {
          ?>
          <td>Actions</td>
          <?php
        }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($rows as $row) {
          echo '<tr>';
          foreach($row as $value) {
            echo '<td>' . $value . '</td>';
          }
          if ($render_actions) {
            ?><td>
              <form action="/update.php" method="get">
                <input type="hidden" name="object_type" value="<?=$_GET['object_type']?>">
                <input type="hidden" name="id" value="<?=$row['ID']?>">
                <button>Edit</button>
              </form>
              <form action="/delete.php" method="post">
                <input type="hidden" name="object_type" value="<?=$_GET['object_type']?>">
                <input type="hidden" name="id" value="<?=$row['ID']?>">
                <button>Delete</button>
              </form>
            </td>
            <?php
          }
        }
      ?>
    </tbody>
  </table>
  <?php
}

function read_all_libraries() {
  require_once('init-mysqli.php');
  $query = "
  SELECT id AS ID,
  name AS Name,
  address AS Address,
  manager AS Manager
  FROM libraries
  ORDER BY id;";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No libraries found', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Library list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_authors() {
  require_once('init-mysqli.php');
  $query = "SELECT id AS ID, name AS Author FROM authors ORDER BY id;";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No authors found', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Authors list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_publishers() {
  require_once('init-mysqli.php');
  $query = "SELECT id AS ID, name AS Name FROM publishers ORDER BY id;";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No publishers found', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Publishers list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_readers() {
  require_once('init-mysqli.php');
  $query = "SELECT id AS ID,
  name AS Name,
  egn AS EGN
  FROM readers
  ORDER BY id;";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No readers found', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Readers list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_loans()
{
  require_once('init-mysqli.php');
  $query = "
    SELECT loans.id AS ID,
    readers.name AS Reader,
    JSON_ARRAYAGG(books.name) AS Books,
    loans.start_date AS `Start Date`,
    loans.end_date AS `End Date`
    FROM (((loans
    INNER JOIN books_loans ON books_loans.loan_id = loans.id)
    INNER JOIN books ON books_loans.book_id = books.id)
    INNER JOIN readers ON loans.reader_id = readers.id)
    GROUP BY loans.id
    ORDER BY loans.id;
  ";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No loans found', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Loans list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_books()
{
  require_once('init-mysqli.php');
  $query = "
    SELECT 
    books.id AS ID,
    books.name AS Title,
    JSON_ARRAYAGG(authors.name) AS `Author Name`,
    publishers.name AS `Publisher Name`,
    books.publish_year AS `Publish Year`,
    books.acquire_date AS `Acquire Date`,
    sectors.name AS `Sector`,
    books.status AS Status
    FROM ((((books
    INNER JOIN books_authors ON books_authors.book_id = books.id)
    INNER JOIN authors ON books_authors.author_id = authors.id)
    INNER JOIN publishers ON books.publisher_id = publishers.id)
    INNER JOIN sectors ON books.sector_id = sectors.id)
    GROUP BY books.id
    ORDER BY books.id;
  ";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, "No books found.", true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Books list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function read_all_sectors() {
  require_once('init-mysqli.php');
  $query = "
    SELECT sectors.id AS ID, sectors.name AS Name, sectors.manager AS Manager, libraries.name AS `Library Name`
    FROM sectors
    INNER JOIN libraries ON sectors.library_id = libraries.id
    ORDER BY sectors.id;
  ";
  $results = $mysqli->query($query);
  if ($results->num_rows == 0) {
    return_error(404, 'No sectors found.', true);
  }
  $rows = $results->fetch_all(MYSQLI_ASSOC);
  $columns = array();
  if (!empty($rows)) {
    $columns = array_keys($rows[0]);
  }
  require_once('header.php');
  ?>
  <h1>Sectors list</h1>
  <?php
  render_table($columns, $rows, true);
  require_once('footer.php');
}

function return_error($errno = 404, $errmsg = '', $render_add_action = false) {
  require_once('header.php');
  ?>
  <h1>Error <?= $errno ?></h1>
  <div class="add-new">
  <p>Error: <?= $errmsg; ?></p>
  </div>
  <?php
  if ($render_add_action) {
    ?>
    <div class="add-new">
      <a href="/create.php?object_type=<?= $_GET['object_type']?>">Add new</a>
    </div>
    <?php
  }
  require_once('footer.php');
  die();
}

$func_name = 'read_all_' . $_GET['object_type'];
if (function_exists($func_name)) {
  $func_name();
} else {
  return_error(404, 'No such object in database');
}