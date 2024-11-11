<?php
$options='<option value="0_0">ریال ایران</option><option value="3_3">گرم طلای 18</option><option value="3_10">سکه بهار آزادی</option><option value="3_11">سکه امامی</option><option value="3_12">سکه نیم</option><option value="3_13">سکه ربع</option><option value="3_14">سکه گرمی</option><option value="3_40">دلار</option><option value="3_41">یورو</option><option value="3_42">پوند</option><option value="3_43">درهم امارات</option><option value="3_44">دلار کانادا</option><option value="3_45">یوان چین</option><option value="3_46">لیره ترکیه</option>';
/*<option value="1_47">ین ژاپن</option><option value="3_48">روپیه هند</option><option value="3_49">دلار استرالیا</option><option value="3_50">کرون سوئد</option><option value="3_51">فرانک سوئیس</option><option value="1_52">کرون نروژ</option><option value="1_53">دلار هنگ کنگ</option><option value="3_54">رینگیت مالزی</option><option value="3_55">دینار کویت</option><option value="3_56">دینار عراق</option><option value="1_57">بات تایلند</option><option value="1_58">روپیه پاکستان</option><option value="1_60">افغانی</option><option value="3_61">ریال عربستان</option><option value="3_62">روبل روسیه</option><option value="3_63">دلار سنگاپور</option><option value="1_64">کرون دانمارک</option><option value="1_65">وون کره جنوبی</option>*/
?>
<div class="box">
	<table id="conversation_tool" class="tools">
	<tr class="legend"><th>مقدار<th>واحد<th><th>واحد<th>نتیجه</tr>
	<tr>
		<td width="25%"><input id="conv_input" size="12"></td>
		<td width="20%" class="fa"><select id="conv_currency_side1"><?=$options?></select></td>
		<td width="10%"> = </td>
		<td width="20%" class="fa"><select id="conv_currency_side2"><?=$options?></select></td>
		<td width="25%" id="con_result"></td>
	</tr></table>
	<span class="s0_0 h">1</span>
</div>