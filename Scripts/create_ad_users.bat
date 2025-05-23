@echo off
SETLOCAL ENABLEDELAYEDEXPANSION

:: Define base command
SET PHP_CMD=php artisan ad:create-user

:: Create User 1
%PHP_CMD% --cn="John Doe" --sn="Doe" --givenname="John" --title="Developer" --description="Software Developer" --displayname="John Doe" --department="IT" --company="TechCorp" --employeenumber="1001" --mailnickname="jdoe" --mail="jdoe@example.com" --mobile="0771000001" --userprincipalname="jdoe@kv.net" --physicaldeliveryofficename="Main Office" --password="StrongP@ssw0rd1" --enable

:: Create User 2
%PHP_CMD% --cn="Jane Smith" --sn="Smith" --givenname="Jane" --title="QA Engineer" --description="Quality Assurance Engineer" --displayname="Jane Smith" --department="QA" --company="TechCorp" --employeenumber="1002" --mailnickname="jsmith" --mail="jsmith@example.com" --mobile="0771000002" --userprincipalname="jsmith@kv.net" --physicaldeliveryofficename="QA Office" --password="StrongP@ssw0rd2" --enable

:: Create User 3
%PHP_CMD% --cn="Michael Chan" --sn="Chan" --givenname="Michael" --title="Manager" --description="Project Manager" --displayname="Michael Chan" --department="Management" --company="TechCorp" --employeenumber="1003" --mailnickname="mchan" --mail="mchan@example.com" --mobile="0771000003" --userprincipalname="mchan@kv.net" --physicaldeliveryofficename="HQ" --password="StrongP@ssw0rd3" --enable

:: Create User 4
%PHP_CMD% --cn="Emily Rose" --sn="Rose" --givenname="Emily" --title="HR Executive" --description="Human Resources" --displayname="Emily Rose" --department="HR" --company="TechCorp" --employeenumber="1004" --mailnickname="erose" --mail="erose@example.com" --mobile="0771000004" --userprincipalname="erose@kv.net" --physicaldeliveryofficename="HR Dept" --password="StrongP@ssw0rd4" --enable

:: Create User 5
%PHP_CMD% --cn="Kasun Vimarshana" --sn="Vimarshana" --givenname="Kasun" --title="SysAdmin" --description="System Administrator" --displayname="Kasun Vimarshana" --department="IT" --company="TechCorp" --employeenumber="1005" --mailnickname="kvimarshana" --mail="kvimarshana@example.com" --mobile="0771000005" --userprincipalname="kvimarshana@kv.net" --physicaldeliveryofficename="Server Room" --password="StrongP@ssw0rd5" --enable

echo.
echo All 5 AD users created.
pause
