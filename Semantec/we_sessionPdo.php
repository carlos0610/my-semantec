<?php
class we_sessionPdo
{
    /**
       * Database connection
       * 
       * @var object
       */
    protected $db;

    /**
       * Database table for session data
       *
       * @var string
       */
    protected $dbTable;

    /**
       * Use database transaction
       * 
       * @var    boolean
       */
    protected $transaction = false;

    /**
       * Regenerate session ID
       * 
       * @var    boolean
       */
    protected $regenerate_id = false;

    /**
       * Constructor
       *
       * @param  object  $pdo PDO database object
       * @param  boolean $transaction Use database transactions [optional]
       * @param  boolean $sessionName Session name [optional]
       * @param  string $dbTable Database table [optional]
       * @return void
       */     
    public function __construct(PDO $pdo, $transaction = true, $sessionName = 'PDOSESSID', $dbTable = 'session_data')
    {
        // Set database table name
        $this->dbTable = (string) $dbTable;
        
        // Set database connection
        $this->db = $pdo;
        if( $transaction ) {
            $this->transaction = true;
            $this->db->beginTransaction();
        }

        // Start session
        session_set_save_handler(array(__CLASS__, '_open'),
                                 array(__CLASS__, '_close'),
                                 array(__CLASS__, '_read'),
                                 array(__CLASS__, '_write'),
                                 array(__CLASS__, '_destroy'),
                                 array(__CLASS__, '_gc'));
        session_name($sessionName);
        session_start();
    }

    /**
       * Regenerate session ID after the session call.
       *
       * @return void
       */
    public function regenerate_id()
    {
        $this->regenerate_id = true;
    }

    /**
       * Destroy session, session data and session cookie.
       * 
       * @return void
       */
    public function destroy()
    {
        $_SESSION = array();

        if( isset($_COOKIE[session_name()]) ) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }

    /**
       * Get raw session data from database.
       *
       * @param  string $id Session ID
       * @return array or false
       */
    protected function _fetchSession($id)
    {
        $stmt = $this->db->prepare('SELECT id, data FROM ' . $this->_getTable() . ' WHERE id = :id AND unixtime > :unixtime');
        $stmt->execute( array(':id' => $id, ':unixtime' => ( time() - (int) ini_get('session.gc_maxlifetime') ) ) );
        $sessions = $stmt->fetchAll();

        if( $this->transaction ) {
            $this->db->commit();
        }

        return empty($sessions) ? false : $sessions[0] ;
    }

    /**
       *  Get database table name for session data
       * 
       * @return string
       */
    protected function _getTable()
    {
        $table = $this->db->quote($this->dbTable);

        // If database driver does not support quoting return raw string
        if( !$table ) {
            $table = $this->dbTable;
        }
        
        return $table;
    }
    
    /**
       * Open session. Not relevant to this class.
       *
       * @param  $savePath
       * @param  $sessionName
       * @return true
       */
    protected function _open($savePath, $sessionName)
    {
        return true;
    }

    /**
       * Close session. Not relevant to this class.
       *
       * @return true
       */
    protected function _close()
    {
        return true;
    }

    /**
       * Read session data.
       *
       * @param  string $id Session ID
       * @return string or false
       */
    protected function _read($id)
    {
        $session = $this->_fetchSession($id);

        return ( $session === false ) ? false : $session['data'] ;
    }

    /**
       * Write session data.
       *
       * @param  string $id Session ID
       * @param  string $sessionData Session data
       * @return void
       */
    protected function _write($id, $sessionData)
    {
        $session = $this->_fetchSession($id);
        if( $session === false ) {
            $stmt = $this->db->prepare('INSERT INTO ' . $this->_getTable() . ' (id, data, unixtime) VALUES (:id, :data, :time)');
        } else {
            $stmt = $this->db->prepare('UPDATE ' . $this->_getTable() . ' SET data = :data, unixtime = :time WHERE id = :id');
        }
        $stmt->execute( array(
            ':id' => $id,
            ':data' => $sessionData,
            ':time' => time()
        ));

        if( $this->transaction ) {
            $this->db->commit();
        }
    }

    /**
       * Destroy session
       *
       * @param  string $id Session ID
       * @return void
       */
    protected function _destroy($id)
    {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->_getTable() . ' WHERE id = :id');
        $stmt->execute(array(':id' => $id));
    }

    /**
       * Garbage collection.
       *
       * @param  integer $maxlifetime Maximum session life time
       * @return void
       */
    protected function _gc($maxlifetime)
    {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->_getTable() . ' WHERE unixtime < :time');
        $stmt->execute(array( ':time' => (time() - (int) $maxlifetime) ));
    }

    /**
       * Destructor
       *
       * @return void
       */
    public function __destruct()
    {
        // Create new session ID
        if( $this->regenerate_id ) {
            session_regenerate_id(true);
        }

        // Close session
        session_write_close();
        if( $this->transaction ) {
            $this->db->commit();
        }
    }
}
?>
