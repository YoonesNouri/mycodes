<?php
$title='ابزار وب مستر';
?>
<div id="webmaster_tools">

<table>
<tbody>
<tr><td colspan="2" class="fa">شما می توانید از هر یک از این ابزار های زیر برای قرار دادن نرخ ها بصورت مجانی روی سایت خود استفاده نمایید.
تغییر تمام کدها به غیر از لینک سایت ارزلایو آزاد بوده. تغییراتی که اعمال می نمایید نباید ماهیت جداول قیمت را تغییر داده و باعث به خطا افتادن کاربر شود.
</td></tr>
<tr><th colspan="2">ابزار جاوا اسکریپت</th></tr>
<tr><td colspan="2">انتخاب قیمت های مورد نظر</td></tr>
<tr><td colspan="2" class="tools" id="currencies"><?=web_items_checkboxes()?></td></tr>
<tr><td>تنظیمات نمایش</td><td>پیش نمایش جدول</td></tr>
<tr><td class="tools">
	<div class="wbox">
		<div class="wrow">انتخاب نوع جدول</div><div class="winp"><input type="radio" name="type" value="h" checked="yes" /> عمودی <input type="radio" name="type" value="v" /> افقی</div>
		<div class="wrow">عرض جدول به پیکسل</div><div class="winp"><input type="text" id="width_px" value="180" size="7" /></div>
		<div class="wrow">عرض جدول به درصد</div><div class="winp"><input type="text" id="width_perc" value="" size="7" /></div>
		<div class="wrow">ارتفاع جدول به پیکسل</div><div class="winp"><input type="text" id="height_px" value="" size="7" /></div>
		<div class="clear"></div>
	</div>
	<div class="wbox">
		<div class="wrow">ضخامت فریم دور</div><div class="winp"><input type="text" class="colors" id="border-width" value="1" size="7" /></div>
		<div class="wrow">رنگ فریم دور</div><div class="winp"><input type="text" class="colors" id="border-color" value="#799FC6" size="7" /></div>
		<div class="wrow">فاصله فریم دور از محتوا داخل</div><div class="winp"><input type="text" id="border-padding" value="3" size="7" /></div>
		<div class="wrow">فاصله حاشیه اطراف بخش های محتوا</div><div class="winp"><input type="text" id="td-padding" value="5" size="7" /></div>
		<div class="clear"></div>
	</div>
	<div class="wbox">
		<div class="wrow">سایز نوشته تیتر</div><div class="winp"><input type="text" id="font_header" value="12" size="7" /> </div>
		<div class="wrow">رنگ نوشته ها تیتر</div><div class="winp"><input type="text" class="colors" id="head-color" value="#000000" size="7" /></div>
		<div class="wrow">رنگ پس زمینه تیتر</div><div class="winp"><input type="text" class="colors" id="header-backcolor" value="#A9D2FF" size="7" /></div>
		<div class="clear"></div>
	</div>
	<div class="wbox">
		<div class="wrow">سایز نوشته نام نرخ و یا ردیف زوج</div><div class="winp"><input type="text" class="colors" id="price-title-size" value="12" size="7" /></div>
		<div class="wrow">پس زمینه نام نرخ و یا ردیف زوج</div><div class="winp"><input type="text" class="colors" id="price-title-color" value="#ffffff" size="7" /></div>
		<div class="wrow">سایز نوشته نرخ  و یا ردیف فرد</div><div class="winp"><input type="text" class="colors" id="price-size" value="11" size="7" /></div>
		<div class="wrow">پس زمینه نرخ و یا ردیف فرد</div><div class="winp"><input type="text" class="colors" id="price-color" value="#EAF4FF" size="7" /><br /></div>
		<div class="clear"></div>
	</div>
	<div class="wbox">
		<div class="wrow">سایز نوشته</div><div class="winp"><input type="text" id="font_size" value="9" size="7" /> </div>
		<div class="wrow">رنگ نوشته ها </div><div class="winp"><input type="text" class="colors" id="font-color" value="#333333" size="7" /></div>
		<div class="clear"></div>
	</div>
	<div class="wbox">
		<div class="wrow">رنگ به روز رسانی </div><div class="winp"><input type="text" class="colors" id="time-color" value="#999999" size="7" /></div>
		<div class="wrow">سایز نوشته به روز رسانی </div><div class="winp"><input type="text" id="time-size" value="10" size="7" /><br /></div>
		<div class="wrow">رنگ نرخ بدون تغییر </div><div class="winp"><input type="text" class="colors" id="same" value="#666666" size="7" /></div>
		<div class="wrow">رنگ تغییر مثبت </div><div class="winp"><input type="text" class="colors" id="pos" value="#009900" size="7" /></div>
		<div class="wrow">رنگ تغییر منفی </div><div class="winp"><input type="text" class="colors" id="neg" value="#cc0000" size="7" /></div>
		<div class="clear"></div>
	</div>
</td><td><div id="preview" style="overflow:auto;width:480px;height:550px;"></div></td></tr>
<tr><td class="fa">این کد را در محلی که میخواهید لیست قیمت نمایش داده شود قرار دهید
<br />کاربران حرفه ای: شما میتوانید کد زیر را به دلخواه خود تغییر دهید در صورت تغییر مستقیم کد پیش نمایش آن به روز رسانی می شود. همچنین بهتر هست کد سی اس اس را به فایل سی اس اس سایت خود منتقل نمایید. 
</td><td class="fa">این کد را در انتهای صفحه قبل از تگ &lt;body/&gt; قرار دهید</td></tr>
<tr><td><textarea cols="50" rows="10" id="code"></textarea></td>
<td><textarea cols="50" rows="10"><script type="text/javascript" src="http://service.arzlive.com/p.js"></script></textarea></td></tr>
</table>
<!--<table>
	<tr>
		<th colspan="2">
			تصاویر قیمت ها<br />
			نمونه کد اچ تی ام ال قابل استفاده برای قرار دادن قیمت ها در کنار هر تصویر آورده شده است.
		</th>
	</tr>
	<tr>
		<td><img src="http://service.arzlive.com/p-blue.png" alt="قیمت طلا، ارز و سکه" /></td>
		<td><textarea rows="10" cols="50"><a href="http://www.arzlive.com"><img src="http://service.arzlive.com/p-blue.png" alt="قیمت طلا، ارز و سکه" /></a></textarea></td>
	</tr>
	<tr>
		<th colspan="2">
			تصاویر به صورت خودکار هر پنج دقیقه یک بار به روز رسانی می شوند.<br />
			در صورتی که وب سایت و یا صفحه وب پر ترافیکی دارید امکان ارائه تصاویر اختصاصی با طرح، اطلاعات و سایز مورد نیاز شما به صورت مجانی وجود دارد. لطفا با ما تماس حاصل نمایید.
		</th>
	</tr>
</table>-->
<script src="http://service.arzlive.com/p.js" type="text/javascript"></script>
</div>
<?php
function web_items_checkboxes(){
	$latest_exchanges=get_json('latest_exchanges');
	$cur=array(40=>'دلار',41=>'یورو',42=>'پوند',43=>'درهم امارات');
	$basic=array('0:1'=>'انس جهانی به دلار','3:2'=>'مثقال طلا در بازار','3:3'=>'گرم طلای 18 در بازار','3:10'=>'سکه بهار آزادی','3:11'=>'سکه امامی','3:12'=>'سکه نیم','3:13'=>'سکه ربع','3:14'=>'سکه گرمی');

	foreach ($basic as $key => $name) {
		if ($latest_exchanges[$key]['sell']>0) ; else continue;
		$out.='<div><label for="'.str_replace(':','_',$key).'">'.$name.'<input type="checkbox" id="'.str_replace(':','_',$key).'" class="exp_currencies" value="1" checked="checked" /></label></div>';
	}

	$out.='<div class="clear"> </div><br />';
	foreach ($cur as $key => $name) {
		if ($latest_exchanges['3:'.$key]['sell']>0) ; else continue;
		$out.='<div><label for="3_'.$key.'">'.$name.' آزاد<input type="checkbox" id="3_'.$key.'" value="1" class="exp_currencies" /></label></div>';
	}
	return $out;
}