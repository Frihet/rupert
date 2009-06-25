create table rt_type
(
	id serial not null primary key,
	name varchar(64) not null,
	deleted boolean not null default false,
	color varchar(32) not null
);

insert into rt_type (name, color) values ('Normal', '#ff0000');
insert into rt_type (name, color) values ('Tentative', '#a0a0a0');

alter table rt_resource_usage add column type_id int not null references rt_type (id) default 1;
alter table rt_resource_usage alter column type_id drop default;

