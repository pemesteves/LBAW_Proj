BEGIN TRANSACTION ISOLATION LEVEL REPEATABLE READ 
 
-- Insert user
 INSERT INTO "user" ("name", "email", "password") 
  VALUES ($name, $email, $password; 
 
-- Insert regular_user 
 INSERT INTO "regular_user" ("user_id", "personal_info") 
  VALUES (currval('user_id_seq'), $info); 

--Insert student
 INSERT INTO "student" ("regular_user_id")
  VALUES (currval('regular_user_id_seq'));

COMMIT;


BEGIN TRANSACTION ISOLATION LEVEL REPEATABLE READ 
 
-- Insert user
 INSERT INTO "user" ("name", "email", "password") 
  VALUES ($name, $email, $password; 
 
-- Insert regular_user 
 INSERT INTO "regular_user" ("user_id", "personal_info") 
  VALUES (currval('user_id_seq'), $info); 

--Insert teacher
 INSERT INTO "teacher" ("regular_user_id")
  VALUES (currval('regular_user_id_seq'));

COMMIT;


BEGIN TRANSACTION ISOLATION LEVEL REPEATABLE READ 
 
-- Insert user
 INSERT INTO "user" ("name", "email", "password") 
  VALUES ($name, $email, $password; 
 
-- Insert regular_user 
 INSERT INTO "regular_user" ("user_id", "personal_info") 
  VALUES (currval('user_id_seq'), $info); 

--Insert organization
 INSERT INTO "organization" ("regular_user_id")
  VALUES (currval('regular_user_id_seq'));

COMMIT;