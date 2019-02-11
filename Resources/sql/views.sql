CREATE
 VIEW `bc_manufacturer`
 AS select distinct `bc_vehicle`.`manufacturer_id` AS `manufacturer_id`,`bc_vehicle`.`manufacturer` AS `manufacturer` from `bc_vehicle` order by `bc_vehicle`.`manufacturer`


 CREATE
 VIEW `bc_manufacturer_count`
 AS select `a`.`manufacturer` AS `manufacturer`,(select count(0) from `bc_vehicle` `b` where `b`.`manufacturer_id` = `a`.`manufacturer_id`) AS `vehicles` from `bc_manufacturer` `a` order by (select count(0) from `bc_vehicle` `b` where `b`.`manufacturer_id` = `a`.`manufacturer_id`) desc
