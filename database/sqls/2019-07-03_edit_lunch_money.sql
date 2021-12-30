ALTER TABLE lunch_factories
DROP COLUMN teacher_money;

ALTER TABLE lunch_setups
ADD teacher_money double(8,2) NULL;
