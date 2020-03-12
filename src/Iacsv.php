<?php

namespace blacknine96\importcsv;

class Iacsv
{

	private $pathFile;

	private $isUTF8;

	private $delimiter;

	private $rowStart;

	private $fileContent;


	public function __construct($pathFile)
	{
		$this->pathFile = $pathFile;
	}


	/**
	 * Handle content file.
	 *
	 */

	public function setFileContent($fileContent)
	{
		$this->fileContent = $fileContent;
		return $this;
	}

	public function getFileContent()
	{
		return $this->fileContent;
	}


	/**
	 * Handle Delimiter
	 *
	 */

	public function detectDelimiter()
	{

		$delimiters = array(',' => 0, ';' => 0, "\t" => 0, '|' => 0,);
		$firstLine = '';
		$this->__openFile();
		if ($this->fileContent) {
			$firstLine = fgets($this->fileContent);
			$this->__closeFile();
		}
		if ($firstLine) {
			foreach ($delimiters as $delimiter => &$count) {
				$count = count(str_getcsv($firstLine, $delimiter));
			}
			$this->delimiter = array_search(max($delimiters), $delimiters);
		}
		else {
			$this->delimiter = key($delimiters);
		}

	}

	public function setDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;
		return $this;

	}

	public function getDelimiter()
	{
		return $this->delimiter;
	}


	/**
	 * Handle path file
	 *
	 */

	public function setPathFile($pathFile)
	{
		$this->pathFile = $pathFile;
		return $this;
	}

	public function getPathFile()
	{
		return $this->pathFile;
	}

	/**
	 * Handle path file
	 *
	 */

	public function setRowStart($rowStart)
	{
		$this->rowStart = $rowStart;
		return $this;
	}

	public function getRowStart()
	{
		return $this->rowStart;
	}


	/**
	 * Handle format file
	 *
	 */
	public function detectFormatFileIsUTF8()
	{

		$this->isUTF8 = mb_check_encoding(file_get_contents($this->pathFile), 'UTF-8');

	}

	public function setIsUTF8($isUTF8)
	{

		$this->isUTF8 = $isUTF8;
		return $this;

	}

	public function getFormatFileIsUTF8()
	{
		return $this->isUTF8;
	}

	/**
	 * Handle file
	 *
	 */
	private function __openFile()
	{
		$this->fileContent = fopen($this->pathFile, 'r');
	}

	private function __closeFile()
	{
		fclose($this->fileContent);
	}

	/**
	 * Get data from file.
	 *
	 */

	public static function start($pathFile)
	{
		return new self($pathFile);
	}


	public function all(){

		if (is_null($this->getDelimiter())) {
			$this->detectDelimiter();
		}

		if (is_null($this->getFormatFileIsUTF8())) {
			$this->detectFormatFileIsUTF8();
		}

		if (is_null($this->getRowStart())) {
			$this->setRowStart(0);
		}

		return $this->__toArray();

	}

	public function openSingleRow(){

		if (is_null($this->getDelimiter())) {
			$this->detectDelimiter();
		}

		if (is_null($this->getFormatFileIsUTF8())) {
			$this->detectFormatFileIsUTF8();
		}

		if (is_null($this->getRowStart())) {
			$this->setRowStart(0);
		}

		$this->__openFile();

		$this->__handleStartRow();

		return $this;

	}

	public function getSingleRow(){
		return $this->__getRow();
	}


	private function __getRow()
	{

		if (!$this->getFormatFileIsUTF8()) {
			$contents = str_getcsv(iconv('Windows-1252', 'UTF-8', fgets($this->fileContent)), $this->delimiter);
		}
		else {
			$contents = str_getcsv(fgets($this->fileContent), $this->delimiter);
		}

		if (count($contents) == 1) {
			$contents = explode(',', $contents[0]);
		}
		return $contents;
	}


	private function __toArray()
	{

		$return = [];

		$this->__openFile();

		$this->__handleStartRow();

		while (!$this->checkIsEof()) {
			$return[] = $this->__getRow();
		}

		return $return;
	}

	private function __handleStartRow(){

		if($this->getRowStart() > 0){
			for($row = 1; $row <= $this->getRowStart(); $row++){
				fgets($this->fileContent);
			}
		}

	}


	public function checkIsEof(){
		if(feof($this->fileContent)){
			$this->__closeFile();
			return true;
		}
		else{
			return false;
		}
	}

	public function checkIsNotEof(){
		if(!feof($this->fileContent)){
			return true;
		}
		else{
			$this->__closeFile();
			return false;
		}
	}



}