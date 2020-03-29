DROP TRIGGER IF EXISTS update_group_posts ON "group" CASCADE;
DROP TRIGGER IF EXISTS update_event_posts ON "event" CASCADE;
DROP TRIGGER IF EXISTS except_user_chat ON "message" CASCADE;
DROP TRIGGER IF EXISTS friend_status ON "friend" CASCADE;
DROP TRIGGER IF EXISTS delete_refused_report ON "report" CASCADE;
DROP TRIGGER IF EXISTS event_date ON "event" CASCADE;
DROP TRIGGER IF EXISTS post_date ON "post" CASCADE;


DROP FUNCTION IF EXISTS update_group_posts() CASCADE;
DROP FUNCTION IF EXISTS update_event_posts() CASCADE;
DROP FUNCTION IF EXISTS throw_exception_user_chat() CASCADE;
DROP FUNCTION IF EXISTS friend_status() CASCADE;
DROP FUNCTION IF EXISTS delete_refused_report() CASCADE;
DROP FUNCTION IF EXISTS event_date() CASCADE;
DROP FUNCTION IF EXISTS post_date() CASCADE;


--Trigger atualizar status dos posts de um grupo

CREATE FUNCTION update_group_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."group".TYPE WHERE public."post"."group_id" = public."group"."group_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_group_posts
    AFTER UPDATE OR INSERT ON public."group"
    FOR EACH ROW
    EXECUTE PROCEDURE update_group_posts();

-- _____________________


--Trigger atualizar status dos posts de um evento

CREATE FUNCTION update_event_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."event".TYPE WHERE public."post"."event_id" = public."event"."event_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_event_posts
    AFTER UPDATE OR INSERT ON public."event"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();

-- _____________________


--Trigger atualizar status dos posts de um user

CREATE FUNCTION update_user_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE public."post" SET public."post".TYPE = public."user".TYPE WHERE public."post"."author_id" = public."event"."event_id";
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER update_user_posts
    AFTER UPDATE OR INSERT ON public."regula"
    FOR EACH ROW
    EXECUTE PROCEDURE update_user_posts();

-- _____________________


--Trigger user errado no chat

CREATE FUNCTION throw_exception_user_chat() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS (SELECT * FROM public."user_in_chat" WHERE public."user_in_chat"."id_user" = NEW.public."message"."sender_id")
    THEN RAISE EXCEPTION 'An user must be in the chat to send a message.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER except_user_chat
    BEFORE INSERT ON public."message"
    FOR EACH ROW
    EXECUTE PROCEDURE throw_exception_user_chat();

-- _____________________


--Trigger adicionar amigo

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
--___________________________


--Trigger apagar refused report

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

--____________________________

--Trigger data evento

CREATE FUNCTION event_date() RETURNS trigger AS
$BODY$
BEGIN
    IF new."date" > now() THEN
    RAISE EXCEPTION 'Invalid event date.';
    END IF;
    RETURN NEW;

END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER event_date
    AFTER INSERT ON public."event"
    EXECUTE PROCEDURE event_date();

--______________________

--Trigger data post

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
    EXECUTE PROCEDURE post_date();

--______________________


--Falta Own content, Unique Organization, Ownership