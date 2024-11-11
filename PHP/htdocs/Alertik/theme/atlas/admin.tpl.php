<?php
$title='پنل ادمین';
$rates=array(40=>'دلار',41=>'یورو',42=>'پوند',43=>'درهم امارات',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',53=>'دلار هنگ کنگ',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',57=>'بات تایلند',58=>'روپیه پاکستان',60=>'افغانی',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی');
?>
<form method="post" id="link_buttons">
<div id="notice"></div>
<table>
	<tr>
		<th width="100px">نوع ارز</th>
		<th width="90px">خرید صرافی</th>
		<th width="80px">تغییر</th>
		<th width="90px">فروش صرافی</th>
		<th width="80px">تغییر</th>
		<th width="100px">اسپرد</th>

		<th width="90px"><a href="#" data-click="column_1">خرید نت</a></th>
		<th width="90px"><a href="#" data-click="column_2">فروش نت</a></th>

		<th width="90px"><a href="#" data-click="column_3">خرید بر دلار</a></th>
		<th width="90px"><a href="#" data-click="column_4">فروش بر دلار</a></th>

		<th width="90px">+اسپرد</th>
	</tr>
	<?php foreach ($rates as $id => $name) { ?>
		<tr>
			<td><?=$name?></td>
			<td><input name="b_<?=$id?>" value="<?=current_price('b_'.$id,$default)?>" size="6" data-price="<?=current_price('b_'.$id,$default)?>" class="buy b_<?=$id?>" data-change="cb_<?=$id?>" data-spread="<?=$id?>"></td>
			<td class="cb_<?=$id?> small_text"></td>
			<td><input name="s_<?=$id?>" value="<?=current_price('s_'.$id,$default)?>" size="6" data-price="<?=current_price('s_'.$id,$default)?>" class="sell s_<?=$id?>" data-change="cs_<?=$id?>" data-spread="<?=$id?>"></td>
			<td class="cs_<?=$id?> small_text"></td>
			<td class="sp_<?=$id?> small_text"></td>

			<td><a href="#" class="column_1" data-loc="b_<?=$id?>"><?=number_format(last(3,$id,'buy'))?></a></td>
			<td><a href="#" class="column_2" data-loc="s_<?=$id?>"><?=number_format(last(3,$id,'sell'))?></a></td>

			<td><a href="#" class="column_3 dollar_buy" data-id="<?=$id?>" data-exchange="<?=exchange_rate_get($id)?>" data-loc="b_<?=$id?>"><?=based_on_dollar($id,'buy')?></a></td>
			<td><a href="#" class="column_4 dollar_sell" data-id="<?=$id?>" data-exchange="<?=exchange_rate_get($id)?>" data-loc="s_<?=$id?>"><?=based_on_dollar($id,'sell')?></a></td>

			<td><input name="p_<?=$id?>" class="p_<?=$id?> add_spread" value="<?=current_price('p_'.$id,$default)?>" size="4"></td>
		</tr>		
	<?php }	?>
</table>
<input type="submit" name="submit" value="ذخیره">
</form>
<?php 
function current_price($id,$default){
	$val=$default[$id];
	if ($val) return $val;
	return 0;
}
function based_on_dollar($id,$type){
	if ($id==40) return '';
	$currency=last(0,$id,'sell');
	if (!$currency) return '';

	return number_format(last(3,40,$type)/last(0,$id,'sell'));
}
function exchange_rate_get($id){
	$ex=last(0,$id,'sell');
	if (!$ex) return 0;
	else return $ex;
}