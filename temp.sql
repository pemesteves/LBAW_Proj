DROP TABLE IF EXISTS public."Notified_User";
DROP TABLE IF EXISTS public."Notification";
DROP TABLE IF EXISTS public."User_In_Chat";
DROP TABLE IF EXISTS public."Message";
DROP TABLE IF EXISTS public."Chat";
DROP TABLE IF EXISTS public."Comment";
DROP TABLE IF EXISTS public."Image";
DROP TABLE IF EXISTS public."File";
DROP TABLE IF EXISTS public."Post";
DROP TABLE IF EXISTS public."Group";
DROP TABLE IF EXISTS public."Event";
DROP TABLE IF EXISTS public."Organization";
DROP TABLE IF EXISTS public."Teacher";
DROP TABLE IF EXISTS public."Student";
DROP TABLE IF EXISTS public."Regular_User";
DROP TABLE IF EXISTS public."Admin";
DROP TABLE IF EXISTS public."User";

CREATE TABLE public."User"
(
    "User_id" integer NOT NULL,
    "Name" text NOT NULL,
    "Email" text NOT NULL,
    "Password" text NOT NULL,
    CONSTRAINT "User_pkey" PRIMARY KEY ("User_id"),
    CONSTRAINT "User_Email_key" UNIQUE ("Email")
);

CREATE TABLE public."Admin"
(
    "Admin_id" integer NOT NULL,
	"User_id" integer NOT NULL REFERENCES public."User",
    CONSTRAINT "Admin_pkey" PRIMARY KEY ("Admin_id")
);

CREATE TABLE public."Regular_User"
(
    "Regular_User_id" integer NOT NULL,
	"User_id" integer NOT NULL REFERENCES public."User",
	"Personal_Info" text,
    CONSTRAINT "Regular_User_pkey" PRIMARY KEY ("Regular_User_id")
);

CREATE TABLE public."Student"
(
    "Student_id" integer NOT NULL,
	"Regular_User_id" integer NOT NULL REFERENCES public."Regular_User",
    CONSTRAINT "Student_pkey" PRIMARY KEY ("Student_id")
);

CREATE TABLE public."Teacher"
(
    "Teacher_id" integer NOT NULL,
	"Regular_User_id" integer NOT NULL REFERENCES public."Regular_User",
    CONSTRAINT "Teacher_pkey" PRIMARY KEY ("Teacher_id")
);

CREATE TABLE public."Organization"
(
    "Organization_id" integer NOT NULL,
	"Regular_User_id" integer NOT NULL REFERENCES public."Regular_User",
	"Approval" boolean NOT NULL DEFAULT FALSE,
    CONSTRAINT "Organization_pkey" PRIMARY KEY ("Organization_id")
);

CREATE TABLE public."Event"
(
	"Event_id" integer NOT NULL,
    "Organization_id" integer NOT NULL REFERENCES public."Organization",
	"Name" text NOT NULL,
	"Location" text NOT NULL,
	"Date" timestamp with time zone NOT NULL,
	"Information" text NOT NULL,
    CONSTRAINT "Event_id_pkey" PRIMARY KEY ("Event_id")
);

CREATE TABLE public."Group"
(
	"Group_id" integer NOT NULL,
	"Name" text NOT NULL,
	"Information" text NOT NULL,
    CONSTRAINT "Group_id_pkey" PRIMARY KEY ("Group_id")
);


CREATE TABLE public."Post"
(
	"Post_id" integer NOT NULL,
	"Author_id" integer NOT NULL REFERENCES public."Regular_User",
	"Title" text NOT NULL,
	"Body" text NOT NULL,
	"Date" timestamp with time zone NOT NULL DEFAULT now(),
	"Upvotes" integer NOT NULL DEFAULT 0,
	"Downvotes" integer NOT NULL DEFAULT 0,
	
	"Event_id" integer DEFAULT NULL References public."Event",
	"Group_id" integer DEFAULT NULL References public."Group",
	
    CONSTRAINT "Post_id_pkey" PRIMARY KEY ("Post_id"),
	CONSTRAINT "Date_ck" CHECK ( "Date" <= now() ),
	CONSTRAINT "Upvotes_ck" CHECK ( "Upvotes" >= 0 ),
	CONSTRAINT "Downvotes_ck" CHECK ( "Downvotes" >= 0 ),
	CONSTRAINT "Bellong_ck" CHECK ( NOT ( ("Event_id" IS NOT NULL) AND ( "Group_id" IS NOT NULL ) )  )
);

CREATE TABLE public."File"
(
	"File_id" integer NOT NULL,
	"Post_id" integer REFERENCES public."Post",
	"File" BYTEA NOT NULL, -- BYTEA == BLOB
	
	CONSTRAINT "File_id_pkey" PRIMARY KEY ("File_id")
);

CREATE TABLE public."Image"
(
	"Image_id" integer NOT NULL,
	"File_id" integer NOT NULL REFERENCES public."File",
	"Group_id" integer DEFAULT NULL REFERENCES public."Group",
	"Event_id" integer DEFAULT NULL REFERENCES public."Event",
	"Regular_user_id" integer  DEFAULT NULL REFERENCES public."Regular_User",
	"Post_id" integer DEFAULT NULL REFERENCES public."Post",
	
	CONSTRAINT "Image_id_pkey" PRIMARY KEY ("Image_id"),
	-- FAZER XOR ENTRE TODOS GOOD LUCK
);

CREATE TABLE public."Comment"
(
	"Comment_id" integer NOT NULL,
	"Post_id" integer DEFAULT NULL REFERENCES public."Post",
	"Comment_to_id" integer DEFAULT NULL REFERENCES public."Comment",
	"Body" text NOT NULL,
	"Date" timestamp with time zone NOT NULL DEFAULT now(),
	"Upvotes" integer NOT NULL DEFAULT 0,
	"Downvotes" integer NOT NULL DEFAULT 0,
	
	
	CONSTRAINT "Comment_id_pkey" PRIMARY KEY ("Comment_id"),
	CONSTRAINT "Dif_cmmt" CHECK ( "Comment_id" != "Comment_to_id" ),
	CONSTRAINT "Date_ck" CHECK ( "Date" <= now() ),
	CONSTRAINT "Upvotes_ck" CHECK ( "Upvotes" >= 0 ),
	CONSTRAINT "Downvotes_ck" CHECK ( "Downvotes" >= 0 ),
	CONSTRAINT "Bellong_ck" CHECK ( (("Post_id" is NOT NULL) AND ("Comment_to_id" IS NULL)) or (("Post_id" is NULL) AND ("Comment_to_id" IS NOT NULL)))
);

CREATE TABLE public."Chat"
(
	"Chat_id" integer NOT NULL,
	CONSTRAINT "Chat_id_pkey" PRIMARY KEY ("Chat_id")
);

CREATE TABLE public."Message"
(
	"Message_id" integer NOT NULL,
	"Sender_id" integer NOT NULL REFERENCES public."Regular_User",
	"Chat_id" integer NOT NULL REFERENCES public."Chat",
	"Body" text NOT NULL,
	"Date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "Message_id_pkey" PRIMARY KEY ("Message_id"),
	CONSTRAINT "Date_ck" CHECK ( "Date" <= now() )
);

CREATE TABLE public."User_In_Chat"
(
	"User_id" integer NOT NULL REFERENCES public."Regular_User",
	"Chat_id" integer NOT NULL REFERENCES public."Chat"
);

CREATE TABLE public."Notification"
(
	"Notification_id" integer NOT NULL,
	"Origin_user_id" integer NOT NULL REFERENCES public."Regular_User",
	"Description" text NOT NULL,
	"Link" text NOT NULL,
	"Date" timestamp with time zone NOT NULL DEFAULT now(),
	
	CONSTRAINT "Notification_id_pkey" PRIMARY KEY ("Notification_id"),
	CONSTRAINT "Date_ck" CHECK ( "Date" <= now() )
);

CREATE TABLE public."Notified_User"
(
	"Notification_id" integer NOT NULL REFERENCES public."Notification",
	"User_notified" integer NOT NULL REFERENCES public."Regular_User",
	"Seen" boolean DEFAULT FALSE NOT NULL
);

