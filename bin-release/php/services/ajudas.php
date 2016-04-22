<?php
require_once 'vo/ajuda.php';

class ajudas
{
	var $username;
	var $password;
	var $server;
	var $port;
	var $databasename;
	var $conexao;
	var $query;
	
	function ajudas()
	{
		
	}
	private function openConexao($usuario,$senha,$servidor,$porta)
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
	public function listar_setores($usuario,$senha,$servidor,$porta,$bancoDados,$tabela)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "SELECT HLPsSetor FROM ".$tabela." GROUP BY HLPsSetor";
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
	public function listar($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$setor)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "SELECT * FROM ".$tabela." WHERE HLPsSetor = '".$setor."' ORDER BY HLPnCapitulo";
		$result = mysqli_query($this->conexao, $this->query);
		$rows = array();
		$i = 0;
		while($row = mysqli_fetch_object($result, "ajuda"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->closeConexao();
		return $rows;
	}
	public function selecionar($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$index)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$rows = array();
		$this->query = "SELECT * FROM ".$tabela." 
		WHERE HLPnId = '".$index."'";
		$result = mysqli_query($this->conexao, $this->query);
		$row = mysqli_fetch_object($result, "ajuda");
		$rows[0] = $row;
		$this->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserir($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$obj)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "INSERT INTO ".$tabela."
		(HLPnId,HLPsSetor,HLPnCapitulo,HLPsTitulo,HLPsDescricao,HLPsTags,HLPbExibir,HLPdAlteracao,HLPdInclusao) 
		VALUES 
		('".$obj->HLPnId."','".$obj->HLPsSetor."','".$obj->HLPnCapitulo."','".$obj->HLPsTitulo."','".$obj->HLPsDescricao."','".$obj->HLPsTags."','".$obj->HLPbExibir."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysqli_query($this->conexao, $this->query);
		$this->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function editar($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$obj)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "UPDATE ".$tabela." SET
		HLPnId = '".$obj->HLPnId."',HLPsSetor = '".$obj->HLPsSetor."',HLPnCapitulo = '".$obj->HLPnCapitulo."',HLPsTitulo = '".$obj->HLPsTitulo."',HLPsDescricao = '".$obj->HLPsDescricao."',HLPsTags = '".$obj->HLPsTags."',HLPbExibir = '".$obj->HLPbExibir."',HLPdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE HLPnId = '".$obj->HLPnId."'";
		$result = mysqli_query($this->conexao, $this->query);
		$this->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function excluir($usuario,$senha,$servidor,$porta,$bancoDados,$tabela,$index)
	{
		$this->openConexao($usuario,$senha,$servidor,$porta);
		$this->databasename = $bancoDados;
		if (!mysqli_select_db($this->conexao, $this->databasename)) 
		{
			die ('Não foi possível conectar com o banco de dados: ' . mysqli_error());
		}
		$this->query = "DELETE FROM ".$tabela." 
		WHERE HLPnId = '".$index."'";
		$result = mysqli_query($this->conexao, $this->query);
		$this->closeConexao();
		if($result === false)
			return false;
		else
			return true;	
	}
	private function closeConexao()
	{
		if(!mysqli_close($this->conexao))
		{
			die ('Não foi possível fechar a conexão com o banco de dados: ' . mysqli_error());
		}
		return true;
	}
}
?>