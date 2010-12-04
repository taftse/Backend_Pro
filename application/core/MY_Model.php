<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2009, Adam Price
 * @license			http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// TODO: Use explain before possible queries to see if indexes have been setup
class MY_Model extends CI_Model
{
    /**
     * The table which the model will act upon
     *
     * @var string
     */
    protected $table;

    /**
     * The primary key which for the current table. Defaults
     * to 'id'
     * 
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * Whether to set the created date value on INSERT
     *
     * @var bool
     */
    protected $set_created_date = FALSE;

    /**
     * Whether to set the modified date value on UPDATE
     *
     * @var bool
     */
    protected $set_modified_date = FALSE;

    /**
     * The modified date column in the table
     *
     * @var string
     */
    protected $modified_date_column = 'modified_on';

    /**
     * The created date column in the table
     *
     * @var string
     */
    protected $created_date_column = 'created_on';

    public function __construct()
    {
        parent::__construct();

        log_message('debug','MY_Model class loaded');
    }

    /**
     * Get a row from the current table using the primary key
     *
     * @throws DatabaseException
     * @param int $primary_value Primary key value
     * @return object
     */
    public function get($primary_value)
    {
        return $this->get_by($this->primary_key, $primary_value);
    }

    /**
     * Get a row from the current table using a WHERE query
     *
     * @throws DatabaseException
     * @param array|string $where Either where clause array or column name
     * @param string $value Column value
     * @return object
     */
    protected function get_by($where, $value = '')
    {
        $this->set_where($where, $value);
        $query = $this->db->get($this->table);

        if($query === FALSE)
        {
            throw new DatabaseException("Unable to get row from table");
        }

        return $query->row();
    }

    /**
     * Get all rows in the current table
     *
     * @throws DatabaseException
     * @return object
     */
    public function get_all()
    {
        $query = $this->db->get($this->table);

        if($query === FALSE)
        {
            throw new DatabaseException("Unable to get all rows from table");
        }

        return $query->result();
    }

    /**
     * Get all rows from the table using a WHERE query
     *
     * @throws DatabaseException
     * @param array|string $where Either where clause array or column name
     * @param string $value Column value
     * @return object
     */
    protected function get_all_by($where, $value = '')
    {
        $this->set_where($where, $value);
        $query = $this->db->get($this->table);

        if($query === FALSE)
        {
            throw new DatabaseException("Unable to get row from table");
        }

        return $query->result();
    }

    /**
     * Update a record using the primary key
     *
     * @throws DatabaseException
     * @param int $primary_value Primary key value
     * @param array $data The data rows to update
     * @return bool
     */
    public function update($primary_value, array $data)
    {
        return $this->update_by($data, $this->primary_key, $primary_value);
    }

    /**
     * Update a record from the table based on a where clause.
     *
     * @throws DatabaseException
     * @param array|string $where Either where clause array or column name
     * @param string $value Column value
     * @return bool
     */
    protected function update_by($data, $where, $value = '')
    {
        if($this->set_modified_date)
        {
            log_message('debug','Updating the modified date');
            $data[$this->modified_date_column] = date('Y-m-d H:i:s');
        }
        
        $this->set_where($where, $value);
        $query = $this->db->update($this->table, $data);

        if($query === FALSE)
        {
            throw new DatabaseException('Unable to update row in table');
        }

        return TRUE;
    }

    /**
     * Insert a record into the table
     *
     * @throws DatabaseException
     * @param array $data The data rows to insert
     * @return int ID of new row, 0 if no auto-inc column
     */
    public function insert(array $data)
    {
        if($this->set_created_date)
        {
            log_message('debug','Setting the created date');
            $data[$this->created_date_column] = date('Y-m-d H:i:s');
        }

        $query = $this->db->insert($this->table, $data);

        if($query === FALSE)
        {
            throw new DatabaseException('Unable to insert row in table');
        }

        return $this->db->insert_id();
    }

    /**
     * Delete a record from the table based on the primary key
     *
     * @throws DatabaseException
     * @param int $primary_value Primary key value
     * @return bool
     */
    public function delete($primary_value)
    {
        return $this->delete_by($this->primary_key, $primary_value);
    }

    /**
     * Delete a record from the table based on a where clause.
     *
     * @throws DatabaseException
     * @param array|string $where Either where clause array or column name
     * @param string $value Column value
     * @return bool
     */
    protected function delete_by($where, $value = '')
    {
        $this->set_where($where, $value);
        $query = $this->db->delete($this->table);

        if($query === FALSE)
        {
            throw new DatabaseException('Unable to delete row from table');
        }

        return TRUE;
    }

    /**
     * Sets a where clause. If a single parameter is given it must be an array
     * containing the where clause. If both parameters are given, the first should
     * be the column name and the second the column value.
     *
     * @param array|string $where Either where clause array or column name
     * @param string $value Column value
     * @return void
     */
    private function set_where($where, $value = '')
    {
        if(is_array($where))
        {
            $this->db->where($where);
        }
        else
        {
            $this->db->where($where, $value);
        }
    }
}

/* End of MY_Model.php */
/* Location: ./application/core/MY_Model.php */