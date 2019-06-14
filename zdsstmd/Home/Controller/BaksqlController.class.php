<?php

namespace Home\Controller;

class BaksqlController extends CommonController
{
	public $config = "";
	public $model = "";
	public $content;
	public $dbName = "";
	public $dir_sep = "/";

	public function _initialize()
	{
		parent::_initialize();
		header("Content-type: text/html;charset=utf-8");
		set_time_limit(0);
		ini_set("memory_limit", "500M");
		$this->config = array("path" => c("DB_BACKUP"), "isCompress" => 0, "isDownload" => 0);
		$this->dbName = c("DB_NAME");
		$this->model = new \Think\Model();
	}

	public function index()
	{
		$path = $this->config["path"];
		$fileArr = $this->MyScandir($path);

		foreach ($fileArr as $key => $value ) {
			if (1 < $key) {
				$fileTime = date("Y-m-d H:i:s", filemtime($path . "/" . $value));
				$fileSize = filesize($path . "/" . $value) / 1024;
				$fileSize = ($fileSize < 1024 ? number_format($fileSize, 2) . " KB" : number_format($fileSize / 1024, 2) . " MB");
				$list[] = array("name" => $value, "time" => $fileTime, "size" => $fileSize);
			}
		}

		$this->assign("list", $list);
		$this->display();
	}

	public function tablist()
	{
		$list = $this->model->query("SHOW TABLE STATUS FROM $this->dbName");
		$this->assign("list", $list);
		$this->display();
	}

	public function backall()
	{
		$tables = $this->getTables();

		if ($this->backup($tables)) {
			$this->success("数据库备份成功！", u("Index/main"));
		}
		else {
			$this->error("数据库备份失败！");
		}
	}

	public function backtables()
	{
		$tab = $_REQUEST["tab"];

		if (is_array($tab)) {
			$tables = $tab;
		}
		else {
			$tables[] = $tab;
		}

		if ($this->backup($tables)) {
			if (is_array($tab)) {
				$this->success("数据库备份成功！", u("index"));
			}
			else {
				$this->success("数据库备份成功！", u("index"));
			}
		}
		else {
			$this->error("数据库备份失败！");
		}
	}

	public function recover()
	{
		if ($this->recover_file($_GET["file"])) {
			$this->success("数据还原成功！", "/public/ok");
		}
		else {
			$this->error("数据还原失败！");
		}
	}

	public function deletebak()
	{
		if (unlink($this->config["path"] . $this->dir_sep . $_GET["file"])) {
			$this->success("删除备份成功！", u("index"));
		}
		else {
			$this->error("删除备份失败！");
		}
	}

	public function downloadBak()
	{
		$file_name = $_GET["file"];
		$file_dir = $this->config["path"];

		if (!file_exists($file_dir . "/" . $file_name)) {
			return false;
			exit();
		}
		else {
			$file = fopen($file_dir . "/" . $file_name, "r");
			header("Content-Encoding: none");
			header("Content-type: application/octet-stream");
			header("Accept-Ranges: bytes");
			header("Accept-Length: " . filesize($file_dir . "/" . $file_name));
			header("Content-Transfer-Encoding: binary");
			header("Content-Disposition: attachment; filename=" . $file_name);
			header("Pragma: no-cache");
			header("Expires: 0");
			echo fread($file, filesize($file_dir . "/" . $file_name));
			fclose($file);
			exit();
		}
	}

	private function MyScandir($FilePath = "./", $Order = 0)
	{
		$FilePath = opendir($FilePath);

		while ($filename = readdir($FilePath)) {
			$fileArr[] = $filename;
		}

		$Order == 0 ? sort($fileArr) : rsort($fileArr);
		return $fileArr;
	}

	private function getFile($fileName)
	{
		$this->content = "";
		$fileName = $this->trimPath($this->config["path"] . $this->dir_sep . $fileName);

		if (is_file($fileName)) {
			$ext = strrchr($fileName, ".");

			if ($ext == ".sql") {
				$this->content = file_get_contents($fileName);
			}
			else if ($ext == ".gz") {
				$this->content = implode("", gzfile($fileName));
			}
			else {
				$this->error("无法识别的文件格式!");
			}
		}
		else {
			$this->error("文件不存在!");
		}
	}

	private function setFile()
	{
		$recognize = "";
		$recognize = $this->dbName;
		$fileName = $this->trimPath($this->config["path"] . $this->dir_sep . $recognize . "_" . date("YmdHis") . "_" . mt_rand(100000000, 999999999) . ".sql");
		$path = $this->setPath($fileName);

		if ($path !== true) {
			$this->error("无法创建备份目录目录 '$path'");
		}

		if ($this->config["isCompress"] == 0) {
			if (!file_put_contents($fileName, $this->content, LOCK_EX)) {
				$this->error("写入文件失败,请检查磁盘空间或者权限!");
			}
		}
		else if (function_exists("gzwrite")) {
			$fileName .= ".gz";

			if ($gz = gzopen($fileName, "wb")) {
				gzwrite($gz, $this->content);
				gzclose($gz);
			}
			else {
				$this->error("写入文件失败,请检查磁盘空间或者权限!");
			}
		}
		else {
			$this->error("没有开启gzip扩展!");
		}

		if ($this->config["isDownload"]) {
			$this->downloadFile($fileName);
		}
	}

	private function trimPath($path)
	{
		return str_replace(array("/", "\\", "//", "\\\\"), $this->dir_sep, $path);
	}

	private function setPath($fileName)
	{
		$dirs = explode($this->dir_sep, dirname($fileName));
		$tmp = "";

		foreach ($dirs as $dir ) {
			$tmp .= $dir . $this->dir_sep;
			if (!file_exists($tmp) && !@mkdir($tmp, 511)) {
				return $tmp;
			}
		}

		return true;
	}

	private function downloadFile($fileName)
	{
		ob_end_clean();
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Length: " . filesize($fileName));
		header("Content-Disposition: attachment; filename=" . basename($fileName));
		readfile($fileName);
	}

	private function backquote($str)
	{
		return "`$str`";
	}

	private function getTables($dbName = "")
	{
		if (!empty($dbName)) {
			$sql = "SHOW TABLES FROM " . $dbName;
		}
		else {
			$sql = "SHOW TABLES ";
		}

		$result = $this->model->query($sql);
		$info = array();

		foreach ($result as $key => $val ) {
			$info[$key] = current($val);
		}

		return $info;
	}

	private function chunkArrayByByte($array, $byte = 5120)
	{
		$i = 0;
		$sum = 0;
		$return = array();

		foreach ($array as $v ) {
			$sum += strlen($v);

			if ($sum < $byte) {
				$return[$i][] = $v;
			}
			else if ($sum == $byte) {
				$return[++$i][] = $v;
				$sum = 0;
			}
			else {
				$return[++$i][] = $v;
				$i++;
				$sum = 0;
			}
		}

		return $return;
	}

	private function backup($tables)
	{
		if (empty($tables)) {
			$this->error("没有需要备份的数据表!");
		}

		$this->content = "/* This file is created by MySQLReback " . date("Y-m-d H:i:s") . " */";

		foreach ($tables as $i => $table ) {
			$table = $this->backquote($table);
			$tableRs = $this->model->query("SHOW CREATE TABLE $table");

			if (!empty($tableRs[0]["create view"])) {
				$this->content .= "\r\n /* 创建视图结构 $table  */";
				$this->content .= "\r\n DROP VIEW IF EXISTS $table;/* MySQLReback Separation */ " . $tableRs[0]["create view"] . ";/* MySQLReback Separation */";
			}

			if (!empty($tableRs[0]["create table"])) {
				$this->content .= "\r\n /* 创建表结构 $table  */";
				$this->content .= "\r\n DROP TABLE IF EXISTS $table;/* MySQLReback Separation */ " . $tableRs[0]["create table"] . ";/* MySQLReback Separation */";
				$tableDateRow = $this->model->query("SELECT * FROM $table");
				$valuesArr = array();
				$values = "";

				if (false != $tableDateRow) {
					foreach ($tableDateRow as &$y ) {
						foreach ($y as &$v ) {
							if ($v == "") {
								$v = "null";
							}
							else {
								$v = "'" . mysql_escape_string($v) . "'";
							}
						}

						$valuesArr[] = "(" . implode(",", $y) . ")";
					}
				}

				$temp = $this->chunkArrayByByte($valuesArr);

				if (is_array($temp)) {
					foreach ($temp as $v ) {
						$values = implode(",", $v) . ";/* MySQLReback Separation */";

						if ($values != ";/* MySQLReback Separation */") {
							$this->content .= "\r\n /* 插入数据 $table */";
							$this->content .= "\r\n INSERT INTO $table VALUES $values";
						}
					}
				}
			}
		}

		if (!empty($this->content)) {
			$this->setFile();
		}

		return true;
	}

	private function recover_file($fileName)
	{
		$this->getFile($fileName);

		if (!empty($this->content)) {
			$content = explode(";/* MySQLReback Separation */", $this->content);

			foreach ($content as $i => $sql ) {
				$sql = trim($sql);

				if (!empty($sql)) {
					$mes = $this->model->execute($sql);

					if (false === $mes) {
						$table_change = array("null" => "''");
						$sql = strtr($sql, $table_change);
						$mes = $this->model->execute($sql);
					}

					if (false === $mes) {
						$log_text = "以下代码还原遇到问题:";
						$log_text .= "\r\n $sql";
						set_log($log_text);
					}
				}
			}
		}
		else {
			$this->error("无法读取备份文件!");
		}

		return true;
	}
}


?>
