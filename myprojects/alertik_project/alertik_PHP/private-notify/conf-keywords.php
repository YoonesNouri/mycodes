<?php
$i=array(
	'usd'=>'0:3:40',
	'krw'=>'0:3:65',
	'dkk'=>'0:3:64',
	'sgd'=>'0:3:63',
	'rub'=>'0:3:62',
	'sar'=>'0:3:61',
	'iqd'=>'0:3:56',
	'kwd'=>'0:3:55',
	'myr'=>'0:3:54',
	'hkd'=>'0:3:53',
	'chf'=>'0:3:51',
	'sek'=>'0:3:50',
	'aud'=>'0:3:49',
	'inr'=>'0:3:48',
	'jpy'=>'0:3:47',
	'try'=>'0:3:46',
	'cny'=>'0:3:45',
	'cad'=>'0:3:44',
	'aed'=>'0:3:43',
	'gbp'=>'0:3:42',
	'eur'=>'0:3:41',
	'kron'=>'0:3:50|0:3:64',
	'arz'=>'0:3:40|0:3:41|0:3:43',
	
	'gcoin'=>'0:3:14',
	'qcoin'=>'0:3:13',
	'hcoin'=>'0:3:12',
	'fcoin'=>'0:3:11',
	'bcoin'=>'0:3:10',
	'coin'=>'0:3:10|0:3:11|0:3:12|0:3:13',//|0:3:14
	
	'ati'=>$ati_keys,
	
	'18k'=>'0:3:3',
	'onc'=>'0:0:1',
	'mesgh'=>'0:3:2',
	'gld'=>'0:0:1|0:3:2|0:3:3',
	
	'gram'=>'0:3:3|0:3:14',
	
	'burs'=>'0:13:114',
	'spd'=>'0:13:113',
	
	'silver'=>'0:0:5',
	'platinium'=>'0:0:6',
	'palladium'=>'0:0:7',
	'oil'=>'0:0:8',	
);
$sms_keywords=array(
	/*		Gold		*/
	'18k'=>$i['18k'],'750'=>$i['18k'],'طلاي 18'=>$i['18k'],'طلای 18'=>$i['18k'],
	'gold'=>$i['gld'],'طلا'=>$i['gld'],'gld'=>$i['gld'],'tala'=>$i['gld'],'talla'=>$i['gld'],
	'mesgh'=>$i['mesgh'],'ghal'=>$i['mesgh'],'مثقال'=>$i['mesgh'],'مظنه'=>$i['mesgh'],
	'ons'=>$i['onc'],'onc'=>$i['onc'],'unc'=>$i['onc'],'انس'=>$i['onc'],'اونس'=>$i['onc'],'uns'=>$i['onc'],
	/*		Coin		*/
	'grami'=>$i['gcoin'],'gramy'=>$i['gcoin'],'گرمی'=>$i['gcoin'],
	'rob'=>$i['qcoin'],'ربع'=>$i['qcoin'],
	'nim'=>$i['hcoin'],'niim'=>$i['hcoin'],'نیم'=>$i['hcoin'],'نيم'=>$i['hcoin'],
	'emam'=>$i['fcoin'],'imam'=>$i['fcoin'],'امامی'=>$i['fcoin'],'امامي'=>$i['fcoin'],'جدید'=>$i['fcoin'],'jadid'=>$i['fcoin'],'tama'=>$i['fcoin'],'tamma'=>$i['fcoin'],'تمام'=>$i['fcoin'],'yek'=>$i['fcoin'],
	'bahar'=>$i['bcoin'],'بهار'=>$i['bcoin'],'آزاد'=>$i['bcoin'],'azad'=>$i['bcoin'],'ghadim'=>$i['bcoin'],'قدیم'=>$i['bcoin'],
	'coin'=>$i['coin'],'sekke'=>$i['coin'],'seke'=>$i['coin'],'سکه'=>$i['coin'],'سكه'=>$i['coin'],
	/*		Mixed		*/
	'gram'=>$i['gram'],'گرم'=>$i['gram'],'garm'=>$i['gram'],'geram'=>$i['gram'],'garam'=>$i['gram'],
	'arz'=>$i['arz'],'ارز'=>$i['arz'],
	/*		Ati			*/
	'burs'=>$i['burs'],'bours'=>$i['burs'],'بورس'=>$i['burs'],
	'spider'=>$i['spd'],'اسپایدر'=>$i['spd'],
	'silver'=>$i['spd'],'نقره'=>$i['spd'],'سیلور'=>$i['spd'],
	'platin'=>$i['platinium'],'پلاتین'=>$i['platinium'],
	'palad'=>$i['palladium'],'پالادیوم'=>$i['palladium'],
	'oil'=>$i['oil'],'نفت'=>$i['oil'],
	'ati'=>$i['ati'],'آتی'=>$i['ati'],'آتى'=>$i['ati'],'اتي'=>$i['ati'],'آتي'=>$i['ati'],'aty'=>$i['ati'],'atee'=>$i['ati'],

	/*		Currencies		*/
	/*'euro'=>$i['eur'],'uro'=>$i['eur'],'EUR'=>$i['eur'],'yuro'=>$i['eur'],'يور'=>$i['eur'],'یور'=>$i['eur'],'ىورو'=>$i['eur'],
	'pound'=>$i['gbp'],'pond'=>$i['gbp'],'pand'=>$i['gbp'],'poond'=>$i['gbp'],'GB'=>$i['gbp'],'پوند'=>$i['gbp'],'پند'=>$i['gbp'],'انگلیس'=>$i['gbp'],'£'=>$i['gbp'],
	'dirh'=>$i['aed'],'dhs'=>$i['aed'],'derh'=>$i['aed'],'darh'=>$i['aed'],'aed'=>$i['aed'],'uae'=>$i['aed'],'درهم'=>$i['aed'],'دیرهم'=>$i['aed'],'امارات'=>$i['aed'],
	'CAD'=>$i['cad'],'canada'=>$i['cad'],'کانادا'=>$i['cad'],'كانادا'=>$i['cad'],
	'CNY'=>$i['cny'],'china'=>$i['cny'],'chin'=>$i['cny'],'یوان'=>$i['cny'],'چین'=>$i['cny'],'yuan'=>$i['cny'],'يوان'=>$i['cny'],
	'TRY'=>$i['try'],'turk'=>$i['try'],'tork'=>$i['try'],'lir'=>$i['try'],'leer'=>$i['try'],'ترکیه'=>$i['try'],'تركيه'=>$i['try'],'لیر'=>$i['try'],'ترکی'=>$i['try'],'لير'=>$i['try'],
	'JPY'=>$i['jpy'],'yen'=>$i['jpy'],'japan'=>$i['jpy'],'ژاپن'=>$i['jpy'],'ین'=>$i['jpy'],
	'INR'=>$i['inr'],'rup'=>$i['inr'],'india'=>$i['inr'],'روپیه'=>$i['inr'],'روپی'=>$i['inr'],'هند'=>$i['inr'],
	'AUD'=>$i['aud'],'australia'=>$i['aud'],'astralia'=>$i['aud'],'ostralia'=>$i['aud'],'ostoralia'=>$i['aud'],'استرالیا'=>$i['aud'],'استراليا'=>$i['aud'],
	'SEK'=>$i['sek'],'sweden'=>$i['sek'],'sued'=>$i['sek'],'sooed'=>$i['sek'],'سوئد'=>$i['sek'],'سوید'=>$i['sek'],
	'cron'=>$i['kron'],'kron'=>$i['kron'],'کرون'=>$i['kron'],'کرن'=>$i['kron'],
	'CHF'=>$i['chf'],'frank'=>$i['chf'],'swiss'=>$i['chf'],'suiss'=>$i['chf'],'swis'=>$i['chf'],'suis'=>$i['chf'],'فرانک'=>$i['chf'],'سوییس'=>$i['chf'],'سوئیس'=>$i['chf'],'سویس'=>$i['chf'],
	'HKD'=>$i['hkd'],'hongkong'=>$i['hkd'],'hong'=>$i['hkd'],'هنگ'=>$i['hkd'],
	'MYR'=>$i['myr'],'mala'=>$i['myr'],'male'=>$i['myr'],'ring'=>$i['myr'],'مالزی'=>$i['myr'],'مالزیا'=>$i['myr'],'رینگت'=>$i['myr'],'رینگیت'=>$i['myr'],'رینگی'=>$i['myr'],
	'KWD'=>$i['kwd'],'kuw'=>$i['kwd'],'kuv'=>$i['kwd'],'kow'=>$i['kwd'],'kov'=>$i['kwd'],'dinar'=>$i['kwd'],'deenar'=>$i['kwd'],'دينار'=>$i['kwd'],'دینار'=>$i['kwd'],'كويت'=>$i['kwd'],'کویت'=>$i['kwd'],
	'IQD'=>$i['iqd'],'iraq'=>$i['iqd'],'irak'=>$i['iqd'],'iragh'=>$i['iqd'],'araq'=>$i['iqd'],'aragh'=>$i['iqd'],'arak'=>$i['iqd'],'عراق'=>$i['iqd'],'اراق'=>$i['iqd'],
	'SAR'=>$i['sar'],'saudi'=>$i['sar'],'saoodi'=>$i['sar'],'rial'=>$i['sar'],'ksa'=>$i['sar'],'arabia'=>$i['sar'],'arabestan'=>$i['sar'],'صعودی'=>$i['sar'],'سعودی'=>$i['sar'],'عربستان'=>$i['sar'],'عربی'=>$i['sar'],'ریال'=>$i['sar'],'riyal'=>$i['sar'],
	'RUB'=>$i['rub'],'rubl'=>$i['rub'],'roobl'=>$i['rub'],'russi'=>$i['rub'],'roosie'=>$i['rub'],'rossi'=>$i['rub'],'روسیه'=>$i['rub'],'روس'=>$i['rub'],
	'SGD'=>$i['sgd'],'singap'=>$i['sgd'],'sangap'=>$i['sgd'],'سنگاپور'=>$i['sgd'],'سینگاپور'=>$i['sgd'],
	'DKK'=>$i['dkk'],'denmark'=>$i['dkk'],'danmark'=>$i['dkk'],'دانمارک'=>$i['dkk'],'دنمارک'=>$i['dkk'],
	//'KRW'=>$i['krw'],'kore'=>$i['krw'],'woo'=>$i['krw'],'voo'=>$i['krw'],'wun'=>$i['krw'],'vun'=>$i['krw'],'کره'=>$i['krw'],'ون'=>$i['krw'],
	'USD'=>$i['usd'],'US'=>$i['usd'],'lar'=>$i['usd'],'dol'=>$i['usd'],'$'=>$i['usd'],'لار'=>$i['usd'],'آمریکا'=>$i['usd'],	*/
);
$sms_keywords=array_merge($sms_keywords,$two_way);
unset($i);