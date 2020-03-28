
-- my info
Select "user_id", "name" from "user" where "email" = 'jokinho@feup.pt';


-- if is admin
Select "admin_id" from "admin" where "user_id" = 24;

-- if is regular_user
Select "regular_user_id", "personal_info" from "regular_user" where "user_id" = 4;

--if is a student
Select "student_id" from "student" where "regular_user_id" = 5;

-- if is a teacher
Select "teacher_id" from "teacher" where "regular_user_id" = 3;

-- if is an organization
Select "organization_id" from "organization" where "regular_user_id" = 4;


-- Select user from a group
Select "user_id" from "user_in_group" where "group_id" = 1;

--events of organization
Select "name", "location", "date", "information" from "event" where "organization_id" = 1;


--Posts posted by me
Select "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id" 
	from "post" where "author_id" = 2;


--Notification
Select "origin_user_id", "description", "link", "date" from "notification"
	INNER JOIN "notified_user" on "notification"."notification_id" ="notified_user"."notification_id" 
	where "user_notified" = 5;


--Message by chat
SELECT "sender_id", "body", "date"
	FROM "message" 
	where "chat_id" = $chatId
	ORDER BY "date" DESC;


--Comments
 Select "comment_id", "user_id", "comment_to_id", "body", "date", "upvotes", "downvotes"
	from "comment" where "post_id" = 5
	order by "date" DESC;


--Posts from event
Select "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id" 
	from "post" where "event_id" = 1;

--Posts from group
Select "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id" 
	from "post" where "group_id" = 1;


	
-- Select all regular users with starting name like...
Select "name" , "user"."user_id" , "regular_user"."regular_user_id" from "user" 
	INNER JOIN "regular_user" on "regular_user"."user_id" = "user"."user_id"  
	where "user"."name" LIKE 'A%';

-- Select Reports that have no answer
Select "reporter_id", "approval", "reason", "reported_user_id", "reported_event_id", "reported_post_id", "reported_comment_id", "reported_group_id"
	from "report" where "approval" IS NULL;


--Select friends
Select "friend_id2"
	from "friend" where (TYPE = 'accepted' and "friend_id1" = 2);


--Select pending requests (the user that hasn't answered)
Select "friend_id2"
	from "friend" where (TYPE = 'pending' and "friend_id1" = 2);
	

--Files path from Posts

SELECT "file_path"
	FROM "file" 
	INNER JOIN "post" ON "post"."post_id" = "file"."post_id" 
	WHERE "post"."post_id" = $postId;


--Posts from groups I belong, users I am friends with and events I am interested on (Feed)
Select "author_id", "title", "body", "date", "upvotes", "downvotes", "post".TYPE, "event_id", "post"."group_id" 
	from "post" INNER JOIN "user_in_group" on "post"."group_id" = "user_in_group"."group_id" 
	where "user_in_group"."user_id" = 2
UNION
Select "author_id", "title", "body", "date", "upvotes", "downvotes", "post".TYPE, "event_id", "post"."group_id" 
	from "post" INNER JOIN "friend" on "friend"."friend_id2" = "post"."author_id" 
	WHERE "friend".TYPE = 'accepted' AND "friend"."friend_id1" = 2
UNION
Select "author_id", "title", "body", "date", "upvotes", "downvotes", "post".TYPE, "post"."event_id", "post"."group_id" 
	from "post" INNER JOIN "user_interested_in_event" 
	on "post"."event_id" = "user_interested_in_event"."event_id"
	WHERE "user_interested_in_event"."user_id" = 2;
	

--Event informations of events I am interested on
Select "event"."event_id", "organization_id", "name", "location", "date", "information"
	FROM "event" INNER JOIN "user_interested_in_event" 
	on "event"."event_id" = "user_interested_in_event"."event_id"
	Where "user_interested_in_event"."user_id" = 2;
	
--Select group infomation of groups I belong
Select "group"."group_id" , "name", "information", TYPE
	from "user_in_group" INNER JOIN "group"
	on "user_in_group"."group_id" = "group"."group_id"
	where "user_id" = 2;
	