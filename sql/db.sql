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
	"friend_id1" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	"friend_id2" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	TYPE "friendship_status" NOT NULL DEFAULT 'pending',
	CONSTRAINT "friend_pkey" PRIMARY KEY ("friend_id1", "friend_id2")
);

CREATE TABLE public."user_interested_in_event"
(
	"user_id" integer NOT NULL REFERENCES public."user"("user_id") ON DELETE CASCADE,
	"event_id" integer NOT NULL REFERENCES public."event"("event_id") ON DELETE CASCADE,
	CONSTRAINT "user_interested_in_event_pkey" PRIMARY KEY ("user_id", "event_id")
);