<?php

namespace yswery\DNS;

use \Exception;
use \InvalidArgumentException;

class JsonStorageProvider extends AbstractStorageProvider
{
    /**
     * @var array
     */
    private $dns_records;

    /**
     * @var int
     */
    private $DS_TTL;

    /**
     * @var boolean
     */
    private $recursion_available;

    /**
     * JsonStorageProvider constructor.
     *
     * @param string $record_file The filepath of the JSON-formatted DNS Zone file.
     * @param int $default_ttl The TTL to be used for all Resource Records omitting a TTL.
     * @throws Exception | InvalidArgumentException
     */
    public function __construct($record_file, $default_ttl = 0)
    {
        if (!file_exists($record_file)) {
            throw new Exception(sprintf('The file "%s" does not exist.', $record_file));
        }

        if (false === $dns_json = file_get_contents($record_file)) {
            throw new Exception(sprintf('Unable to open JSON file: "%s".', $record_file));
        }

        if (null === $dns_records = json_decode($dns_json, true)) {
            throw new Exception(sprintf('Unable to parse JSON file: "%s".', $record_file));
        }

        if (!is_int($default_ttl)) {
            throw new InvalidArgumentException('Default TTL must be an integer.');
        }

        $this->DS_TTL = $default_ttl;
        $this->dns_records = $dns_records;
        $this->recursion_available = false;
    }

    /**
     * @param $question
     * @return array
     */
    public function get_answer($question, $wildcard = false )
    {
        $q_name = $question[0]['qname'];
        $q_type = $question[0]['qtype'];
        $q_class = $question[0]['qclass'];
        $domain = trim($q_name, '.');
        $type = RecordTypeEnum::get_name($q_type);

		if($wildcard) {
			// get the domain part only, and extract any subdomain

			$matches = array();
			if(preg_match("/^(.+)\.trgrd\.site(\.)?$/", $q_name, $matches) == 1){
				$domain = "trgrd.site";
			} elseif (preg_match("/^(.+)\.trgrd\.other(\.)?$/", $q_name, $matches) == 1){
				$domain = "trgrd.other";
			}
		}
        // If there is no resource record or the record does not have the type, return an empty array.
        if (!array_key_exists($domain, $this->dns_records) || !isset($this->dns_records[$domain][$type])) {
            return array();
        }

        $answer = array();
        $data = (array) $this->dns_records[$domain][$type];

        foreach ($data as $rdata) {
            $answer[] = array(
                'name' => $q_name,
                'class' => $q_class,
                'ttl' => $this->DS_TTL,
                'data' => array(
                    'type' => $q_type,
                    'value' => $rdata,
                ),
            );
        }

        return $answer;
    }

    /**
     * Get the currently loaded DNS Records
     *
     * @return array
     */
    public function getDnsRecords()
    {
        return $this->dns_records;
    }

    
    /**
     * Getter method for $recursion_available property
     *
     * @return boolean
     */
    public function allows_recursion() {
        return $this->recursion_available;
    }
  
     /*
     * Check if the resolver knows about a domain
     *
     * @param  string  $domain the domain to check for
     * @return boolean         true if the resolver holds info about $domain
     */
    public function is_authority($domain) {
        $domain = trim($domain, '.');
		if (strpos(strtolower($domain), 'trgrd.other') !== false || strpos(strtolower($domain), 'trgrd.site') !== false) { //we are authoritative for every trgrd.net subdomains
			return true;
		}
        return array_key_exists($domain, $this->dns_records);
    }
}