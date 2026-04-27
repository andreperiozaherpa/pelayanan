#!/bin/bash
set -e

# Initialize MariaDB data directory if it's empty
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MariaDB data directory..."
    mysql_install_db --user=mysql --datadir=/var/lib/mysql > /dev/null

    # Start MySQL temporarily to setup root and database
    echo "Setting up MariaDB users and database..."
    tfile=`mktemp`
    if [ ! -f "$tfile" ]; then
        return 1
    fi

    cat << EOF > $tfile
USE mysql;
FLUSH PRIVILEGES;
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_PASSWORD:-pelayanan_dokumen_pass}';
CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE:-pelayanan_dokumen_db}\`;
GRANT ALL PRIVILEGES ON \`${DB_DATABASE:-pelayanan_dokumen_db}\`.* TO '${DB_USERNAME:-pelayanan_dokumen_user}'@'%' IDENTIFIED BY '${DB_PASSWORD:-pelayanan_dokumen_pass}';
FLUSH PRIVILEGES;
EOF

    /usr/bin/mysqld --user=mysql --bootstrap < $tfile
    rm $tfile
    echo "MariaDB initialization complete."
fi

# Run supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
