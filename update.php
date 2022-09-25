<?php
function update_libraries($id)
{
  require_once('init-mysqli.php');
  if (!!$_POST['name'] && !!$_POST['address'] && !!$_POST['manager']) {
    $query = $mysqli->prepare("UPDATE libraries SET name=?, address=?, manager=? WHERE id=?;");
    $query->bind_param('sssi', $_POST['name'], $_POST['address'], $_POST['manager'], $id);
    $query->execute();
    header('Location: /read.php?object_type=libraries');
    die();
  }
  $query = $mysqli->prepare("
    SELECT *
    FROM libraries
    WHERE id=?;
  ");
  $query->bind_param('d', $id);
  $query->execute();
  $result = $query->get_result();

  if ($result->num_rows == 0) {
    return_error(404, "Id doesn't match any records in db.", false);
  }

  $record = $result->fetch_assoc();
  $object = array(
    'title' => 'Edit library',
    'inputs' => array(
      'name' => array(
        'label' => 'Name',
        'type' => 'text',
        'required' => true,
        'value' => $record['name']
      ),
      'address' => array(
        'label' => 'Address',
        'type' => 'text',
        'required' => true,
        'value' => $record['address']
      ),
      'manager' => array(
        'label' => 'Manager',
        'type' => 'text',
        'required' => true,
        'value' => $record['manager']
      ),
    )
  );
  render_form($object);
}


function update_sectors($id)
{
  require_once('init-mysqli.php');
  if (
    !!$_POST['name'] &&
    !!$_POST['manager'] &&
    !!$_POST['library_id']
  ) {
    $query = $mysqli->prepare("UPDATE sectors SET name=?, manager=?, library_id=? WHERE id=?;");
    $query->bind_param('ssii', $_POST['name'], $_POST['manager'], $_POST['library_id'], $id);
    $query->execute();
    header('Location: /read.php?object_type=sectors');
    die();
  } else {
    $results = $mysqli->query("SELECT id, name FROM libraries;");
    $libraries = $results->fetch_all(MYSQLI_ASSOC);
    $query = $mysqli->prepare("SELECT * FROM sectors WHERE id=?;");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    if ($results->num_rows == 0) {
      return_error(404, 'Id does not match any records.');
    }
    $record = $result->fetch_assoc();
    $object = array(
      'title' => 'Edit sector',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true,
          'value' => $record['name']
        ),
        'manager' => array(
          'label' => 'Manager',
          'type' => 'text',
          'required' => true,
          'value' => $record['manager']
        ),
        'library_id' => array(
          'label' => 'Library',
          'type' => 'select',
          'values' => $libraries,
          'required' => true,
          'value' => $record['library_id']
        )
      )
    );
    render_form($object);
  }
}

function update_publishers($id)
{
  require_once('init-mysqli.php');
  if (!!$_POST['name']) {
    $name = $_POST['name'];
    $query = $mysqli->prepare("UPDATE publishers SET name=? WHERE id=?;");
    $query->bind_param('si', $name, $id);
    $query->execute();
    header('Location: /read.php?object_type=publishers');
    die();
  } else {
    $query = $mysqli->prepare("SELECT * FROM publishers WHERE id=?;");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 0) {
      return_error(404, 'ID does not match any records.');
    }
    $record = $result->fetch_assoc();
    $object = array(
      'title' => 'Edit publisher',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true,
          'value' => $record['name']
        )
      )
    );
    render_form($object);
  }
}

function update_authors($id)
{
  require_once('init-mysqli.php');
  if (!!$_POST['name']) {
    $name = $_POST['name'];
    $query = $mysqli->prepare("UPDATE authors SET name=? WHERE id=?;");
    $query->bind_param('si', $name, $id);
    $query->execute();
    header('Location: /read.php?object_type=authors');
    die();
  } else {
    $query = $mysqli->prepare("SELECT * FROM authors WHERE id=?;");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 0) {
      return_error(404, "ID does not match any records in db.");
    }
    $record = $result->fetch_assoc();
    $object = array(
      'title' => 'Add new author',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true,
          'value' => $record['name']
        )
      )
    );
    render_form($object);
  }
}

function update_readers($id)
{
  require_once('init-mysqli.php');
  if (!!$_POST['name'] && $_POST['egn']) {
    $name = $_POST['name'];
    $egn = $_POST['egn'];
    $query = $mysqli->prepare("UPDATE readers SET name=?, egn=? WHERE id=?;");
    $query->bind_param('ssi', $name, $egn, $id);
    $query->execute();
    header('Location: /read.php?object_type=readers');
    die();
  } else {
    $query = $mysqli->prepare("SELECT * FROM readers WHERE id=?;");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 0) {
      return_error(404, "ID does not match any records in db.");
    }
    $record = $result->fetch_assoc();
    $object = array(
      'title' => 'Add new reader',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true,
          'value' => $record['name']
        ),
        'egn' => array(
          'label' => 'EGN',
          'type' => 'text',
          'required' => true,
          'value' => $record['egn']
        )
      )
    );
    render_form($object);
  }
}

function update_books($id)
{
  require_once('init-mysqli.php');
  if (
    !!$_POST['name'] &&
    !!$_POST['author_ids'] &&
    !!$_POST['publisher_id'] &&
    !!$_POST['publish_year'] &&
    !!$_POST['acquire_date'] &&
    !!$_POST['sector_id'] &&
    !!$_POST['status']
  ) {
    $name = $_POST['name'];
    $author_ids = $_POST['author_ids'];
    $publisher_id = $_POST['publisher_id'];
    $publish_year = $_POST['publish_year'];
    $acquire_date = $_POST['acquire_date'];
    $sector_id = $_POST['sector_id'];
    $status = $_POST['status'];
    $query = $mysqli->prepare("
      UPDATE books
      SET name=?,
      publisher_id=?,
      publish_year=?,
      acquire_date=?,
      sector_id=?,
      status=?
      WHERE id = ?;
    ");
    $query->bind_param('sissiii', $name, $publisher_id, $publish_year, $acquire_date, $sector_id, $status, $id);
    $query->execute();

    foreach ($author_ids as $author_id) {
      $query = $mysqli->prepare("UPDATE books_authors SET author_id=? WHERE book_id=?;");
      $query->bind_param('ii', $author_id, $id);
      $query->execute();
    }
    header('Location: /read.php?object_type=books');
    die();
  } else {
    $query = $mysqli->prepare("SELECT * FROM books WHERE id=?;");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 0) {
      return_error(404, "ID does not match any records");
    }
    $record = $result->fetch_assoc();
    $results = $mysqli->query("SELECT id, name FROM authors;");
    $authors = $results->fetch_all(MYSQLI_ASSOC);
    $results = $mysqli->query("SELECT id, name FROM publishers;");
    $publishers = $results->fetch_all(MYSQLI_ASSOC);
    $results = $mysqli->query("SELECT id, name FROM sectors;");
    $sectors = $results->fetch_all(MYSQLI_ASSOC);

    $object = array(
      'title' => 'Add new book',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true,
          'value' => $record['name']
        ),
        'author_ids[]' => array(
          'type' => 'select',
          'label' => 'Authors',
          'values' => $authors,
          'multiple' => true,
          'required' => true,
          'value' => $record['author_id']
        ),
        'publisher_id' => array(
          'type' => 'select',
          'label' => 'Publishers',
          'values' => $publishers,
          'required' => true,
          'value' => $record['publisher_id']
        ),
        'publish_year' => array(
          'type' => 'date',
          'label' => 'Publish Year',
          'required' => true,
          'value' => $record['publish_year']
        ),
        'acquire_date' => array(
          'type' => 'date',
          'label' => 'Acquire Date',
          'required' => true,
          'value' => $record['acquire_date']
        ),
        'sector_id' => array(
          'type' => 'select',
          'label' => 'Sector',
          'values' => $sectors,
          'required' => true,
          'value' => $record['sector_id']
        ),
        'status' => array(
          'type' => 'select',
          'label' => 'Status',
          'values' => array(
            array(
              'id' => '0',
              'name' => 'New'
            ),
            array(
              'id' => '1',
              'name' => 'Used'
            ),
            array(
              'id' => '2',
              'name' => 'Mint'
            )
          ),
          'required' => true,
          'value' => $record['status']
        )
      )
    );
    render_form($object);
  }
}

function update_loans($id)
{
  require_once('init-mysqli.php');
  if (
    !!$_POST['reader_id'] &&
    !!$_POST['book_ids'] &&
    !!$_POST['start_date'] &&
    !!$_POST['end_date']
  ) {
    $reader_id = $_POST['reader_id'];
    $book_ids = $_POST['book_ids'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $query = $mysqli->prepare("UPDATE loans SET reader_id=?, start_date=?, end_date=? WHERE id=?;");
    $query->bind_param('issi', $reader_id, $start_date, $end_date, $id);
    $query->execute();

    foreach ($book_ids as $book_id) {
      $query = $mysqli->prepare("UPDATE books_loans SET book_id=? WHERE loan_id=?;");
      $query->bind_param('ii', $book_id, $id);
      $query->execute();
    }
    header('Location: /read.php?object_type=loans');
    die();
  } else {
    $query = $mysqli->prepare("SELECT * FROM loans WHERE id=?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 0) {
      return_error(404, "ID does not match any records in db.");
    }
    $record = $result->fetch_assoc();

    $results = $mysqli->query("SELECT id, name FROM books;");
    $books = $results->fetch_all(MYSQLI_ASSOC);

    $results = $mysqli->query("SELECT id, name FROM readers;");
    $readers = $results->fetch_all(MYSQLI_ASSOC);

    $object = array(
      'title' => 'Add new loan',
      'inputs' => array(
        'reader_id' => array(
          'label' => 'Reader',
          'type' => 'select',
          'values' => $readers,
          'required' => true,
          'value' => $record['reader_id']
        ),
        'start_date' => array(
          'type' => 'date',
          'label' => 'Start date',
          'required' => true,
          'value' => $record['start_date']
        ),
        'end_date' => array(
          'type' => 'date',
          'label' => 'End date',
          'required' => true,
          'value' => $record['end_date']
        ),
        'book_ids[]' => array(
          'type' => 'select',
          'label' => 'Books',
          'values' => $books,
          'required' => true,
          'multiple' => true,
        )
      )
    );
    render_form($object);
  }
}

$func_name = 'update_' . $_GET['object_type'];

if (function_exists($func_name) && $_GET['id']) {
  $func_name($_GET['id']);
} else {
  return_error(404, 'Can\'t find object.');
}

function render_form($object)
{
  require_once('header.php');
?>
  <h1><?= $object['title'] ?></h1>
  <form method="post">
    <?php
    foreach ($object['inputs'] as $name => $attr) {
    ?><div>
        <label>
          <?= ucfirst($attr['label']); ?>:
          <?php
          if ($attr['type'] == 'select') {
          ?>
            <select name="<?= $name; ?>"
            <?php if ($attr['multiple']) echo 'multiple'; ?>
            <?php if ($attr['required']) echo 'required'; ?>
            >
              <?php
              if (count($attr['values']) > 0) {
                foreach ($attr['values'] as $value) {
              ?>
                  <option value="<?= $value['id']; ?>"
                    <?php if ($value['id'] == $attr['value']) echo "selected";?>>
                    <?= $value['name']; ?>
                  </option>
                <?php
                }
              } else {
                ?>
                <option value="">No Entries</option>
              <?php
              }
              ?>
            </select>
          <?php
          } else {
          ?>
            <input type="<?= $attr['type']; ?>"
            name="<?= $name; ?>"
            <?php if ($attr['required']) echo 'required'; ?>
            value="<?=$attr['value']; ?>">
          <?php
          } ?>
        </label>
      </div>
    <?php
    }
    ?>
    <button type="submit">Submit</button>
  </form>
<?php
  require_once('footer.php');
}

function return_error($errno = 404, $errmsg = '', $render_add_action = false)
{
  require_once('header.php');
?>
  <h1>Error <?= $errno ?></h1>
  <p>Error: <?= $errmsg; ?></p>
  <?php
  if ($render_add_action) {
  ?>
    <a href="/create.php?object_type=<?= $_GET['object_type'] ?>">Add new</a>
<?php
  }
  require_once('footer.php');
  die();
}
