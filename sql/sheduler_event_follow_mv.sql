delimiter |

CREATE EVENT `follow_mv`
ON SCHEDULE EVERY 1  HOUR
ON COMPLETION  PRESERVE ENABLE
COMMENT 'Creates a materialized view with user data'
DO
BEGIN
SET @stmt_sql = "SELECT @rank:=@rank+1 AS id, me as me_id, he as he_id,  first_name, last_name, u.slug, p.avatar, f_date,  max(relation) as relation
FROM
(SELECT @rank:=0) AS foo,
(SELECT me, he, max(f_date) as f_date, 'friends' as relation
FROM
(SELECT user_id me, followee_id he, created_at f_date, 'following' as relation
FROM follow
UNION ALL
SELECT followee_id me, user_id he, created_at f_date,  'follower' as relation
FROM follow
) as al
GROUP BY me, he
HAVING count(relation) > 1
UNION ALL
SELECT user_id me, followee_id he, created_at f_date, 'following' as relation
FROM follow
UNION ALL
SELECT followee_id me, user_id he, created_at f_date, 'follower' as relation
FROM follow ) rel
LEFT JOIN user u
ON rel.he = u.id
LEFT JOIN profile p
ON rel.he = p.user_id
WHERE  u.status = 1
GROUP BY  he_id, me_id, first_name, last_name, u.slug, p.avatar, f_date
ORDER BY relation DESC, f_date DESC";

SET @drop_stmt = "drop table if exists validbook.follow_mv;";

PREPARE stmt
FROM @drop_stmt;

EXECUTE stmt;


SET @sql = concat("create table validbook.follow_mv as ", @stmt_sql);

PREPARE stmt
FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;
END |
delimiter ;