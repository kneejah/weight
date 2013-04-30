CREATE TABLE 'users' (
	'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	'username' TEXT NOT NULL,
	'password_hash' TEXT NOT NULL,
	'email_address' INTEGER NOT NULL,
	'create_time' INTEGER NOT NULL,
	'update_time' INTEGER NOT NULL
);

CREATE TABLE 'weight' (
	'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	'userid' INTEGER NOT NULL,
	'weight' REAL NOT NULL,
	'create_time' INTEGER NOT NULL,
	'comment' TEXT
);