<?php
/**
 * 汉字繁简转换类
 * @author wangxi
 */
class ccConverter {
	private $simple;
	private $complex;
	function __construct() { 
		$fd = fopen( CORE_LIB_PATH."char_table/simple.list" , 'r' );
		$this->simple = trim(fread( $fd , filesize(CORE_LIB_PATH."char_table/simple.list") ));
		fclose( $fd );
		
		$fd = fopen( CORE_LIB_PATH."char_table/complex.list" , 'r' );
		$this->complex = trim(fread( $fd , filesize(CORE_LIB_PATH."char_table/simple.list") ));
		fclose( $fd );
	} 
	
	function indexOf($sorce,$chinese) {
	   return ( mb_strpos($sorce,$chinese,null,"utf8") );
	}

	function charAt($sorce,$numpos) {
		return ( mb_substr($sorce,$numpos,1,"utf8") );
	}	
	
	function comtosim( $txt ) {
		$sim = '';
		for ($i= 0; $i< mb_strlen($txt,"utf8"); $i++ ) { //get chinese len
			if ( $this->indexOf( trim($this->complex) , $this->charAt($txt,$i) ) !=false ) { //判断 汉字是否在简体字表中
				$sim .= $this->charAt( trim($this->simple) , $this->indexOf( trim($this->complex) , $this->charAt($txt,$i) ) );
			}else{
				$sim .= $this->charAt( $txt , $i );
			}
		}
		return $sim;
	}	
	
}


/*


function simtocom($txt){
global $simple,$complex;


for ($i= 0; $i< mb_strlen($txt,"GB2312"); $i++ ) { //get chinese len

      if ( indexOf( trim($simple),charAt($txt,$i) ) !=false ){ //判断 汉字是否在简体字表中


   $com .=charAt(     trim($complex),indexOf( trim($simple),charAt($txt,$i) )    );

   }else{

   $com .=charAt($txt,$i);

   }

}
return $com;
}

function comtosim($txt){
global $simple,$complex;
$sim = '';
for ($i= 0; $i< mb_strlen($txt,"Big5"); $i++ ) { //get chinese len

      if ( indexOf( trim($complex),charAt($txt,$i) ) !=false ){ //判断 汉字是否在简体字表中


   $sim .=charAt(     trim($simple),indexOf( trim($complex),charAt($txt,$i) )    );

   }else{

   $sim .=charAt($txt,$i);

   }

}
return $sim;
}


function indexOf($sorce,$chinese) {
   return ( mb_strpos($sorce,$chinese,null,"GB2312") );
}
function charAt($sorce,$numpos) {
return ( mb_substr($sorce,$numpos,1,"Big5") );
}

echo simtocom('我爱中国');
echo '<br>';
echo comtosim('锕皑蔼碍');
*/