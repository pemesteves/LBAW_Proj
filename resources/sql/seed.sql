DROP TABLE IF EXISTS public."reset_pass";
DROP TABLE IF EXISTS public."user_reaction";
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
DROP INDEX IF EXISTS "comment_date";
DROP INDEX IF EXISTS "message_date";
DROP INDEX IF EXISTS "post_date";
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

	"userable_id" integer,
	"userable_type" text,

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
	"university" text NOT NULL,

	"regular_userable_id" integer,
	"regular_userable_type" text,

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
	"updated_at" timestamp with time zone NOT NULL DEFAULT now(),
	"created_at" timestamp with time zone NOT NULL DEFAULT now(),
	TYPE status NOT NULL DEFAULT 'normal',
    CONSTRAINT "event_id_pkey" PRIMARY KEY ("event_id")
);

CREATE TABLE public."group"
(
	"group_id" serial NOT NULL,
	"name" text NOT NULL,
	"information" text NOT NULL,
	"updated_at" timestamp with time zone NOT NULL DEFAULT now(),
	"created_at" timestamp with time zone NOT NULL DEFAULT now(),
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
	TYPE status NOT NULL DEFAULT 'normal',
	
	
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
	"chat_name" text,
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
	"title" text NOT NULL,
	"reason" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	
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

CREATE TABLE public."user_reaction"
(
	"user_id" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	"post_id" integer NOT NULL REFERENCES public."post"("post_id") ON DELETE CASCADE,
	"like_or_dislike" integer NOT NULL, -- 1 for like, 0 for dislike
	CONSTRAINT "user_reaction_pkey" PRIMARY KEY ("user_id", "post_id")
);

CREATE TABLE public."reset_pass"
(
	"reset_pass_id" serial,
	"email" text NOT NULL,
	"token" text NOT NULL,
	CONSTRAINT "reset_pass_pkey" PRIMARY KEY ("reset_pass_id")
);


-- USER NOTIFIED INDEX
CREATE INDEX "user_notified" ON "notified_user" USING hash("user_notified");
CREATE INDEX "notified_user_notification_id" ON "notified_user" USING hash("notification_id");

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

-- DATE INDEXES
CREATE INDEX "message_date" ON "message" USING btree("date");
CREATE INDEX "comment_date" ON "comment" USING btree("date");
CREATE INDEX "post_date" ON "post" USING btree("date");

-- GIST SEARCH INDEXES 
CREATE INDEX "search_post_titles" ON "post" USING GIST(to_tsvector('english', "title" || ' ' || "body"));
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
DROP TRIGGER IF EXISTS event_update_at ON "event" CASCADE;
DROP TRIGGER IF EXISTS group_update_at ON "group" CASCADE;

DROP FUNCTION IF EXISTS update_group_posts() CASCADE;
DROP FUNCTION IF EXISTS update_event_posts() CASCADE;
DROP FUNCTION IF EXISTS update_user_posts() CASCADE;
DROP FUNCTION IF EXISTS friend_status() CASCADE;
DROP FUNCTION IF EXISTS delete_refused_report() CASCADE;
DROP FUNCTION IF EXISTS event_date() CASCADE;
DROP FUNCTION IF EXISTS post_date() CASCADE;
DROP FUNCTION IF EXISTS unique_org() CASCADE;
DROP FUNCTION IF EXISTS update_at() CASCADE;

CREATE FUNCTION update_group_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."group".TYPE WHERE public."post"."group_id" = public."group"."group_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_group_posts
    AFTER UPDATE OF TYPE ON public."group"
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
    AFTER UPDATE OF TYPE ON public."event"
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
    AFTER UPDATE OF TYPE ON public."user"
    FOR EACH ROW
    EXECUTE PROCEDURE update_user_posts();



CREATE FUNCTION friend_status() RETURNS trigger AS
$$
BEGIN
	IF NEW."type" = 'accepted'::friendship_status THEN
		Insert INTO public."friend" ("friend_id1","friend_id2","type") values (OLD."friend_id2",OLD."friend_id1",'accepted');
	ELSEIF NEW."type" = 'refused'::friendship_status THEN
		DELETE FROM public."friend" 
        WHERE ("friend_id1" = OLD."friend_id1" AND "friend_id2" = OLD."friend_id2");
    END IF; 
    RETURN NEW;
END
$$
LANGUAGE plpgsql;


Create TRIGGER friend_status
	AFTER UPDATE ON public."friend"
	FOR EACH ROW
	EXECUTE PROCEDURE friend_status();




CREATE FUNCTION delete_refused_report() RETURNS trigger AS
$BODY$
BEGIN
    IF New."approval" = FALSE THEN
        DELETE FROM public."report"
        WHERE "report_id" = Old."report_id";
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER delete_refused_report
    AFTER UPDATE ON public."report"
	FOR EACH ROW
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


CREATE FUNCTION update_at() RETURNS trigger AS
$BODY$
BEGIN
    New."updated_at" = now();
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER event_update_at
    AFTER UPDATE ON public."event"
	FOR EACH ROW
    EXECUTE PROCEDURE update_at();

CREATE TRIGGER group_update_at
    AFTER UPDATE ON public."group"
	FOR EACH ROW
    EXECUTE PROCEDURE update_at();



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


insert into public."user" ("name", "email", "password","userable_id","userable_type") values('Admin1', 'admin1@gg.pt', '$2y$12$4c4ki2eoJHMW75uuaFffLe6yEiUsbtomUOQErBwgp7hmeV5DfVzPa',1,'App\Admin'); --admin
insert into public."user" ("name", "email", "password","userable_id","userable_type") values('Joaquim Rodrigues', 'jokinho@feup.pt', '$2y$12$YD2mkOUiJDXykPGe2bHw6ugb14EBPlTK6.Nf7QTnDEz2Su19tW7EW',1,'App\RegularUser'); --aaaa
insert into public."user" ("name", "email", "password","userable_id","userable_type") values('Paulo Tavares', 'paulot@flup.pt', '$2y$12$v4q.gyOIG6xdFbhoq8dcsuhoIvyWvHYD8DJtLMi0vX5B1r..3M7N2',2,'App\RegularUser'); --1234pass
insert into public."user" ("name", "email", "password","userable_id","userable_type") values('AEISEP', 'aeisep@isep.pt', '$2y$12$zXXPUS2k6hPJ4u/Ue9lLuesoKfJMKIBspDTejMvQGyoKvZ1kNbV8u',3,'App\RegularUser'); --yoyo10
insert into public."user" ("name", "email", "password","userable_id","userable_type") values('Gustavo Torres', 'tgusta@feup.pt', '$2y$12$ydeNcQ4mOr1HLYnooq2Xuu5ZLYMla7ZIEBaQiN08UekSJ0WvAqdve',4,'App\RegularUser'); --tgustamucho
insert into public."user" ("name", "email", "password","userable_id","userable_type") values('Pedro Esteves', 'pmesteves@feup.pt', '$2y$12$braCBZULabp3zeMXJHAmDeClfXk9myM/4LovIm/UES5Kgk8HR8Isy',5,'App\RegularUser'); --estevesboss
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Vitor Ventuzelos', 'berserking@feup.pt', '$2y$12$mAQnlXAoBWvwe..Oym7pmuRBsbw2Xtqcbt75hjxuYPH9ll3n/XhlC',6,'App\RegularUser'); --berserking_idiot
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('José Martins', 'martins@flup.pt', '$2y$12$csw8WDrgXzAay3jiFdxYIOmpECmceiXvyOK1AECLSFyXuZnQsusuO',7,'App\RegularUser'); --martins
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Marta Camões', 'mcamoes@fcup.pt', '$2y$12$0l0WKBud6d/WPJUH/7ceye1JaEGaOWMsAaihzl/EnRQ2rSsOhmrMa',8,'App\RegularUser'); --123p
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Diana Magalhães', 'dmaga@flup.pt', '$2y$12$U8cNTlV7AwpXvn5uJOk9euWg8TPizGb4tE.n1EQUobcb1A/ySy3nW',9,'App\RegularUser'); --password12
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Tiago Pessoa', 'tpessoa@isep.pt', '$2y$12$vufeANKaqUnvw0qhTY8m0uqnvcJ8oz22xX0GYX6pZWGk8nJK9dFAy',10,'App\RegularUser'); --hacked91
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Ricardo Pinto', 'rpinto@fmup.pt', '$2y$12$EvueAIWOHfCnNBRs5kzF8uaN3breD8yb1AmcQxl47gl0TPsRMWb/2',11,'App\RegularUser'); --debugger99
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Maria Soares', 'msoares@icbas.pt', '$2y$12$birxlOQC5ZjVdgM01lqrfOrbh/xKcvJTDsJ.g0kZz6g6VImGoKAgK',12,'App\RegularUser'); --12345p
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Francisco Costa', 'fcosta@ffup.pt', '$2y$12$gZefsrh9Vw2HV6xhtIbSsuinFsRZAhJUuTyNfY9oWCUHgscv2V94K',13,'App\RegularUser'); --pass2
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('José Silva', 'runcolho@fadeup.pt', '$2y$12$nghMwiWQGwiq5PnD1APOZer8ARVAWUgVMV0eOR.wtYnl04yEt9TQm',14,'App\RegularUser'); --pass1
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Miguel Alvim', 'malvim21@fadeup.pt', '$2y$12$3LK/1hPOKHizdFXR0DPUcemsDtKVaIblJPlZqjMDmbJ6YTydMDD8S',15,'App\RegularUser'); --pass0
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Dinis Pereira', 'dinpereira@fmup.pt', '$2y$12$FlACpgGQRMEZ8eKaPXuesu48Sck0ddmXiGx.IeN5Zsa92YgH6BYTC',16,'App\RegularUser'); --lbaw2034
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Soraia Tavares', 'stavares@ffup.pt', '$2y$12$LS1C2bq2avu4KQrRftIC.u9NFiRFNyr4CFCmWYMPB0ujhm743w6ki',17,'App\RegularUser'); --lbaw
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Osvaldo Antunes', 'oantunes@fadeup.pt', '$2y$12$A/LUWLcMsPHJJYgfsmruMeYFmVyGnJXsg1IP3OFDS5fhrFr4Kubde',18,'App\RegularUser'); --query10
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Mariana Castro', 'mcastro@fcup.pt', '$2y$12$HSHLu.dkd9b73IWz4LQAgOS9C4l3V2tJfQKAcK1Cz.aHyi8krJFpW',19,'App\RegularUser'); --sql2
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Ana Faria', 'afaria@fep.pt', '$2y$12$fE4X/GTixS845Xx4YwjAo.a.e9LC7Ay7vba./gQHbeT/ONecrbkXm',20,'App\RegularUser'); --turbulencia2725
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Rui Cardoso', 'rcardoso@fep.pt', '$2y$12$U8sMy44W0FPraRQ5bAdjtOd91ouvK9tnrAvn62MOPUl1E.OWFfsHy',21,'App\RegularUser'); --bazofi10
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('AEFEUP', 'aefeup@feup.pt', '$2y$12$Oo8YqVMNXx5M2a47bWjeHOl11KzF94DYs0AsJLf8hC8un3p2RK2HG',22,'App\RegularUser'); --gustavoboss
insert into public."user" ("name", "email", "password","userable_id","userable_type") values ('Admin2', 'admin2@admins.pt', '$2y$12$BUZvInYL8iZR.N.qu59NIOGr8asY3vlML2KaRIVAJBkQ6IS4odm6m',2,'App\Admin'); --adminGang


insert into public."admin" ("user_id") values (1);
insert into public."admin" ("user_id") values (24);


insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (2, 'Just one regular user!',1,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (3, 'Just another regular user!',2,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (4, 'Another one!',3,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (5, 'DB user',4,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (6, 'GameJam user',5,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (7, 'Another one!',6,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (8, 'FLUP student',7,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (9, 'FCUP teacher',1,'App\Teacher', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (10, 'FLUP student',8,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (11, 'I am an ISEP teacher',2,'App\Teacher', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (12, 'FMUP student',9,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (13, 'ICBAS student',10,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (14, 'FFUP student',11,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (15, 'Sports student',12,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (16, 'FADEUP teacher',3,'App\Teacher', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (17, 'FMUP student',13,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (18, 'Pharmacy student',14,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (19, 'Sports student',15,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (20, 'Science student',16,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (21, 'FEP student',17,'App\Student', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (22, 'FEP teacher',4,'App\Teacher', 'FEUP');
insert into public."regular_user" ("user_id", "personal_info","regular_userable_id","regular_userable_type", "university") values (23, 'Aefeup',1,'App\Organization', 'FEUP');


insert into public."student" ("regular_user_id") values (1);
insert into public."student" ("regular_user_id") values (2);
insert into public."student" ("regular_user_id") values (3);
insert into public."student" ("regular_user_id") values (4);
insert into public."student" ("regular_user_id") values (5);
insert into public."student" ("regular_user_id") values (6);
insert into public."student" ("regular_user_id") values (7);
insert into public."student" ("regular_user_id") values (9);
insert into public."student" ("regular_user_id") values (11);
insert into public."student" ("regular_user_id") values (12);
insert into public."student" ("regular_user_id") values (13);
insert into public."student" ("regular_user_id") values (14);
insert into public."student" ("regular_user_id") values (16);
insert into public."student" ("regular_user_id") values (17);
insert into public."student" ("regular_user_id") values (18);
insert into public."student" ("regular_user_id") values (19);
insert into public."student" ("regular_user_id") values (20);


insert into public."teacher" ("regular_user_id") values (8);
insert into public."teacher" ("regular_user_id") values (10);
insert into public."teacher" ("regular_user_id") values (15);
insert into public."teacher" ("regular_user_id") values (21);


insert into public."organization" ("regular_user_id", "approval") values (22, TRUE);


insert into public."event" ("organization_id", "name", "location", "date", "information") values (1, 'Evento de LBAW', 'Porto', '2020-06-29 17:45:00', 'general info');


insert into public."group" ("name", "information", TYPE) values ('Grupo de LBAW', 'Grupo para os estudantes de LBAW', 'normal');
insert into public."group" ("name", "information", TYPE) values ('UP/IPP Group', 'Estudantes do Porto', 'normal');


insert into public."user_in_group" ("user_id", "group_id") values (2, 1);
insert into public."user_in_group" ("user_id", "group_id") values (5, 1);
insert into public."user_in_group" ("user_id", "group_id") values (6, 1);
insert into public."user_in_group" ("user_id", "group_id") values (7, 1);
insert into public."user_in_group" ("user_id", "group_id") values (2, 2);
insert into public."user_in_group" ("user_id", "group_id") values (5, 2);
insert into public."user_in_group" ("user_id", "group_id") values (6, 2);
insert into public."user_in_group" ("user_id", "group_id") values (7, 2);
insert into public."user_in_group" ("user_id", "group_id") values (8, 2);
insert into public."user_in_group" ("user_id", "group_id") values (9, 2);
insert into public."user_in_group" ("user_id", "group_id") values (10, 2);
insert into public."user_in_group" ("user_id", "group_id") values (12, 2);
insert into public."user_in_group" ("user_id", "group_id") values (13, 2);
insert into public."user_in_group" ("user_id", "group_id") values (14, 2);
insert into public."user_in_group" ("user_id", "group_id") values (15, 2);
insert into public."user_in_group" ("user_id", "group_id") values (17, 2);
insert into public."user_in_group" ("user_id", "group_id") values (18, 2);
insert into public."user_in_group" ("user_id", "group_id") values (19, 2);
insert into public."user_in_group" ("user_id", "group_id") values (20, 2);
insert into public."user_in_group" ("user_id", "group_id") values (21, 2);


insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (2, 'FEUP LIFE', 'Gosto de estudar na FEUP', '2020-02-20 17:45:00', 4, 3, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (2, 'Aula de LBAW', 'Na próxima aula de LBAW temos que entregar todos os artefactos desta componente?', '2020-02-24 10:38:43', 2, 0, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (2, 'Trabalho COMP', 'Alguém da turma 4 necessita de um elemento para a realização do trabalho 1 de Compiladores?', '2020-03-05 15:50:11', 5, 1, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (1, 'Inquéritos FLUP', 'Conseguem todos preencher os inquéritos lançados pela faculdade?', '2020-03-10 08:31:06', 10, 0, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (3, 'Quarentena', 'Pedimos a todos os alunos da Academia, em especial os do ISEP para ficarem em casa durante este período de quarentena, seguindo as indicações da DGS.', '2020-03-13 16:54:51', 40, 1, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (6, 'Grupo para LBAW', 'Bom dia pessoal, criei este grupo para ser mais facil partilharmos os ficheiros para LBAW. Abraço', '2020-03-12 09:21:41', 3, 0, DEFAULT, NULL, 1);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (10, 'Ficheiro de teste', 'Este post serve para testar o sistema de ficheiros.', '2020-03-20 21:23:44', 0, 0, DEFAULT, NULL, NULL);
insert into public."post" ("author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (12, 'Imagem de teste', 'Este post serve para testar o sistema de imagens.', '2020-03-20 22:31:22', 0, 0, DEFAULT, 1, NULL);


insert into public."comment" ("user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (5, 2, NULL, 'Não, essa entrega tem uma data específica, verifica no Moodle', '2020-02-24 11:03:22', 1, 0);
insert into public."comment" ("user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (6, 5, NULL, 'Otima iniciativa', '2020-03-14 16:31:19', 5, 0);
insert into public."comment" ("user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (8, 4, NULL, 'Está preenchido, professor', '2020-03-10 10:23:44', 1, 0);


insert into public."chat"("chat_name") VALUES('grupo de LBAW') ;
insert into public."chat"("chat_name") VALUES('um chat qualquer') ;


insert into public."user_in_chat" ("user_id", "chat_id") values (2,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (5,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (6,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (7,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (3,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (9,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (11,1);
insert into public."user_in_chat" ("user_id", "chat_id") values (16,1);


insert into public."message" ("sender_id", "chat_id", "body", "date") values (2, 1, 'Boas pessoal, este vai ser o chat de LBAW', '2020-03-20 14:22:55');
insert into public."message" ("sender_id", "chat_id", "body", "date") values (5, 1, 'Boas!', '2020-03-20 14:30:00');
insert into public."message" ("sender_id", "chat_id", "body", "date") values (6, 1, 'Então, tudo bem?', '2020-03-20 15:02:21');
insert into public."message" ("sender_id", "chat_id", "body", "date") values (7, 1, 'Mãos à obra', '2020-03-20 15:03:41');
insert into public."message" ("sender_id", "chat_id", "body", "date") values (9, 2, 'Criei este chat para todos os professores se poderem manter em contacto', '2020-03-22 21:16:47');


insert into public."report" ("reporter_id", "approval", "title", "reason", "reported_user_id", "reported_event_id", "reported_post_id", "reported_comment_id", "reported_group_id") values (3, NULL, 'Reportando a ferramenta', 'so para testar a ferramenta', NULL, NULL, 1, NULL, NULL);
insert into public."report" ("reporter_id", "approval", "title", "reason", "reported_user_id", "reported_event_id", "reported_post_id", "reported_comment_id", "reported_group_id") values (2, NULL, 'Não gosto da diversidade', 'pouco conteudo', NULL, NULL, NULL, 2, NULL);


insert into public."file" ("post_id", "file_path") values ( 7,'../files/test.txt');
insert into public."file" ("post_id", "file_path") values ( 8,'../files/image.png');


insert into public."image" ("file_id", "post_id") values (2, 8);


insert into public."notification" ("origin_user_id", "description", "link", "date") values (3, 'I just posted!', '../feed.php#5', '2020-03-21 12:00:00');
insert into public."notification" ("origin_user_id", "description", "link", "date") values (5, 'New message in chat!', '../chat/1', '2020-03-25 14:12:45');
insert into public."notification" ("origin_user_id", "description", "link", "date") values (7, 'New message in chat!', '../chat/1', '2020-03-27 18:54:31');



insert into public."notified_user" ("notification_id", "user_notified") values (1, 5);
insert into public."notified_user" ("notification_id", "user_notified") values (1, 7);
insert into public."notified_user" ("notification_id", "user_notified") values (1, 10);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 2);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 6);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 7);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 3);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 9);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 11);
insert into public."notified_user" ("notification_id", "user_notified") values (2, 16);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 2);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 6);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 7);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 3);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 9);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 11);
insert into public."notified_user" ("notification_id", "user_notified") values (3, 16);



insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (1, 4, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (1, 15, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (1, 18, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 3, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (3, 2, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 4, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (4, 2, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 5, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (5, 2, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 6, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (6, 2, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 7, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (7, 2, 'accepted');
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 8, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (2, 10, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (4, 3, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (4, 6, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (5, 7, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (5, 8, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (6, 8, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (6, 19, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (7, 12, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (7, 17, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (8, 11, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (8, 10, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (10, 20, DEFAULT);
insert into public."friend" ("friend_id1", "friend_id2", TYPE ) values (14, 2, DEFAULT);


insert into public."user_interested_in_event" ("user_id","event_id") values(2,1);
insert into public."user_interested_in_event" ("user_id","event_id") values(4,1);
insert into public."user_interested_in_event" ("user_id","event_id") values(7,1);
insert into public."user_interested_in_event" ("user_id","event_id") values(10,1);