DROP TABLE IF EXISTS public."notified_user";
DROP TABLE IF EXISTS public."notification";
DROP TABLE IF EXISTS public."user_in_chat";
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

CREATE TABLE public."user"
(
    "user_id" serial NOT NULL,
    "name" text NOT NULL,
    "email" text NOT NULL,
    "password" text NOT NULL,
    CONSTRAINT "user_pkey" PRIMARY KEY ("user_id"),
    CONSTRAINT "user_email_key" UNIQUE ("email")
);

CREATE TABLE public."admin"
(
    "admin_id" serial NOT NULL,
	"user_id" integer NOT NULL REFERENCES public."user"("user_id"),
    CONSTRAINT "admin_pkey" PRIMARY KEY ("admin_id")
);

CREATE TABLE public."regular_user"
(
    "regular_user_id" serial NOT NULL,
	"user_id" integer NOT NULL REFERENCES public."user"("user_id"),
	"personal_info" text,
    CONSTRAINT "regular_user_pkey" PRIMARY KEY ("regular_user_id")
);

CREATE TABLE public."student"
(
    "student_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
    CONSTRAINT "student_pkey" PRIMARY KEY ("student_id")
);

CREATE TABLE public."teacher"
(
    "teacher_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
    CONSTRAINT "teacher_pkey" PRIMARY KEY ("teacher_id")
);

CREATE TABLE public."organization"
(
    "organization_id" serial NOT NULL,
	"regular_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"approval" boolean NOT NULL DEFAULT FALSE,
    CONSTRAINT "organization_pkey" PRIMARY KEY ("organization_id")
);

CREATE TABLE public."event"
(
	"event_id" serial NOT NULL,
    "organization_id" integer NOT NULL REFERENCES public."organization"("organization_id"),
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
    CONSTRAINT "group_id_pkey" PRIMARY KEY ("group_id")
);


CREATE TABLE public."post"
(
	"post_id" serial NOT NULL,
	"author_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"title" text NOT NULL,
	"body" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	"upvotes" integer NOT NULL DEFAULT 0,
	"downvotes" integer NOT NULL DEFAULT 0,
	
	"event_id" integer DEFAULT NULL References public."event"("event_id"),
	"group_id" integer DEFAULT NULL References public."group"("group_id"),
	
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
	"file_id" integer NOT NULL REFERENCES public."file"("file_id"),
	"group_id" integer DEFAULT NULL REFERENCES public."group"("group_id"),
	"event_id" integer DEFAULT NULL REFERENCES public."event"("event_id"),
	"regular_user_id" integer  DEFAULT NULL REFERENCES public."regular_user"("regular_user_id"),
	"post_id" integer DEFAULT NULL REFERENCES public."post"("post_id"),
	
	CONSTRAINT "image_id_pkey" PRIMARY KEY ("image_id"),
	CONSTRAINT "bellong_ck" CHECK ( (Case when ("group_id" iS NOT NULL) then 1 else 0 end)  + 
								 	(Case when ("event_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("regular_user_id" iS NOT NULL) then 1 else 0 end) + 
								    (Case when ("post_id" iS NOT NULL) then 1 else 0 end) = 1)
);

CREATE TABLE public."comment"
(
	"comment_id" serial NOT NULL,
	"post_id" integer DEFAULT NULL REFERENCES public."post"("post_id"),
	"comment_to_id" integer DEFAULT NULL REFERENCES public."comment"("comment_id"),
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
	"chat_id" integer NOT NULL,
	CONSTRAINT "chat_id_pkey" PRIMARY KEY ("chat_id")
);

CREATE TABLE public."message"
(
	"message_id" serial NOT NULL,
	"sender_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"chat_id" integer NOT NULL REFERENCES public."chat"("chat_id"),
	"body" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "message_id_pkey" PRIMARY KEY ("message_id"),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() )
);

CREATE TABLE public."user_in_chat"
(
	"user_id" serial NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"chat_id" integer NOT NULL REFERENCES public."chat"("chat_id"),
	CONSTRAINT "user_in_chat_pkey" PRIMARY KEY ("user_id", "chat_id")
);

CREATE TABLE public."notification"
(
	"notification_id" serial NOT NULL,
	"origin_user_id" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"description" text NOT NULL,
	"link" text NOT NULL,
	"date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "notification_id_pkey" PRIMARY KEY ("notification_id"),
	CONSTRAINT "date_ck" CHECK ( "date" <= now() )
);

CREATE TABLE public."notified_user"
(
	"notification_id" serial NOT NULL REFERENCES public."notification"("notification_id"),
	"user_notified" integer NOT NULL REFERENCES public."regular_user"("regular_user_id"),
	"seen" boolean DEFAULT FALSE NOT NULL,
	CONSTRAINT "notified_user_pkey" PRIMARY KEY ("notification_id", "user_notified")
);


