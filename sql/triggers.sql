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