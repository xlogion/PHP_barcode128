<?php
class bcode128 {
    const K_STARTA = 103;
    const K_STARTB = 104;
    const K_STARTC = 105;

    const K_STOP = 106;

	protected $type="auto"; //编码类型
	protected $string; //编码文本
	protected $keysA, $keysB, $keysC, $bcode; 
	protected $width=200;
	protected $height=50;
	protected $margin_left=0;
	protected $margin_top=0;
	protected $margin_right=0;
	protected $margin_bottom=0;
	protected $img_type='png';
	protected $color='#000';
	protected $out_file=NULL;
	protected $top_text='';
	protected $top_size=15;
	protected $top_margin=5;
	protected $top_font='font/microhei.ttc';
	protected $top_color='';
	protected $bottom_text='';
	protected $bottom_size=15;
	protected $bottom_margin=5;
	protected $bottom_font='font/Arial.ttf';
	protected $bottom_color='';

	protected $allow_options=array('type','width','height','margin','img_type','color','out_file','top_text','top_size','top_margin','top_font','top_color','bottom_text','bottom_size','bottom_margin','bottom_font','bottom_color'); 

    function __construct($string = NULL,$options=array()) {
		$this->initialize_code(); //初始化字符编码
		$this->string=$string;
		foreach ($options as $k=>$v) {
			if ($v!='') {
				if ($k=='margin') {
					$t_array=@explode(',',$v);
					if (count($t_array)==1) {
						$this->margin_left=$this->margin_top=$this->margin_right=$this->margin_bottom=$v;
					}
					else if (count($t_array)==2) {
						$this->margin_left=$this->margin_right=$t_array[0];
						$this->margin_top=$this->margin_bottom=$t_array[1];
					}
					else if (count($t_array)==4) {
						list($this->margin_left,$this->margin_bottom,$this->margin_right,$this->margin_top) = $t_array;
					}
				}
				else {
				in_array($k,$this->allow_options) &&  $this->$k=$v;
				}
			}
		}

		
		if ($this->type=='auto') {
			$this->type=$this->get_type();
		}
		
    }


	function __destruct() { 
	
	} 
	
	//获取编码类型
	private function get_type() { 
		$length=strlen($this->string);
		if ($length >= 4 && preg_match('/^['.preg_quote($this->keysC, '/').']+$/',$this->string) && $length%2==0 ) {
			$this->type = 'C';
		}
		else {
			if ($length>0 && preg_match('/^['.preg_quote($this->keysA, '/').']+$/',$this->string) ) {
				$this->type = 'A';
			}
			else {
				$this->type = 'B';
			}

		}
		
		return $this->type;
	}
   
	//初始化各个字符编码
	private function initialize_code() { 
		  /* CODE 128 A */
        $this->keysA = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
        for ($i = 0; $i < 32; $i++) {
            $this->keysA .= chr($i);
        }
        /* CODE 128 B */
        $this->keysB = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'.chr(127);
        /* CODE 128 C */
        $this->keysC = '0123456789';

		$this->bcode = array('212222','222122','222221','121223','121322','131222','122213','122312','132212','221213','221312','231212','112232','122132','122231','113222','123122','123221','223211','221132','221231','213212','223112','312131','311222','321122','321221','312212','322112','322211','212123','212321','232121','111323','131123','131321','112313','132113','132311','211313','231113','231311','112133','112331','132131','113123','113321','133121','313121','211331','231131','213113','213311','213131','311123','311321','331121','312113','312311','332111','314111','221411','431111','111224','111422','121124','121421','141122','141221','112214','112412','122114','122411','142112','142211','241211','221114','413111','241112','134111','111242','121142','121241','114212','124112','124211','411212','421112','421211','212141','214121','412121','111143','111341','131141','114113','114311','411113','411311','113141','114131','311141','411131','211412','211214','211232','2331112');
		return true;
	}
	
	//设定宽度,高度
	public function set_wh($width=200,$height=50) {
		$this->width = $width;
		$this->height = $height;
	}
	
	//输出图片
	public function img() { 
		$result=$this->get_string();
		//print_r($result);

		if (($this->width-($this->margin_left+$this->margin_right)) < $result['min_width']) {
			$this->width=$result['min_width']+($this->margin_left+$this->margin_right);
		}

		$img=imagecreate($this->width,$this->height); 
		$white = imagecolorallocate($img, 255, 255, 255); //背景色白色

		$t_color=$this->hex2rgb($this->color);
		$color = imagecolorallocate($img,$t_color['red'],$t_color['green'],$t_color['blue']); 

		if ($this->top_color=='') {
			$top_color=$color;
		}
		else {
			$t_top_color=$this->hex2rgb($this->top_color);
			$top_color = imagecolorallocate($img,$t_top_color['red'],$t_top_color['green'],$t_top_color['blue']); 
		}

		if ($this->bottom_color=='') {
			$bottom_color=$color;
		}
		else {
			$t_bottom_color=$this->hex2rgb($this->bottom_color);
			$bottom_color = imagecolorallocate($img,$t_bottom_color['red'],$t_bottom_color['green'],$t_bottom_color['blue']); 
		}

		
		$width=($this->width-($this->margin_left+$this->margin_right))/$result['min_width'];

		$x=$this->margin_left; 
		//计算顶部字符串
		$top_height=0;
		if ($this->top_text!='') {
			$t_info=imagettfbbox($this->top_size, 0, $this->top_font, $this->top_text);
			$t_width = abs($t_info[4] - $t_info[0]); //字体宽度
			$t_height = abs($t_info[5] - $t_info[1]); //字体高度
			$top_height=$t_height+$this->top_margin; //预留高度
			$t_y=$this->margin_top+$t_height; //字体Y坐标
			$limit_width=$t_width/mb_strlen($this->top_text,'utf8'); //单个字符宽度
			$t_m_width=(($this->width-($this->margin_left+$this->margin_right)-$limit_width)/(mb_strlen($this->top_text,'utf8')-1)); //间隔宽度
			
			//支持中文
			$t_x=$this->margin_left; 
			for ($i=0;$i<mb_strlen($this->top_text,'utf8');$i++) {
				$v=mb_substr($this->top_text, $i, 1,'utf8');
				ImageTTFText($img,$this->top_size,0,$t_x,$t_y,$top_color,$this->top_font,$v);
				$t_x=$t_x+$t_m_width;
			}
			/*
			$t=str_split($this->top_text);
			$t_x=$this->margin_left; 
			foreach ($t as $v) {
				ImageTTFText($img,$this->top_size,0,$t_x,$t_y,$color,$this->top_font,$v);
				$t_x=$t_x+$t_m_width;
			}
			*/
		}



		$y=$this->margin_top+$top_height; 


		//计算底部字符串
		$sub_height=0;
		if ($this->bottom_text!='') {
			$t_info=imagettfbbox($this->bottom_size, 0, $this->bottom_font, $this->bottom_text);
			$t_width = abs($t_info[4] - $t_info[0]); //字体宽度
			$t_height = abs($t_info[5] - $t_info[1]); //字体高度
			$sub_height=$t_height+$this->bottom_margin; //预留高度
			$t_y=($this->height-$t_height-$this->margin_bottom)+$t_height; //字体Y坐标
			$limit_width=$t_width/strlen($this->bottom_text); //单个字符宽度
			$b_m_width=(($this->width-($this->margin_left+$this->margin_right)-$limit_width)/(strlen($this->bottom_text)-1)); //间隔宽度

		}

		$height=$this->height-$this->margin_top-$this->margin_bottom-$sub_height-$top_height;



		
		$temp=0;
		foreach ($result['date'] as $v) {
			$t=str_split($v);
			foreach ($t as $v) {
				if ($temp==0) imagefilledrectangle($img, $x, $y, $x+($width*$v), $y+$height, $color); 
				else imagefilledrectangle($img, $x, $y, $x+($width*$v), $y+$height, $white); 
				
				$x=$x+($width*$v);
				if ($temp==0) $temp=1;
				else $temp=0;
			}
		}

		//写入底部文字
		if ($this->bottom_text!='') {
			$t=str_split($this->bottom_text);
			$t_x=$this->margin_left; 
			foreach ($t as $v) {
				ImageTTFText($img,$this->bottom_size,0,$t_x,$t_y,$bottom_color,$this->bottom_font,$v);
				$t_x=$t_x+$b_m_width;
			}

		}

		switch ($this->img_type) {
			case 'png':
			  $this->out_file==NULL && header('Content-Type: image/png');
			  imagepng($img,$this->out_file);
			  break;
			case 'jpg':
			  $this->out_file==NULL && header('Content-Type: image/jpeg');
			  imagejpeg($img,$this->out_file, 100);
			  break;
			case 'gif':
			  $this->out_file==NULL && header('Content-Type: image/gif');
			  imagegif($img,$this->out_file);
			  break;
			default:
			  $this->out_file==NULL && header('Content-Type: image/png');
			  imagepng($img,$this->out_file);
		}
		imagedestroy($img);
		return true;
	}

	private function hex2rgb( $colour ) {
		if ( $colour[0] == '#' ) {
			$colour = substr( $colour, 1 );
		}
		if ( strlen( $colour ) == 6 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
		} 
		elseif ( strlen( $colour ) == 3 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
		} 
		else {
			return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );
		return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	}

	//获取字符编码
	private function get_string() { 
		$date=array();
		switch ($this->type) {
			case 'A':
				$date[]=$this->bcode[self::K_STARTA];
				$check_s=self::K_STARTA;
				$f=str_split($this->string);
			    $search=str_split($this->keysA);
			  break;
			case 'B':
			   $date[]=$this->bcode[self::K_STARTB];
			   $check_s=self::K_STARTB;
			   $f=str_split($this->string);
			   $search=str_split($this->keysB);
			  break;
			case 'C':
			   $date[]=$this->bcode[self::K_STARTC];
			   $check_s=self::K_STARTC;
			   $f=str_split($this->string,2);
			   //$search=$this->keysC;
			  break;
			default:
			 // 出错返回
		}
		
		foreach ($f as $k=>$v) {
			if ($this->type=='C') {
			$limit=(int)$v;
			$date[]=$this->bcode[$limit];
			}
			else {
			$limit=array_search($v,$search);
			$date[]=$this->bcode[$limit];
			}
			$check_s+=($k+1)*$limit;
		}
		
		$date[]=$this->bcode[$check_s%103];
		$date[]=$this->bcode[self::K_STOP];
		
		$min_width=0;
		//$temp='b';
		foreach ($date as $v) {
			$t=str_split($v);
			foreach ($t as $v) {
				$min_width=$min_width+$v;
				}
		}
		//unset($date);
		
		$result['text']=$this->string;
		$result['date']=$date;
		$result['min_width']=$min_width;
		return $result;

	}

}