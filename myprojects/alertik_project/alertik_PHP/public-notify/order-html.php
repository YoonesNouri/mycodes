<?php
function package_render($package_type,$order){
	global $price_list;
	if (!$price_list[$package_type]['sms'])$price_list[$package_type]['sms']='متغیر';
	if (!$order) return number_format($price_list[$package_type]['price']);
	return '<a href="#" data-price="'.number_format($price_list[$package_type]['price']).'" data-days="'.$price_list[$package_type]['days'].'" data-sms="'.$price_list[$package_type]['sms'].'" data-title="'.$price_list[$package_type]['title'].'" data-type="'.$package_type.'">'.number_format($price_list[$package_type]['price']).'</a>';
}
function package_table($l=false){
	return '<div  id="price_table"><table class="common_tbl">
<tbody>
<tr>
	<th colspan="6">سرویس عمومی / بر مبنای ساعات مشخص: در این نوع سرویس ها، محتوای پیامک در زمان های معینی برای شما ارسال می شود.</th>	
</tr>
<tr>
	<td>نام سرویس</td>
	<td>محتویات پیامک</td>
	<td colspan="2">زمان ارسال</td>
	<td>30 روز</td>
	<td>120 روز</td>
</tr>
<tr class="even">
	<td>سکه و طلا</td>
	<td>سکه جدید، مثقال طلا و طلای 18 عیار</td>
	<td colspan="2">یک عدد ظهر، یک عدد در طول روز و یک عدد بعد از ظهر</td>
	<td>'.package_render('ti1',$l).'</td>
	<td>'.package_render('ti4',$l).'</td>
</tr>
<tr>
	<td>طلا</td>
	<td>مثقال طلا، طلای 18 عیار و انس</td>
	<td colspan="2">یک عدد ظهر، یک عدد در طول روز و یک عدد بعد از ظهر</td>
	<td>'.package_render('tg1',$l).'</td>
	<td>'.package_render('tg4',$l).'</td>
</tr>
<tr class="even">
	<td>سکه</td>
	<td>سکه جدید، قدیم، نیم، ربع و گرمی</td>
	<td colspan="2">یک عدد ظهر، یک عدد در طول روز و یک عدد بعد از ظهر</td>
	<td>'.package_render('tc1',$l).'</td>
	<td>'.package_render('tc4',$l).'</td>
</tr>
<tr>
	<th colspan="6">سرویس عمومی / بر مبنای نوسان: در این نوع سرویس ها، محتوای پیامک در پی نوسانات قیمت برای شما ارسال می شود.</th>
</tr>
<tr>
	<td>نام سرویس</td>
	<td>محتویات پیامک</td>
	<td>زمان ارسال</td>
	<td>حداقل فاصله زمانی</td>
	<td>30 روز</td>
	<td>120 روز</td>			
</tr>
<tr class="even">
	<td>سکه و طلا</td>
	<td>سکه جدید، مثقال طلا و طلای 18 عیار</td>
	<td>در تغییرات بیش از 0.5 درصدی هر یک از محتویات</td>
	<td>30 دقیقه</td>
	<td>'.package_render('ci1',$l).'</td>
	<td>'.package_render('ci4',$l).'</td>
</tr>
<tr>
	<td>طلا</td>
	<td>مثقال طلا، طلای 18 عیار و انس</td>
	<td>در تغییرات بیش از 0.5 درصدی هر یک از محتویات</td>
	<td>30 دقیقه</td>
	<td>'.package_render('cg1',$l).'</td>
	<td>'.package_render('cg4',$l).'</td>
</tr>
<tr class="even">
	<td>سکه</td>
	<td>سکه جدید، قدیم، نیم، ربع و گرمی</td>
	<td>در تغییرات بیش از 0.5 درصدی هر یک از محتویات</td>
	<td>30 دقیقه</td>
	<td>'.package_render('cc1',$l).'</td>
	<td>'.package_render('cc4',$l).'</td>
</tr>
<tr>
	<th colspan="6">سرویس 2 سویه و سرویس اختصاصی: در این نوع سرویس ها شما تعداد مشخصی پیامک خریداری می کنید و بسته به نیاز خود، نحوه دریافت پیامک ها را تنظیم می نمایید.</th>
</tr>
<tr>
	<td>نام سرویس</td>
	<td>محتویات پیامک</td>
	<td>زمان ارسال</td>
	<td>تعداد پیامک</td>
	<td></td>
	<td>120 روز</td>
</tr>
<tr class="even">
	<td>سرویس پایه</td>
	<td>قابل تنظیم توسط شما</td>
	<td>قابل تنظیم توسط شما</td>
	<td>60</td>
	<td></td>
	<td>'.package_render('ss4',$l).'</td>
</tr>
<tr>
	<td>سرویس مقدماتی</td>
	<td>قابل تنظیم توسط شما</td>
	<td>قابل تنظیم توسط شما</td>
	<td>180</td>
	<td></td>
	<td>'.package_render('sm4',$l).'</td>
</tr>
<tr class="even">
	<td>سرویس پیشرفته</td>
	<td>قابل تنظیم توسط شما</td>
	<td>قابل تنظیم توسط شما</td>
	<td>540</td>
	<td></td>
	<td>'.package_render('sb4',$l).'</td>
</tr>
</table>
<br />
<ul class="notes">
	<li>قیمت تمام بسته ها به ریال می باشند.</li>
	<li>در صورت عدم نوسان نرخ به میزان قید شده حداقل دو پیامک ارسال خواهد شد.</li>
	<li>میزان تغییرات به نسبت پیامک قبلی محاسبه می شود.</li>
	<li>توجه داشته باشید در صورت انتخاب بسته اشتراک با حداقل فاصله زمانی 30 دقیقه در صورت نوسانات شدید ممکن است طی روز تا 20 پیامک دریافت نمایید.</li>
</ul></div>';
}/*
<tr>
	<td>آتی کار</td>
	<td>تمام ماه های آتی</td>
	<td>در تغییرات بیش از 1 درصدی هر یک از محتویات</td>
	<td>20 دقیقه</td>
	<td>'.package_render('ca1',$l).'</td>
	<td>'.package_render('ca4',$l).'</td>
</tr>*/