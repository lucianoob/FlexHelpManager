package model.vo
{
	[RemoteClass(alias="vo.ajuda")]

	[Bindable]
	public class ajuda
	{
		public var HLPnId:int;
		public var HLPsSetor:String;
		public var HLPnCapitulo:Number=1.01;
		public var HLPsTitulo:String;
		public var HLPsDescricao:String;
		public var HLPsTags:String;
		public var HLPbExibir:String;
		public var HLPdAlteracao:*;
		public var HLPdInclusao:*;
	}
}
