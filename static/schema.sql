drop table rt_resource_tag;
drop table rt_tag;
drop table rt_resource_usage;
drop table rt_type;
drop table rt_resource;

create table rt_resource
(
	id serial not null primary key,
	name varchar(64) not null
);

create table rt_type
(
	id serial not null primary key,
	name varchar(64) not null,
	deleted boolean not null default false,
	color varchar(32) not null
);

create table rt_resource_usage
(
	id serial not null primary key,
	resource_id int not null references rt_resource (id),
	start date not null,
	stop date not null,
	description varchar(64) not null default '',
	usage int not null,
	type_id int not null references rt_usage_type (id)
);

create table rt_tag
(
	id serial not null primary key,
	description varchar(64) not null
);

create table rt_resource_tag
(
	id serial not null primary key,
	resource_id int not null references rt_resource (id),
	tag_id int not null references rt_tag (id) 
);

insert into rt_tag (description) values ('ITIL/Prince2 course');
insert into rt_tag (description) values ('Preproject');
insert into rt_tag (description) values ('Web development');
insert into rt_tag (description) values ('Sysadmin');
insert into rt_tag (description) values ('Other');

insert into rt_type (name, color) values ('Normal', '#ff0000');
insert into rt_type (name, color) values ('Tentative', '#ffff00');
