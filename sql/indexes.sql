DROP INDEX IF EXISTS "user_notified"; 
DROP INDEX IF EXISTS "user_email";
DROP INDEX IF EXISTS "search_post_titles";
DROP INDEX IF EXISTS "search_user_names";
DROP INDEX IF EXISTS "search_group_names";
DROP INDEX IF EXISTS "search_event_names";


CREATE INDEX "user_notified" ON "notified_user"("user_notified");
CREATE INDEX "user_email" ON "user" USING hash("email");

CREATE INDEX "search_post_titles" ON "post" USING GIST("title");
CREATE INDEX "search_user_names" ON "user" USING GIST("name");
CREATE INDEX "search_group_names" ON "group" USING GIST("name");
CREATE INDEX "search_event_names" ON "event" USING GIST("name");