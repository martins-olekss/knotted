# knotted
Silly little link app


## Create Links table
```sqlite
create table links
(
  id          integer primary key autoincrement,
  title       varchar(500),
  url         varchar(500),
  description text
);
```

## Create User table
```sqlite
create table user
(
  id       integer primary key autoincrement,
  name     varchar(500),
  username varchar(250),
  password text
);
```