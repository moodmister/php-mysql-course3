DROP TABLE IF EXISTS
libraries,
sectors,
authors,
publishers,
books,
books_authors,
readers,
loans,
books_loans CASCADE;
CREATE TABLE IF NOT EXISTS libraries (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  address text NOT NULL,
  manager text NOT NULL,
  PRIMARY KEY (id)
);
CREATE TABLE IF NOT EXISTS sectors (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  manager text NOT NULL,
  library_id int NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (library_id) REFERENCES libraries(id)
);
CREATE TABLE IF NOT EXISTS authors (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS publishers (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS books (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  publisher_id int NOT NULL,
  publish_year date NOT NULL,
  acquire_date date NOT NULL,
  sector_id int NOT NULL,
  status int NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (publisher_id) REFERENCES publishers(id),
  FOREIGN KEY (sector_id) REFERENCES sectors(id)
);
CREATE TABLE IF NOT EXISTS books_authors (
  id int NOT NULL AUTO_INCREMENT,
  book_id int NOT NULL,
  author_id int NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (book_id) REFERENCES books(id),
  FOREIGN KEY (author_id) REFERENCES authors(id)
);
CREATE TABLE IF NOT EXISTS readers (
  id int NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  egn text NOT NULL,
  PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS loans (
  id int NOT NULL AUTO_INCREMENT,
  reader_id int NOT NULL,
  start_date date NOT NULL,
  end_date date NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (reader_id) REFERENCES readers(id)
);
CREATE TABLE IF NOT EXISTS books_loans (
  id int NOT NULL AUTO_INCREMENT,
  loan_id int NOT NULL,
  book_id int NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (loan_id) REFERENCES loans(id),
  FOREIGN KEY (book_id) REFERENCES books(id)
);