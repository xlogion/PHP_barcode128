# `PHP barcode 128` 简介
----
- ## 说明
    
    ```
    PHP barcode 128
    
    是一个支持自定义宽度和高度的128条码类
    ```
    
	
- ## 使用示例
	```
	require_once('class/bcode128.class.php');



	$options=array(
			'type'=>'A', //设定编码类型 不传递为自动判断
			'width'=>400, //设定生成图片的宽度
			'height'=>200, //设定生成图片的高度
			'margin'=>'10', //边距 传递 1个参数时同时设定四周  传递 2个参数时 为左右,上下 传递4个参数时，为 左,下,右,上
			'img_type'=>'gif', //输出图片类型 png jpg gif 
			'color'=>'#000', //输出颜色
			//'out_file'=>'out_file/1.gif', //输出的文件路径
			'top_text'=>'php生成图形条形码演示',
			'top_size'=>20,
			'top_margin'=>10,
			'top_font'=>'font/microhei.ttc',
			//'top_color'=>'#000', //顶部文字颜色
			'bottom_text'=>'352136423333',
			'bottom_size'=>20,
			'bottom_margin'=>10,
			'bottom_font'=>'font/Arial.ttf',
			//'bottom_color'=>'#330000', //底部文字颜色
			 );
	$font = new bcode128('352136423333',$options);
	
	
	$font->img();

	```



----------
# `PHP barcode 128` 参数说明

- ## 输入格式

	`$font = new bcode128($barcode_string,$options);`
	
	barcode_string 为生成的条码原始数据
	
	options	一个参数的数组，支持以下列出的所有类型

----------
# `options` 参数说明

- ## 开始之前

	其实所有的参数都不是必要参数
	
	你可以按照你的需求，传递或者不传递
	
- ## type

	输出条形码格式，支持A/B/C
	
	当然也支持自动判断类型 auto
	
	`'type'=>'A'`
	
	`'type'=>'B'`
	
	`'type'=>'C'`
	
	`'type'=>'auto'` *默认为auto
	
	
- ## width

	`'width'=>400`
	
	生成的图片宽度 单位为px
	
	默认为 200px
	
- ## height

	`'height'=>50`
	
	生成的图片高度 单位为px
	
	默认为 50px
	
- ## margin

	设置图片的 4 个外边距。
	
	margin 简写属性在一个声明中设置所有外边距属性。
	
	该属性可以有 1 到 4 个值。
	
	单位为px,不可更改
	
	默认为0px

	### 例子 1
	
	`'margin'=>'10'`
	- 左外边距是 10px
	- 下外边距是 10px
	- 右外边距是 10px
	- 上外边距是 10px
	
	### 例子 2
	
	`'margin'=>'10 50'`
	- 左外边距是 10px
	- 下外边距是 50px
	- 右外边距是 10px
	- 上外边距是 50px

	### 例子 3
	
	`'margin'=>'5 15 10 20'`
	- 左外边距是 5px
	- 下外边距是 15px
	- 右外边距是 10px
	- 上外边距是 20px

	
- ### img_type

	输出的图片类型 支持 `png jpg gif`
	
	默认为 PNG
	
	`'img_type'=>'gif'`

	在没指定 out_file 的情况下
	
	将改变PHP header类型

	在指定 out_file 的情况下
	
	将改变 out_file 的扩展名

- ### color

	颜色参数
	
	`'color'=>'#000'`
	
	这个数值会改变 条形码颜色
	
	如果没有强制指定`top_color`或者`bottom_font`
	
	那么以上两个参数会被设定为 同 `color`相同的值

- ### out_file

	`'out_file'=>'out_file/1.gif'`
	
	输出的文件路径，会将文件保存至 out_file的设定值
	
	输出的文件类型，请用 `img_type` 设定

- ### top_text

	`'top_text'=>'php生成图形条形码演示'`
	
	条形码顶部文字
	
	注意，需要检查`top_font`设定的字体，是否支持输入的文字
	
- ### top_size

	`'top_size'=>20`
	
	顶部文字大小，单位px
	
- ### top_margin

	`'top_margin'=>10`
	
	顶部文字的下边距
	
	确切的说，就是顶部文字同下方条形码的距离。
		
- ### top_font

	`'top_font'=>'font/microhei.ttc'`
	
	顶部文字字体
	
- ### top_color
	
	`'top_color'=>'#000'`
	
	顶部文字颜色

- ### bottom_text

	`'bottom_text'=>'php生成图形条形码演示'`
	
	条形码底部文字
	
	注意，需要检查`bottom_font`设定的字体，是否支持输入的文字
	
- ### bottom_size

	`'bottom_size'=>20`
	
	底部文字大小，单位px
	
- ### bottom_margin

	`'bottom_margin'=>10`
	
	底部文字的上边距
	
	确切的说，就是底部文字同上方条形码的距离。
		
- ### bottom_font

	`'bottom_font'=>'font/Arial.ttf'`
	
	底部文字字体
	
- ### bottom_color
	
	`'bottom_color'=>'#000'`
	
	底部文字颜色
