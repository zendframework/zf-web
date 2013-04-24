package
{
	import flash.utils.ByteArray;

	[RemoteClass (alias="ComplexBARequest")]
	public class ComplexBARequest
	{
		/**
		 * @type int
		 */
		public var id:int;
		
		/**
		 * @type flash.utils.ByteArray
		 */
		public var image:ByteArray;
		
		/**
		 * @type string
		 */
		public var message:String;
		
		/**
		 * @type string
		 */
		public var result:String;
		
		/**
		 * @type String; 
		 */
		public var title:String;
		
		public function ComplexBARequest()
		{
		}
	}
}