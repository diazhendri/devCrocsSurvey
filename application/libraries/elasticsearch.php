<?php
/**
 * Elasticsearch Library
 *
 * @package OpenLibs
 * 
 */
class ElasticSearch
{
    public $index;

    /**
     * constructor setting the config variables for server ip and index.
     */

    public function __construct()
    {
        $ci = &get_instance();
        $ci -> config -> load("elasticsearch");
        $this -> server = $ci -> config -> item('es_server');
        $this -> index = $ci -> config -> item('index');
    }
    /**
     * Handling the call for every function with curl
     * 
     * @param type $path
     * @param type $method
     * @param type $data
     * 
     * @return type
     * @throws Exception
     */

    private function call($path, $method = 'GET', $data = null)
    {
        if (!$this -> index) {
            throw new Exception('$this->index needs a value');
        }

        $url = $this -> server . '/' . $this -> index . '/' . $path;
        $headers = array('Accept: application/json', 'Content-Type: application/json', );
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        switch($method) {
            case 'GET' :
				if($data != NULL) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
                break;
            case 'POST' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT' :
				// echo "<br />".json_encode($data)."<br />";die;
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if($data != NULL) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
                break;
        }
		// echo $url.' '.$data.' '.$ch;
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return json_decode($response, true);
    }


    /**
     * create a index with mapping or not
     * 
     * @param json $map
     */

    public function create($map = false)
    {
        if (!$map) {
            $this -> call(null, 'PUT');
        } else {
            $this -> call(null, 'PUT', $map);
        }
    }

    /**
     * get status
     * 
     * @return array
     */

    public function status()
    {
        return $this -> call('_status');
    }

    /**
     * count how many indexes it exists
     * 
     * @param string $type
     * 
     * @return array
     */

    public function count($type)
    {
        return $this -> call($type . '/_count?' . http_build_query(array(null => '{matchAll:{}}')));
    }
	
	public function count_by_filter($type, $data)
    {
        return $this -> call($type . '/_count', 'POST', $data);
    }

    /**
     * set the mapping for the index
     * 
     * @param string $type
     * @param json   $data
     * 
     * @return array
     */

    public function map($type, $data)
    {
        return $this -> call($type . '/_mapping', 'PUT', $data);
    }

    /**
     * set the mapping for the index
     * 
     * @param type $type
     * @param type $id
     * @param type $data
     * 
     * @return type
     */

    public function add($type, $id, $data)
    {
        return $this -> call($type . '/' . $id, 'PUT', $data);
    }

    /**
     * delete a index
     * 
     * @param type $type 
     * @param type $id 
     * 
     * @return type 
     */

    public function delete($type, $id)
    {
        return $this -> call($type . '/' . $id, 'DELETE');
    }
	
    /**
     * delete a index by query
     * 
     * @param type $type 
     * @param type $id 
     * 
     * @return type 
     */

    public function delete_advanced($type,$data)
    {
		if(!empty($data)) {
			return $this -> call($type.'/_query', 'DELETE', $data);
		}
    }
	
    /**
     * make a simple search query
     * 
     * @param type $type
     * @param type $q
     * 
     * @return type
     */

    public function query($type, $q)
    {
        return $this -> call($type . '/_search?' . http_build_query(array('q' => $q)));
    }

    /**
     * make a advanced search query with json data to send
     * 
     * @param type $type
     * @param type $query
     * 
     * @return type
     */

///**additional function
    public function advancedquery($type, $query)
    {
        return $this -> call($type . '/_search', 'POST', $query);
    }
	
	public function advancedqueryall($q, $type, $query)
    {
        return $this -> call($type . '/_search?' . http_build_query(array('q' => $q)), 'POST', $query);
    }
	
	public function countadvancedquery($type, $query)
    {
        return $this -> call($type . '/_count', 'POST', $query);
    }
	
	public function countadvancedqueryall($q, $type, $query)
    {
        return $this -> call($type . '/_count?' . http_build_query(array('q' => $q)), 'POST', $query);
    }

	

    /**
     * make a search query with result sized set
     * 
     * @param string  $type  what kind of type of index you want to search
     * @param string  $query the query as a string
     * @param integer $size  The size of the results
     * 
     * @return array
     */

    public function query_wresultSize($type, $query, $size = 999)
    {
        return $this -> call($type . '/_search?' . http_build_query(array('q' => $q, 'size' => $size)));
    }

    /**
     * get one index via the id
     * 
     * @param string  $type The index type
     * @param integer $id   the indentifier for a index
     * 
     * @return type
     */

    public function get($type, $id)
    {
        return $this -> call($type . '/' . $id .'?pretty:1', 'GET');
    }
	
	/**
     * get one index via the parameter data
     * 
     * @param string  $type The index type
     * @param integer $data the JSON data
     * 
     * @return type
     */
	 
	public function get_advanced($type,$data,$q="")
    {
        return $this -> call($type.'/_search'.((!empty($q))? "?".http_build_query(array('q' => $q)):""), 'GET', $data);
    }
	public function get_countadvanced($type,$data,$q="")
    {
        return $this -> call($type.'/_count'.((!empty($q))? http_build_query(array('q' => $q)):""), 'GET', $data);
    }
	
    /**
     * Query the whole server
     * 
     * @param type $query
     * 
     * @return type
     */

    public function query_all($query)
    {
        return $this -> call('_search?search_type=scan&' . http_build_query(array('q' => $query)));
    }

    /**
     * get similar indexes for one index specified by id - send data to add filters or more
     * 
     * @param string  $type
     * @param integer $id
     * @param string  $fields
     * @param string  $data 
     * 
     * @return array 
     */

    public function morelikethis($type, $id, $fields = false, $data = false)
    {
        if ($data != false && !$fields) {
            return $this -> call($type . '/' . $id . '/_mlt', 'GET', $data);
        } else if ($data != false && $fields != false) {
            return $this -> call($type . '/' . $id . '/_mlt?' . $fields, 'POST', $data);
        } else if (!$fields) {
            return $this -> call($type . '/' . $id . '/_mlt');
        } else {
            return $this -> call($type . '/' . $id . '/_mlt?' . $fields);
        }
    }

    /**
     * make a search query with result sized set
     * 
     * @param type $query
     * @param type $size
     * 
     * @return type
     */
    public function query_all_wresultSize($query, $size = 999)
    {
        return $this -> call('_search?' . http_build_query(array('q' => $query, 'size' => $size)));
    }
	
	public function update($type,$id,$data) 
	{
		//echo $id.' '.$data;
		return $this -> call($type.'/'.$id.'/_update', 'POST', $data);
	}
}
