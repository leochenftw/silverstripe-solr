<?php

/**
 * The default solr / lucene formatted query
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class SolrQueryBuilder {
	
	public $title = 'Default Solr';
	
	protected $userQuery = '';
	protected $fields = array('title', 'text');
	protected $and = array();
	protected $params = array();
	/**
	 * an array of field => amount to boost
	 * @var array
	 */
	protected $boost = array();

	public function baseQuery($query) {
		$this->userQuery = $query;
	}
	
	public function queryFields($fields) {
		$this->fields = $fields;
	}
	
	public function andWith($field, $value) {
		$this->and[$field] = $value;
	}
	
	public function setParams($params) {
		$this->params = $params;
	}
	
	public function getParams() {
		return $this->params;
	}

	public function parse($string) {
		
		$sep = '';
		$lucene = '';
		foreach ($this->fields as $field) {
			$lucene .= $sep . $field . ':' . $string;
			if (isset($this->boost[$field])) {
				$lucene .= '^' . $this->boost[$field];
			}
			$sep = ' OR ';
		}

		return $lucene;
	}
	
	public function boost($boost) {
		$this->boost = $boost;
	}
	
	public function toString() {
		$rawQuery = $this->userQuery ? '(' . $this->parse($this->userQuery).')' : '';
		
		// add in all the clauses;
		$sep = '';
		if ($rawQuery) {
			$sep = ' AND ';
		}
		
		foreach ($this->and as $field => $value) {
			$rawQuery .= $sep . $field .':' . $value;
			
			
			$sep = ' AND ';
		}
		
		return $rawQuery;
	}
}