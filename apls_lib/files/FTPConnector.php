<?php

class FTPConnector
{

    private $ftpConn = null;

    const DEFAULT_PORT = 21;
    const DEFAULT_TIMEOUT = 90;
    const DEFAULT_PASV = true;

    /**
     * FTPConnector constructor.
     * @param $host - адрес сервера
     * @param $user - пользователь
     * @param $pass - пароль
     * @param int $port - порт
     * @param int $timeout - тайм-аут для всех последующих сетевых операций
     * @param bool $pasv - вклчюение пасивного режима
     */
    public function __construct(
        $host,
        $user,
        $pass,
        $port = self::DEFAULT_PORT,
        $timeout = self::DEFAULT_TIMEOUT,
        $pasv = self::DEFAULT_PASV)
    {
        $this->ftpConn = @ftp_ssl_connect($host,$port,$timeout) or die("Не удалось установить соединение с $host");
        if (@ftp_login($this->ftpConn, $user, $pass)) {
            ftp_pasv($this->ftpConn, $pasv);
        }
    }

    /**
     * @param bool $pasv - вклчюение пасивного режима
     */
    public function setPasv($pasv = true) {
        ftp_pasv($this->ftpConn, $pasv);
    }

    public function getConn() {
        return $this->ftpConn;
    }

    public function __destruct()
    {
        ftp_close($this->ftpConn);
    }
}