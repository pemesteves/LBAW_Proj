DROP TABLE IF EXISTS public."user_interested_in_event";
DROP TABLE IF EXISTS public."friend";
DROP TABLE IF EXISTS public."report";
DROP TABLE IF EXISTS public."notified_user";
DROP TABLE IF EXISTS public."notification";
DROP TABLE IF EXISTS public."user_in_chat";
DROP TABLE IF EXISTS public."user_in_group";
DROP TABLE IF EXISTS public."message";
DROP TABLE IF EXISTS public."chat";
DROP TABLE IF EXISTS public."comment";
DROP TABLE IF EXISTS public."image";
DROP TABLE IF EXISTS public."file";
DROP TABLE IF EXISTS public."post";
DROP TABLE IF EXISTS public."group";
DROP TABLE IF EXISTS public."event";
DROP TABLE IF EXISTS public."organization";
DROP TABLE IF EXISTS public."teacher";
DROP TABLE IF EXISTS public."student";
DROP TABLE IF EXISTS public."regular_user";
DROP TABLE IF EXISTS public."admin";
DROP TABLE IF EXISTS public."user";

DROP TYPE IF EXISTS "friendship_status";
DROP TYPE IF EXISTS status;

CREATE TYPE status AS ENUM ('normal', 'blocked', 'deleted');
CREATE TYPE "friendship_status" AS ENUM ('accepted', 'pending', 'refused');

DROP INDEX IF EXISTS "user_notified"; 
DROP INDEX IF EXISTS "notified_user_notification_id";
DROP INDEX IF EXISTS "user_email";
DROP INDEX IF EXISTS "student_regular_id";
DROP INDEX IF EXISTS "teacher_regular_id";
DROP INDEX IF EXISTS "organization_regular_id";
DROP INDEX IF EXISTS "post_author_id";
DROP INDEX IF EXISTS "post_event_id";
DROP INDEX IF EXISTS "post_group_id";
DROP INDEX IF EXISTS "comment_post_id";
DROP INDEX IF EXISTS "comment_comment_to_id";
DROP INDEX IF EXISTS "event_organizer";
DROP INDEX IF EXISTS "accepted_friendship";
DROP INDEX IF EXISTS "pending_friendship";
DROP INDEX IF EXISTS "message_chat";
DROP INDEX IF EXISTS "file_post_id";
DROP INDEX IF EXISTS "search_post_titles";
DROP INDEX IF EXISTS "search_user_names";
DROP INDEX IF EXISTS "search_group_names";
DROP INDEX IF EXISTS "search_event_names";

CREATE TABLE public."user"
(
    "user_id" serial NOT NULL,
    "name" text NOT NULL,
    "email" text NOT NULL,
    "password" text NOT NULL,
	TYPE status NOT NULL DEFAULT 'normal',
    CONSTRAINT "user_pkey" PRIMARY KEY ("user_id"),
    CONSTRAINT "user_email_key" UNIQUE ("email")
);

CREATE TABLE public."admin"
(
    "admin_id" serial NOT NULL,
	"user_id" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
    CONSTRAINT "admin_pkey" PRIMARY KEY ("admin_id")
);

CREATE TABLE public."regular_user"
(
    "regular_user_id" serial NOT NULL,
	"user_id" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	"personal_info" text,
    CONSTRAINT "regular_user_pkey" PRIMARY KEY ("regular_user_id")
);

CREATE TABLE public."student"
(
    "student_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
    CONSTRAINT "student_pkey" PRIMARY KEY ("student_id")
);

CREATE TABLE public."teacher"
(
    "teacher_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
    CONSTRAINT "teacher_pkey" PRIMARY KEY ("teacher_id")
);

CREATE TABLE public."organization"
(
    "organization_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"approval" boolean NOT NULL DEFAULT FALSE,
    CONSTRAINT "organization_pkey" PRIMARY KEY ("organization_id")
);

CREATE TABLE public."event"
(
	"event_id" serial NOT NULL,
    "organization_id" integer NOT NULL REFERENCES public."organization"("organization_id") ON DELETE CASCADE,
	"name" text NOT NULL,
	"location" text NOT NULL,
	"date" timestamp with time zone NOT NULL,
	"information" text NOT NULL,
    CONSTRAINT "event_id_pkey" PRIMARY KEY ("event_id")
);

CREATE TABLE public."group"
(
	"group_id" serial NOT NULL,
	"name" text NOT NULL,
	"information" text NOT NULL,
	TYPE status NOT NULL DEFAULT 'normal',
    CONSTRAINT "group_id_pkey" PRIMARY KEY ("group_id")
);

CREATE TABLE public."user_in_group"
(
	"user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"group_id" integer NOT NULL REFERENCES public."group"("group_id") ON DELETE CASCADE,
	"admin" boolean NOT NULL DEFAULT FALSE,
	CONSTRAINT "user_in_group_pkey" PRIMARY KEY ("user_id", "group_id")
);

CREATE TABLE public."post"
(
	"post_id" serial NOT NULL,
	"author_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"title" text NOT NULL,
	"body" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	"upvotes" integer NOT NULL DEFAULT 0,
	"downvotes" integer NOT NULL DEFAULT 0,
	TYPE status NOT NULL DEFAULT 'normal',

	
	"event_id" integer DEFAULT NULL References public."event"("event_id") ON DELETE CASCADE,
	"group_id" integer DEFAULT NULL References public."group"("group_id") ON DELETE CASCADE,
	
    CONSTRAINT "post_id_pkey" PRIMARY KEY ("post_id"),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() ),
	CONSTRAINT "upvotes_ck" CHECK ( "upvotes" >= 0 ),
	CONSTRAINT "downvotes_ck" CHECK ( "downvotes" >= 0 ),
	CONSTRAINT "bellong_ck" CHECK ( NOT ( ("event_id" IS NOT NULL) AND ( "group_id" IS NOT NULL ) )  )
);

CREATE TABLE public."file"
(
	"file_id" serial NOT NULL,
	"post_id" integer REFERENCES public."post"("post_id"),
	"file_path" text NOT NULL,
	
	CONSTRAINT "file_id_pkey" PRIMARY KEY ("file_id")
);

CREATE TABLE public."image"
(
	"image_id" serial NOT NULL,
	"file_id" integer NOT NULL REFERENCES public."file"("file_id") ON DELETE CASCADE,
	"group_id" integer DEFAULT NULL REFERENCES public."group"("group_id") ON DELETE CASCADE,
	"event_id" integer DEFAULT NULL REFERENCES public."event"("event_id") ON DELETE CASCADE,
	"regular_user_id" integer  DEFAULT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"post_id" integer DEFAULT NULL REFERENCES public."post"("post_id") ON DELETE CASCADE,
	
	CONSTRAINT "image_id_pkey" PRIMARY KEY ("image_id"),
	CONSTRAINT "bellong_ck" CHECK ( (Case when ("group_id" iS NOT NULL) then 1 else 0 end)  + 
								 	(Case when ("event_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("regular_user_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("post_id" iS NOT NULL) then 1 else 0 end) = 1)
);

CREATE TABLE public."comment"
(
	"comment_id" serial NOT NULL,
	"user_id" integer NOT NULl, --NEW
	"post_id" integer DEFAULT NULL REFERENCES public."post"("post_id") ON DELETE CASCADE,
	"comment_to_id" integer DEFAULT NULL REFERENCES public."comment"("comment_id") ON DELETE CASCADE,
	"body" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	"upvotes" integer NOT NULL DEFAULT 0,
	"downvotes" integer NOT NULL DEFAULT 0,
	
	
	CONSTRAINT "comment_id_pkey" PRIMARY KEY ("comment_id"),
	CONSTRAINT "dif_cmmt" CHECK ( "comment_id" != "comment_to_id" ),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() ),
	CONSTRAINT "upvotes_ck" CHECK ( "upvotes" >= 0 ),
	CONSTRAINT "downvotes_ck" CHECK ( "downvotes" >= 0 ),
	CONSTRAINT "bellong_ck" CHECK ( (("post_id" is NOT NULL) AND ("comment_to_id" IS NULL)) or (("post_id" is NULL) AND ("comment_to_id" IS NOT NULL)))
);

CREATE TABLE public."chat"
(
	"chat_id" serial NOT NULL,
	CONSTRAINT "chat_id_pkey" PRIMARY KEY ("chat_id")
);

CREATE TABLE public."message"
(
	"message_id" serial NOT NULL,
	"sender_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"chat_id" integer NOT NULL REFERENCES public."chat"("chat_id") ON DELETE CASCADE,
	"body" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "message_id_pkey" PRIMARY KEY ("message_id"),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() )
);

CREATE TABLE public."user_in_chat"
(
	"user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"chat_id" integer NOT NULL REFERENCES public."chat"("chat_id") ON DELETE CASCADE,
	CONSTRAINT "user_in_chat_pkey" PRIMARY KEY ("user_id", "chat_id")
);

CREATE TABLE public."notification"
(
	"notification_id" serial NOT NULL,
	"origin_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"description" text NOT NULL,
	"link" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "notification_id_pkey" PRIMARY KEY ("notification_id"),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() )
);

CREATE TABLE public."notified_user"
(
	"notification_id" integer NOT NULL REFERENCES public."notification"("notification_id") ON DELETE CASCADE,
	"user_notified" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"seen" boolean DEFAULT FALSE NOT NULL,
	CONSTRAINT "notified_user_pkey" PRIMARY KEY ("notification_id", "user_notified")
);


CREATE TABLE public."report"
(
	"report_id"  serial NOT NULL,
	"reporter_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"approval" boolean DEFAULT NULL,
	"reason" text NOT NULL,
	
	"reported_user_id" integer REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"reported_event_id" integer REFERENCES public."event"("event_id") ON DELETE CASCADE,
	"reported_post_id" integer REFERENCES public."post"("post_id") ON DELETE CASCADE,
	"reported_comment_id" integer REFERENCES public."comment"("comment_id") ON DELETE CASCADE,
	"reported_group_id" integer REFERENCES public."group"("group_id") ON DELETE CASCADE,
	
	CONSTRAINT "report_pkey" PRIMARY KEY ("report_id"),
		CONSTRAINT "bellong_ck" CHECK ( (Case when ("reported_user_id" iS NOT NULL) then 1 else 0 end)  + 
								 	(Case when ("reported_event_id" iS NOT NULL) then 1 else 0 end) + 
									(Case when ("reported_post_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("reported_comment_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("reported_group_id" iS NOT NULL) then 1 else 0 end) = 1)
);


CREATE TABLE public."friend"
(
	"friend_id1" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	"friend_id2" integer NOT NULL REFERENCES public."regular_user"("regular_user_id") ON DELETE CASCADE,
	TYPE "friendship_status" NOT NULL DEFAULT 'pending',
	CONSTRAINT "friend_pkey" PRIMARY KEY ("friend_id1", "friend_id2")
);

CREATE TABLE public."user_interested_in_event"
(
	"user_id" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	"event_id" integer NOT NULL REFERENCES public."event"("event_id") ON DELETE CASCADE,
	CONSTRAINT "user_interested_in_event_pkey" PRIMARY KEY ("user_id", "event_id")
);



-- USER NOTIFIED INDEX
CREATE INDEX "user_notified" ON "notified_user"("user_notified");
CREATE INDEX "notified_user_notification_id" ON "notified_user" USING hash("notification_id");

-- LOGIN EMAIL INDEX
CREATE INDEX "user_email" ON "user" USING hash("email");

-- REGULAR USER ID FOREIGN KEYS INDEXES
CREATE INDEX "student_regular_id" ON "student" USING hash("regular_user_id");
CREATE INDEX "teacher_regular_id" ON "teacher" USING hash("regular_user_id");
CREATE INDEX "organization_regular_id" ON "organization" USING hash("regular_user_id");
CREATE INDEX "post_author_id" ON "post" USING hash("author_id");

-- POST FOREIGN KEYS INDEXES
CREATE INDEX "post_event_id" ON "post" USING hash("event_id") WHERE "event_id" IS NOT NULL;
CREATE INDEX "post_group_id" ON "post" USING hash("group_id") WHERE "group_id" IS NOT NULL; 

-- COMMENT FOREIGN KEYS INDEXES  
CREATE INDEX "comment_post_id" ON "comment" USING hash("post_id") WHERE "post_id" IS NOT NULL;
CREATE INDEX "comment_comment_to_id" ON "comment" USING hash("comment_to_id") WHERE "comment_to_id" IS NOT NULL;

-- EVENT ORGANIZER INDEX
CREATE INDEX "event_organizer" ON "event" USING hash("organization_id");

-- FRIENDSHIP INDEX
CREATE INDEX "accepted_friendship" ON "friend" USING hash("friend_id1") WHERE TYPE = 'accepted';
CREATE INDEX "pending_friendship" ON "friend" USING hash("friend_id1") WHERE TYPE = 'pending';

-- CHAT INDEX
CREATE INDEX "message_chat" ON "message" USING hash("chat_id");

-- FILE INDEX
CREATE INDEX "file_post_id" ON "file" USING hash("post_id");

-- GIST SEARCH INDEXES 
CREATE INDEX "search_post_titles" ON "post" USING GIST(to_tsvector('english', "title"));
CREATE INDEX "search_user_names" ON "user" USING GIST(to_tsvector('english', "name"));
CREATE INDEX "search_group_names" ON "group" USING GIST(to_tsvector('english', "name"));
CREATE INDEX "search_event_names" ON "event" USING GIST(to_tsvector('english', "name"));



DROP TRIGGER IF EXISTS update_group_posts ON "group" CASCADE;
DROP TRIGGER IF EXISTS update_event_posts ON "event" CASCADE;
DROP TRIGGER IF EXISTS update_user_posts ON "user" CASCADE;
DROP TRIGGER IF EXISTS friend_status ON "friend" CASCADE;
DROP TRIGGER IF EXISTS delete_refused_report ON "report" CASCADE;
DROP TRIGGER IF EXISTS event_date ON "event" CASCADE;
DROP TRIGGER IF EXISTS post_date ON "post" CASCADE;
DROP TRIGGER IF EXISTS unique_org ON "organization" CASCADE;

DROP FUNCTION IF EXISTS update_group_posts() CASCADE;
DROP FUNCTION IF EXISTS update_event_posts() CASCADE;
DROP FUNCTION IF EXISTS update_user_posts() CASCADE;
DROP FUNCTION IF EXISTS friend_status() CASCADE;
DROP FUNCTION IF EXISTS delete_refused_report() CASCADE;
DROP FUNCTION IF EXISTS event_date() CASCADE;
DROP FUNCTION IF EXISTS post_date() CASCADE;
DROP FUNCTION IF EXISTS unique_org() CASCADE;

CREATE FUNCTION update_group_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."group".TYPE WHERE public."post"."group_id" = public."group"."group_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_group_posts
    AFTER UPDATE ON public."group"
    FOR EACH ROW
    EXECUTE PROCEDURE update_group_posts();



CREATE FUNCTION update_event_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."event".TYPE WHERE public."post"."event_id" = public."event"."event_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_event_posts
    AFTER UPDATE ON public."event"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();



CREATE FUNCTION update_user_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."user".TYPE WHERE public."post"."author_id" = public."user"."user_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_user_posts
    AFTER UPDATE ON public."user"
    FOR EACH ROW
    EXECUTE PROCEDURE update_user_posts();



CREATE FUNCTION friend_status() RETURNS trigger AS
$$
BEGIN
	IF new."friendship_status" = 'accepted' THEN
		Insert into public."friend" ("friend_id1","friend_id2","friendship_status") values (old.public."friend_id2",old.public."friend_id1",'accepted');
	ELSEIF new."friendship_status" = 'refused' THEN
		DELETE FROM public."friend" 
        WHERE ("friend_id1" = old.public."friend_id1" AND "friend_id2" = old.public."friend_id2");
    END IF; 
    RETURN NEW;
END
$$
LANGUAGE plpgsql;


Create TRIGGER friend_status
	AFTER UPDATE ON public."friend"
	EXECUTE PROCEDURE friend_status();



CREATE FUNCTION delete_refused_report() RETURNS trigger AS
$BODY$
BEGIN
    IF new."approval" = FALSE THEN
        DELETE FROM public."report"
        WHERE "report_id" = old.public."report_id";
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_refused_report
    AFTER UPDATE ON public."report"
    EXECUTE PROCEDURE delete_refused_report();



CREATE FUNCTION event_date() RETURNS trigger AS
$BODY$
BEGIN
    IF New."date" < now() THEN
    RAISE EXCEPTION 'Invalid event date.';
    END IF;
	RETURN NEW;
    

END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER event_date
    AFTER INSERT ON public."event"
	FOR EACH ROW
    EXECUTE PROCEDURE event_date();



CREATE FUNCTION post_date() RETURNS trigger AS
$BODY$
BEGIN
    IF new."date" > now() THEN
    RAISE EXCEPTION 'Invalid post date.';
    END IF;
    RETURN NEW;

END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_date
    AFTER INSERT ON public."post"
	FOR EACH ROW
    EXECUTE PROCEDURE post_date();



CREATE FUNCTION unique_org() RETURNS trigger AS
$BODY$
BEGIN
	IF new."approval" = TRUE THEN
    	IF EXISTS (Select * from
				  	(SELECT * FROM "organization" INNER JOIN "regular_user" on "organization"."regular_user_id" = "regular_user"."regular_user_id"
						INNER JOIN "user" on "regular_user"."user_id" = "user"."user_id" where "organization"."approval" = TRUE) as "t1"
				   	INNER JOIN
				   	(SELECT * FROM "organization" INNER JOIN "regular_user" on "organization"."regular_user_id" = "regular_user"."regular_user_id"
						INNER JOIN "user" on "regular_user"."user_id" = "user"."user_id" where "organization"."organization_id" = new."organization_id") as "t2"
				   	on t1."name" = "t2"."name"
				  	) 
		THEN
			RAISE EXCEPTION 'Two organizations approved with same name';
		END IF;
	END IF;
	RETURN NEW;

END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER unique_org
    BEFORE UPDATE ON public."organization"
    EXECUTE PROCEDURE unique_org();