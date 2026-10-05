## comandos
**1:** mariadb -u root -p < sql/001_schema.sql
**2:** mariadb -u root -p < sql/002_seed.sql
**3:** 
- `CREATE USER 'tuuser'@'localhost' IDENTIFIED BY 'TU_PASSWORD_SEGURA';`
- `GRANT SELECT, INSERT, UPDATE ON scanfood_mio.* TO 'tunuser'@'localhost';`
- `FLUSH PRIVILEGES;`
