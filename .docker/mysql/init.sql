CREATE DATABASE blog_api CHARACTER SET utf8 COLLATE utf8_general_ci;

-- post
CREATE TABLE IF NOT EXISTS blog_api.post (
  id INT(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  seo_title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
  draft TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

-- post fixtures
INSERT INTO blog_api.post (title, seo_title, content)
VALUES('This is a test post', 'this-is-a-test-post', 'Content test');
