@ECHO OFF
:: execute backup
cd "C:\Program Files\MySQL\MySQL Server 8.0\bin"
mysqldump -uroot -proot --databases followme > C:\inetpub\wwwroot\followme\core\storage\app\attachments\database_backup\followme_dump.sql