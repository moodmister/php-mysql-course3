<?php
function create_libraries()
{
  if (!!$_POST['name'] && !!$_POST['address'] && !!$_POST['manager']) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $manager = $_POST['manager'];
    require_once('init-mysqli.php');
    $query = $mysqli->prepare("INSERT INTO libraries (id, name, address, manager) VALUES (NULL, ?, ?, ?);");
    $query->bind_param('sss', $name, $address, $manager);
    $query->execute();
    header('Location: /read.php?object_type=libraries');
    die();
  } else {
    $object = array(
      'title' => 'Add new library',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true
        ),
        'address' => array(
          'label' => 'Address',
          'type' => 'text',
          'required' => true
        ),
        'manager' => array(
          'label' => 'Manager',
          'type' => 'text',
          'required' => true
        ),
      )
    );
    render_form($object);
  }
}

function create_sectors()
{
  if (
    !!$_POST['name'] &&
    !!$_POST['manager'] &&
    !!$_POST['library_id']
  ) {
    $name = $_POST['name'];
    $manager = $_POST['manager'];
    $library_id = $_POST['library_id'];
    require_once('init-mysqli.php');
    $query = $mysqli->prepare("INSERT INTO sectors (id, name, manager, library_id) VALUES (NULL, ?, ?, ?);");
    $query->bind_param('ssi', $name, $manager, $library_id);
    $query->execute();
    header('Location: /read.php?object_type=sectors');
    die();
  } else {
    require_once('init-mysqli.php');
    $results = $mysqli->query("SELECT id, name FROM libraries;");
    $libraries = $results->fetch_all(MYSQLI_ASSOC);
    $object = array(
      'title' => 'Add new sector',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true
        ),
        'address' => array(
          'label' => 'Address',
          'type' => 'text',
          'required' => true
        ),
        'manager' => array(
          'label' => 'Manager',
          'type' => 'text',
          'required' => true
        ),
        'library_id' => array(
          'label' => 'Library',
          'type' => 'select',
          'values' => $libraries,
          'required' => true
        )
      )
    );
    render_form($object);
  }
}

function create_publishers()
{
  if (!!$_POST['name']) {
    require_once('init-mysqli.php');
    $name = $_POST['name'];
    $query = $mysqli->prepare("INSERT INTO publishers (id, name) VALUES (NULL, ?);");
    $query->bind_param('s', $name);
    $query->execute();
    header('Location: /read.php?object_type=publishers');
    die();
  } else {
    $object = array(
      'title' => 'Add new publisher',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true
        )
      )
    );
    render_form($object);
  }
}

function create_authors()
{
  if (!!$_POST['name']) {
    require_once('init-mysqli.php');
    $name = $_POST['name'];
    $query = $mysqli->prepare("INSERT INTO authors (id, name) VALUES (NULL, ?);");
    $query->bind_param('s', $name);
    $query->execute();
    header('Location: /read.php?object_type=authors');
    die();
  } else {
    $object = array(
      'title' => 'Add new author',
      'inputs' => array(
        'name' => array(
          'label' => 'Name',
          'type' => 'text',
          'required' => true
        )
      )
    );
    render_form($object);
  }
}

function create_readers()
{
  if (!!$_POST['name'] && $_POST['egn']) {
    require_once('init-mysqli.php');
    $name = $_POST['name'];
    $egn = $_POST['egn'];
    $query = $mysqli->prepare("INSERT INTO readers (id, name, egn) VALUES (NULL, ?, ?);");
    $query->bind_param('ss', $name, $egn);
    $query->execute();
    header('Location: /read.php?object_type=readers');
    die();
  } else {
    $object = array(
      'title' => 'Add new reader',
      'inputs' => array(
        'name' => array(
          'label' => 'Userame',
          'type' => 'text',
          'required' => true
        ),
        'egn' => array(
          'label' => 'EGN',
          'type' => 'text',
          'required' => true
        )
      )
    );
    render_form($object);
  }
}

function create_books()
{
  if (
    isset($_POST['name']) &&
    isset($_POST['author_ids']) &&
    isset($_POST['publisher_id']) &&
    isset($_POST['publish_year']) &&
    isset($_POST['acquire_date']) &&
    isset($_POST['sector_id']) &&
    isset($_POST['status'])
  ) {
    require_once('init-mysqli.php');
    $name = $_POST['name'];
    $author_ids = $_POST['author_ids'];
    $publisher_id = $_POST['publisher_id'];
    $publish_year = $_POST['publish_year'];
    $acquire_date = $_POST['acquire_date'];
    $sector_id = $_POST['sector_id'];
    $status = $_POST['status'];
    $query = $mysqli->prepare("INSERT INTO books (id, name, publisher_id, publish_year, acquire_date, sector_id, status) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
    $query->bind_param('sissii', $name, $publisher_id, $publish_year, $acquire_date, $sector_id, $status);
    $query->execute();

    $last_insert_id = $mysqli->insert_id;
    foreach ($author_ids as $author_id) {
      $query = $mysqli->prepare("INSERT INTO books_authors (id, book_id, author_id) VALUES (NULL, ?, ?);");
      $query->bind_param('ii', $last_insert_id, $author_id);
      $query->execute();
    }
    header('Location: /read.php?object_type=books');
    die();
  } else {
    require_once('init-mysqli.php');
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
          'required' => true
        ),
        'author_ids[]' => array(
          'type' => 'select',
          'label' => 'Authors',
          'values' => $authors,
          'multiple' => true,
          'required' => true
        ),
        'publisher_id' => array(
          'type' => 'select',
          'label' => 'Publishers',
          'values' => $publishers,
          'required' => true
        ),
        'publish_year' => array(
          'type' => 'date',
          'label' => 'Publish Year',
          'required' => true
        ),
        'acquire_date' => array(
          'type' => 'date',
          'label' => 'Acquire Date',
          'required' => true
        ),
        'sector_id' => array(
          'type' => 'select',
          'label' => 'Sector',
          'values' => $sectors,
          'required' => true
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
          'required' => true
        )
      )
    );
    render_form($object);
  }
}

function create_loans()
{
  if (
    !!$_POST['reader_id'] &&
    !!$_POST['book_ids'] &&
    !!$_POST['start_date'] &&
    !!$_POST['end_date']
  ) {
    require_once('init-mysqli.php');
    $reader_id = $_POST['reader_id'];
    $book_ids = $_POST['book_ids'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $query = $mysqli->prepare("INSERT INTO loans (id, reader_id, start_date, end_date) VALUES (NULL, ?, ?, ?);");
    $query->bind_param('dss', $reader_id, $start_date, $end_date);
    $query->execute();
    $last_insert_id = $mysqli->insert_id;
    foreach ($book_ids as $book_id) {
      $query = $mysqli->prepare("INSERT INTO books_loans (id, loan_id, book_id) VALUES (NULL, ?, ?);");
      $query->bind_param('dd', $last_insert_id, $book_id);
      $query->execute();
    }
    header('Location: /read.php?object_type=loans');
    die();
  } else {
    require_once('init-mysqli.php');
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
        ),
        'start_date' => array(
          'type' => 'date',
          'label' => 'Start date',
          'required' => true,
        ),
        'end_date' => array(
          'type' => 'date',
          'label' => 'End date',
          'required' => true,
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

function render_form($object_type)
{
  require_once('header.php');
?>
  <h1><?= $object_type['title'] ?></h1>
  <form method="post">
    <?php
    foreach ($object_type['inputs'] as $name => $attr) {
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
                  <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
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
            <input type="<?= $attr['type']; ?>" name="<?= $name; ?>" <?php if ($attr['required']) echo 'required'; ?> placeholder="<?=$attr['label']?>">
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

$func_name = 'create_' . $_GET['object_type'];

if (function_exists($func_name)) {
  $func_name();
} else {
  return_error(404, 'No such object type.');
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
