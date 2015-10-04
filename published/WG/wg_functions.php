<?
if(!function_exists('getLinkPrefix')){
	
	function getLinkPrefix( $level ){
		
		$pagePath = $_SERVER['PHP_SELF'];
		$pageHost = $_SERVER['HTTP_HOST'];
		$pageProtocol = 'http://';
	
		$URL = sprintf( "%s%s%s", $pageProtocol, $pageHost, $pagePath );
	
		$pathData = explodePath( $URL );
	
		if ( !strlen($pathData[count($pathData)-1]) )
			array_pop($pathData);
	
		if ( defined("WEB_CLIENT") )
			$level++;
	
		for ( $i = 1; $i <= $level; $i++ )
			array_pop( $pathData );
	
		return implode("/", $pathData);
	}
}
?>