<?php

class PHPBDService 
{
	var $username;
	var $password;
	var $server;
	var $port;
	var $databasename;
	var $tablename;
	var $conexao;
	var $query;
	
	public function PHPBDService() 
	{
		
	}
	public function openConexao($usuario,$senha,$servidor,$porta)
	{
		$this->username = $usuario;
		$this->password = $senha;
		$this->server = $servidor;
		$this->port = $porta;
		$this->conexao = mysqli_connect($this->server, $this->username, $this->password);
		if (!$this->conexao) 
		{
			die('Não foi possível realizar a conexão com o servidor: ' . mysqli_error());
		}	
		return true;
	}
	public function listarBancoDados($usuario,$senha,$servidor,$porta)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->query = "show databases";
		$result = mysqli_query($this->conexao, $this->query);
		$rows = array();
		$i = 0;
		
		//print_r($result);
		while($row = mysqli_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->closeConexao();
		return $rows;
	}
	public function listarTabelas($usuario,$senha,$servidor,$porta,$bancoDados)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "show tables";
		$result = mysqli_query($this->conexao, $this->query);
		$rows = array();
		$i = 0;
		while($row = mysqli_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->closeConexao();
		return $rows;
	}
	public function listarCampos($usuario,$senha,$servidor,$porta,$bancoDados,$tabela)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		$this->tablename = $tabela;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "SHOW FULL COLUMNS FROM ".$this->tablename;
		$result = mysqli_query($this->conexao, $this->query);
		$rows = array();
		$i = 0;
		while($row = mysqli_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->closeConexao();
		return $rows;
	}
	public function listarComentarioTabela($usuario,$senha,$servidor,$porta,$bancoDados,$tabela)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		$this->tablename = $tabela;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "SHOW TABLE STATUS LIKE '".$this->tablename."'";
		$result = mysqli_query($this->conexao, $this->query);
		$row = mysqli_fetch_object($result);
		$this->closeConexao();
		return $row;
	}
	public function salvarComentarioTabela($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$comentario)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		$this->tablename = $tabela;
		$return = true;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "ALTER TABLE ".$this->tablename." COMMENT  '".$comentario."'";
		$result = mysqli_query($this->conexao, $this->query);
		$this->closeConexao();
		return $return;
	}
	public function salvarComentario($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$campos)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		$this->tablename = $tabela;
		$return = true;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			return 'Não foi possível conectar com o banco de dados: ' . mysqli_error();
		}
		for($i=0; $i<count($campos); $i++)
		{
			$obj = explode("|", $campos[$i]);
			$autoinc = "";
			if($obj[3] == "auto_increment")
				$autoinc = " AUTO_INCREMENT ";
			$this->query = "ALTER TABLE ".$this->tablename." CHANGE  ".$obj[2]."  
			".$obj[2]." ".strtoupper($obj[1])." NOT NULL $autoinc COMMENT  '".$obj[0]."'";
			$result = mysqli_query($this->conexao, $this->query);
			$return &= $result;
		}
		$this->closeConexao();
		return $return;
	}
	public function closeConexao()
	{
		if(!mysqli_close($this->conexao))
		{
			die ('Não foi possível fechar a conexão com o banco de dados: ' . mysqli_error());
		}
		return true;
	}
}
?>
