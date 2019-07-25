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