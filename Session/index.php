<?php

/*
    CREATE TABLE sessions(
   id VARCHAR(255) UNIQUE NOT NULL,
   payload TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
 */

ini_set('session.auto_start.gc_maxlifetime', 10);
ini_set('extension','php_pdo_mysql.dll');

/**
 * Session Handler Interface
 */
class DatabaseSessionHandler implements SessionHandlerInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function open($path, $name)
    {
        return true;
    }

    public function read($id)
    {
        $sth = $this->pdo->prepare('SELECT * FROM sessions WHERE `id` = :id');

        if ($sth->execute([':id' => $id])) {
            if ($sth->rowCount()>0) {
                $payload = $sth->fetchObject()->payload();
            } else {
                $sth = $this->pdo->prepare('INSERT INTO sessions(`id`) VALUES(:id)');
                $sth->execute([':id' => $id]);
            }
        }

        return $payload ?? '';
    }

    public function close()
    {
        return true;
    }

    public function destroy($id)
    {
        $this->pdo
            ->prepare('DELETE FROM sessions WHERE `id` = :id')
            ->execute([':id' => $id])
        ;
    }

    public function gc($max_lifetime)
    {
        $sth = $this->pdo->prepare('SELECT * FROM sessions');

        if ($sth->execute()) {
            while ($row = $sth->fetchObject()) {
                $timestamp = strtotime($row->created_at);
                if (time() - $timestamp > $max_lifetime) {
                    $this->destroy($row->id);
                }
            }
            return true;
        }

        return false;
    }

    public function write($id, $data)
    {
        return $this->
        pdo->prepare('UPDATE sessions SET `payload` = :payload WHERE `id` = :id')
            ->execute([':payload' => $data, ':id' =>$id]);
    }
}

session_set_save_handler(new DatabaseSessionHandler(new PDO('mysql:dbname=test;host=127.0.0.1;', 'root', 'root')));
session_start();

//$_SESSION['message'] = 'Hello world';
//$_SESSION['foo'] = new stdClass();

session_gc();