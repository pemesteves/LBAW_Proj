
-- my info
Select "user_id", "name" from "user" where "email" = 'jokinho@feup.pt';


-- if is admin
Select "admin_id" from "admin" where "user_id" = 24;

-- if is regular_user
Select "regular_user_id", "personal_info" from "regular_user" where "user_id" = 4;

--if is student
Select "student_id" from "student" where "regular_user_id" = 5;

-- if is teacher
Select "teacher_id" from "teacher" where "regular_user_id" = 3;

-- if is organization
Select "organization_id" from "organization" where "regular_user_id" = 4;


-- if user is group
Select "user_id" from "user_in_group" where "group_id" = 1;

--if user in group
Select "group_id" from "user_in_group" where "user_id" = 2;


--events of organization
Select "name", "location", "date", "information" from "event" where "organization_id" = 1;


--My posts
Select "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id" 
	from "post" where "author_id" = 2;



--Notification
Select "origin_user_id", "description", "link", "date" from "notification"
	INNER JOIN "notified_user" on "notification"."notification_id" ="notified_user"."notification_id" 
	where "user_notified" = 5;


--Message
Select "sender_id", "body", "date",
	from "message" where "chat_id" = 2
	order by "date" DESC;


--Comments
 Select "comment_id", "user_id", "comment_to_id", "body", "date", "upvotes", "downvotes"
	from "comment" where "post_id" = 5
	order by "date" DESC;


--File NAO PINTOU

Select "file_path"
	from "file" where "post_id" = 7;