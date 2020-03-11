<?php

namespace core\blacknine;

class Iacsv
{

	private $pathFile;

	private $formatFileIsUTF8;

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

		$this->formatFileIsUTF8 = mb_check_encoding(file_get_contents($this->pathFile), 'UTF-8');

	}

	public function setFormatFileIsUTF8($formatFileIsUTF8)
	{

		$this->formatFileIsUTF8 = $formatFileIsUTF8;
		return $this;

	}

	public function getFormatFileIsUTF8()
	{
		return $this->formatFileIsUTF8;
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


	public function execute(){

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
		// always return array of row content
		return $contents;
	}


	private function __toArray()
	{

		$return = [];

		$row = 1;

		$this->__openFile();

		while (!feof($this->fileContent)) {

			$contents = $this->__getRow();

			if($this->getRowStart() < $row){
				$return[] = $contents;
			}


			$row++;

		}

		$this->__closeFile();

		return $return;
	}



}