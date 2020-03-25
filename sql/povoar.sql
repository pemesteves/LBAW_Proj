
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(1, 'Admin1', 'admin1@gg.pt', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', NULL) --admin
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'Joaquim Rodrigues', 'jokinho@feup.pt', '61be55a8e2f6b4e172338bddf184d6dbee29c98853e0a0485ecee7f27b9af0b4', NULL) --aaaa
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'Paulo Tavares', 'paulot@flup.pt', '37d43d21680eea509d56b0b9aa90e986b068c6c3ebe5f6e31199d94d1d99920f', NULL) --1234pass
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'AEISEP', 'aeisep@isep.pt', '2371eed6bc20f1a21a1b02d963db2966c0f0b12b42153b0caf181416a2c09eae', NULL) --yoyo10
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'Gustavo Torres', 'tgusta@feup.pt', '1727619a97994f63beb35ab12c792972b4bfbebc6fbbaa0621a31afdbc725268', NULL) --tgustamucho
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'Pedro Esteves', 'pmesteves@feup.pt', '80cfa04f6ef471fef3a12581ca0ef857af748aa19463ea0292e4a9c310a4dae9', NULL) --estevesboss
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'Vitor Ventuzelos', 'berserking@feup.pt', '979d2b82d1ed2c6f23406e898b9adb9060090501928654278eefff39a9fe15f4', NULL) --berserking_idiot
insert into public."user" ("user_id", "name", "email", "password", TYPE) values(NULL, 'José Martins', 'martins@flup.pt', '4447a0a857c5856a65be0b6d88e522930d60cc70cd87fc5a97ef5f9b29472e1f', NULL) --martins


insert into public."admin" ("admin_id", "user_id") values (1, 1);


insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (1, 2, 'Just one regular user!');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 3, 'Just another regular user!');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 4, 'Another one!');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 5, 'DB user');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 6, 'GameJam user');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 7, 'Another one!');
insert into public."regular_user" ("regular_user_id", "user_id", "personal_info") values (NULL, 8, 'Flup student');


insert into public."student" ("student_id", "regular_user_id") values (1, 2);
insert into public."student" ("student_id", "regular_user_id") values (NULL, 5);
insert into public."student" ("student_id", "regular_user_id") values (NULL, 6);
insert into public."student" ("student_id", "regular_user_id") values (NULL, 7);
insert into public."student" ("student_id", "regular_user_id") values (NULL, 8);


insert into public."teacher" ("teacher_id", "regular_user_id") values (1, 3);


insert into public."organization" ("organization_id", "regular_user_id", "approval") values (1, 4, TRUE);


insert into public."event" ("event_id", "organization_id", "name", "location", "date", "information") values (1, 1, 'Evento de LBAW', 'Porto', '2020-04-23 17:45:00');


insert into public."group" ("group_id", "name", "information", TYPE) values (1, "Grupo de LBAW", "Grupo para os estudantes de LBAW", 'normal');


insert into public."user_in_group" ("user_id", "group_id") values (2, 0);
insert into public."user_in_group" ("user_id", "group_id") values (5, 0);
insert into public."user_in_group" ("user_id", "group_id") values (6, 0);
insert into public."user_in_group" ("user_id", "group_id") values (7, 0);


insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (1, 2, 'FEUP LIFE', 'Gosto de estudar na FEUP', '2020-02-20 17:45:00', 4, 3, NULL, NULL, NULL);
insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (NULL, 2, 'Aula de LBAW', 'Na próxima aula de LBAW temos que entregar todos os artefactos desta componente?', '2020-02-24 10:38:43', 2, 0, NULL, NULL, NULL);
insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (NULL, 2, 'Trabalho COMP', 'Alguém da turma 4 necessita de um elemento para a realização do trabalho 1 de Compiladores?', '2020-03-05 15:50:11', 5, 1, NULL, NULL, NULL);
insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (NULL, 1, 'Inquéritos FLUP', 'Conseguem todos preencher os inquéritos lançados pela faculdade?', '2020-03-10 08:31:06', 10, 0, NULL, NULL, NULL);
insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (NULL, 3, 'Quarentena', 'Pedimos a todos os alunos da UP, em especial os do ISEP para ficarem em casa durante este período de quarentena, seguindo as indicações da DGS.', '2020-03-13 16:54:51', 40, 1, NULL, NULL, NULL);
insert into public."post" ("post_id", "author_id", "title", "body", "date", "upvotes", "downvotes", TYPE, "event_id", "group_id") values (NULL, 6, 'Grupo para LBAW', 'Bom dia pessoal, criei este grupo para ser mais facil partilharmos os ficheiros para LBAW. Abraço', '2020-03-12 09:21:41', 3, 0, NULL, NULL, 0);



insert into public."comment" ("comment_id", "user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (1, 5, 2, NULL, 'Não, essa entrega tem uma data específica, verifica no Moodle', '2020-02-24 11:03:22', 1, 0);
insert into public."comment" ("comment_id", "user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (NULL, 6, 5, NULL, 'Otima iniciativa', '2020-03-14 16:31:19', 5, 0);
insert into public."comment" ("comment_id", "user_id","post_id", "comment_to_id", "body", "date", "upvotes", "downvotes") values (NULL, 8, 4, NULL, 'Está preenchido, professor', '2020-03-10 10:23:44', 1, 0);


insert into public."chat" ("chat_id") values (1);

insert into public."user_in_chat" ("user_id", "chat_id") values (2,0);
insert into public."user_in_chat" ("user_id", "chat_id") values (5,0);
insert into public."user_in_chat" ("user_id", "chat_id") values (6,0);
insert into public."user_in_chat" ("user_id", "chat_id") values (7,0);

insert into public."message" ("message_id", "sender_id", "chat_id", "body", "date") values (1, 2, 0, 'Boas pessoal, este vai ser o chat de LBAW', '2020-03-20 14:22:55');
insert into public."message" ("message_id", "sender_id", "chat_id", "body", "date") values (NULL, 5, 0, 'Boas!', '2020-03-20 14:30:00');
insert into public."message" ("message_id", "sender_id", "chat_id", "body", "date") values (NULL, 6, 0, 'Então, tudo bem?', '2020-03-20 15:02:21');
insert into public."message" ("message_id", "sender_id", "chat_id", "body", "date") values (NULL, 7, 0, 'Mãos à obra', '2020-03-20 15:03:41');


insert into public."report" ("report_id", "reporter_id", "approval", "reason", "reported_user_id", "reported_event_id", "reported_post_id", "reported_comment_id", "reported_group_id") values (1, 3, NULL, "so para testar a ferramenta", NULL, NULL, 0, NULL, NULL);
insert into public."report" ("report_id", "reporter_id", "approval", "reason", "reported_user_id", "reported_event_id", "reported_post_id", "reported_comment_id", "reported_group_id") values (NULL, 2, NULL, "pouco conteudo", NULL, NULL, NULL, 1, NULL);

--FALTA NOTIFICATION
--FALTA NOTIFIED_USER
--FALTA IMAGE
--FALTA FILE