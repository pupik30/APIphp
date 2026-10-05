create database if not exists db_rent character set utf8mb4 collate utf8mb4_unicode_ci;
use db_rent;

# Прейскурант
create table price_list(
    serial_no int not null auto_increment unique,
    apartment_type varchar(50) primary key,
    price_per_meter decimal(10,2) not null,
    utilities_per_person decimal(10,2) not null
);

# Квартиросъемщик
# apartment_cost - начислено за квартиру: жилая площадь * цена метра + проживающие * коммунальные услуги
# rent_paid      - сумма квартплаты (фактически внесено)
create table tenants(
    serial_no int primary key auto_increment,
    owner_name varchar(100) not null,
    residents_count int not null,
    living_area decimal(6,2) not null,
    apartment_type varchar(50) not null,
    apartment_cost decimal(10,2) not null,
    rent_paid decimal(10,2) not null,
    index idx_tenants_type (apartment_type),
    constraint fk_tenants_type foreign key (apartment_type) references price_list(apartment_type)
);

INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (1, 'Студия', 55, 350);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (2, 'Малосемейная', 50, 350);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (3, '1-комнатная', 60, 400);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (4, '2-комнатная', 65, 400);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (5, '3-комнатная', 70, 450);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (6, '4-комнатная', 75, 450);
INSERT INTO price_list (serial_no, apartment_type, price_per_meter, utilities_per_person) VALUES (7, 'Улучшенной планировки', 90, 500);

INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (1, 'Иванов И.И.', 3, 52, '2-комнатная', 4580, 4580);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (2, 'Петров П.П.', 1, 28, 'Студия', 1890, 690);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (3, 'Сидорова А.В.', 4, 72, '3-комнатная', 6840, 6840);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (4, 'Кузнецов Д.С.', 2, 38, '1-комнатная', 3080, 0);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (5, 'Смирнова Е.А.', 5, 85, '4-комнатная', 8625, 8625);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (6, 'Попов Н.К.', 1, 18, 'Малосемейная', 1250, 0);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (7, 'Волкова О.М.', 2, 45, '1-комнатная', 3500, 3500);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (8, 'Иванов И.И.', 3, 60, 'Улучшенной планировки', 6900, 2550);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (9, 'Петров П.П.', 2, 33, 'Малосемейная', 2350, 2350);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (10, 'Сидорова А.В.', 4, 52, '2-комнатная', 4980, 2980);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (11, 'Кузнецов Д.С.', 3, 72, '3-комнатная', 6390, 6390);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (12, 'Смирнова Е.А.', 1, 22, 'Студия', 1560, 1000);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (13, 'Попов Н.К.', 5, 85, '4-комнатная', 8625, 4625);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (14, 'Волкова О.М.', 4, 60, 'Улучшенной планировки', 7400, 7400);
INSERT INTO tenants (serial_no, owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid) VALUES (15, 'Иванов И.И.', 2, 45, '1-комнатная', 3500, 800);

# Ведомость задолжников: задолженность = начислено - внесено
# select owner_name, apartment_type, apartment_cost, rent_paid, apartment_cost - rent_paid as debt
# from tenants where rent_paid < apartment_cost order by owner_name;
