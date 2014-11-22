<?php

class AerospikeSessionHandler implements SessionHandlerInterface
{
	private $client;
	private $options;

	public function __construct(Aerospike $client = NULL, $options = array()) {

		// initialize Aerospike instance if not provided
		if ($client == NULL) {
			$config = ["hosts" => [[
					"addr" => array_key_exists('addr', $options)? $options['addr'] : "127.0.0.1",
					"port" => array_key_exists('port', $options)? $options['port'] : 3010
			]]];
			$client = new Aerospike($config);
		}
		$this->client = $client;

		// set default options
		$this->options = array_merge(array(
				'ns' => 'test',
				'set' => 'session',
				'bin' => 'data'
		), $options);
		
		if (!array_key_exists('ttl', $options))
			$options['ttl'] = ini_get('session.gc_maxlifetime');
	}

	public function open($savePath, $sessionName) {
		return $this->client->isConnected();
	}

	public function close() {
		$this->client->close();
		return true;
	}

	public function read($id) {
		$status = $this->client->get($this->as_key($id), $record);
		if (($status == Aerospike::OK) && (isset($record['bins']))) {
			if (count($record['bins'] == 1))
				return array_values($record['bins'])[0];
			else
				return $record['bins'][$this->options['bin']];
		}
		else
			return '';
	}

	public function write($id, $data) {
		$status = $this->client->put($this->as_key($id), [$this->options['bin'] => $data], $this->options['ttl'], []);
		return ($status == Aerospike::OK);
	}

	public function destroy($id) {
		$status = $this->client->remove($this->as_key($id));
		return ($status == Aerospike::OK);
	}

	public function gc($maxlifetime) {
		return true;
	}

	private function as_key($id) {
		return $this->client->initKey($this->options['ns'], $this->options['set'], $id);
	}
}

