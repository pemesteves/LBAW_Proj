DROP INDEX IF EXISTS "user_notified"; 
DROP INDEX IF EXISTS "user_email";
DROP INDEX IF EXISTS "student_regular_id";
DROP INDEX IF EXISTS "teacher_regular_id";
DROP INDEX IF EXISTS "organization_regular_id";
DROP INDEX IF EXISTS "search_post_titles";
DROP INDEX IF EXISTS "search_user_names";
DROP INDEX IF EXISTS "search_group_names";
DROP INDEX IF EXISTS "search_event_names";

-- GENERAL INDEXES
CREATE INDEX "user_notified" ON "notified_user"("user_notified");
CREATE INDEX "user_email" ON "user" USING hash("email");

-- REGULAR USER ID FOREIGN KEYS INDEXES
CREATE INDEX "student_regular_id" ON "student" USING hash("regular_user_id");
CREATE INDEX "teacher_regular_id" ON "teacher" USING hash("regular_user_id");
CREATE INDEX "organization_regular_id" ON "organization" USING hash("regular_user_id");
CREATE INDEX "post_author_id" ON "post" USING hash("author_id");

-- GIST SEARCH INDEXES 
CREATE INDEX "search_post_titles" ON "post" USING GIST(to_tsvector('english', "title"));
CREATE INDEX "search_user_names" ON "user" USING GIST(to_tsvector('english', "name"));
CREATE INDEX "search_group_names" ON "group" USING GIST(to_tsvector('english', "name"));
CREATE INDEX "search_event_names" ON "event" USING GIST(to_tsvector('english', "name"));