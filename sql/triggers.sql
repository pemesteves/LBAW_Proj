DROP TRIGGER IF EXISTS update_group_posts ON "group" CASCADE;
DROP TRIGGER IF EXISTS update_event_posts ON "event" CASCADE;
DROP TRIGGER IF EXISTS except_user_chat ON "message" CASCADE;
DROP TRIGGER IF EXISTS add_friend ON "friend" CASCADE;

DROP FUNCTION IF EXISTS update_group_posts() CASCADE;
DROP FUNCTION IF EXISTS update_event_posts() CASCADE;
DROP FUNCTION IF EXISTS throw_exception_user_chat() CASCADE;
DROP FUNCTION IF EXISTS add_friend() CASCADE;

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

Create FUNCTION add_friend() returns trigger as $$
BEGIN
	IF new."friendship_status" = 'accepted' THEN
		Insert into public."friend" ("friend_id1","friend_id2","friendship_status") values (old.public."friend_id2",old.public."friend_id1",'accepted');
	END IF;
END; $$
LANGUAGE plpgsql;


Create trigger add_friend
	after update 
	on public."friend"
	EXECUTE PROCEDURE
		add_friend();

--___________________________


