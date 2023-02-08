CREATE TABLE IF NOT EXISTS user_account(
        id SERIAL PRIMARY KEY,
        labels text[] not null default '{}'
);

CREATE TABLE IF NOT EXISTS company(
        id SERIAL PRIMARY KEY,
        labels text[] not null default '{}'
);

CREATE TABLE IF NOT EXISTS site(
        id SERIAL PRIMARY KEY,
        labels text[] not null default '{}'
);

CREATE TABLE IF NOT EXISTS label(
         id SERIAL PRIMARY KEY,
         name varchar(100) UNIQUE NOT NULL
);

INSERT INTO label (name) VALUES ('new_member');
INSERT INTO label (name) VALUES ('label1');
INSERT INTO label (name) VALUES ('label2');
INSERT INTO user_account (labels) VALUES ('{"new_member","label2"}');
INSERT INTO company (labels) VALUES ('{"label1","label2"}');
INSERT INTO site (labels) VALUES ('{"label1"}');
