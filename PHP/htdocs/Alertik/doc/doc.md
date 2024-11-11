exch_rate | backlog
20 -> Currency from gold
								------World-------	-----------------------Iran-----------------------------------
	Code 	currency_id new		A.Rate	B.World(A)	C.CBank		D.Market (Paper)	F.Market (Transfer/Coin Market)
	1		Gold Ounce 995		1
	2		Gold Mesghal 705			A.1						5|4
	3		Gram of 18K					A.1						D.2
	5		Silver				1
	6		Platinium
	7		Palladium
	8		Light Sweet Crude Oil
	10		Old Coin					A.1						5|4					D.2
	11		New Coin					A.1			6			5|4					D.2
	12		Half Coin					A.1			6			5|4					D.2
	13		Quarter Coin				A.1			6			5|4					D.2
	14		Gram Coin					A.1			6			5|4					D.2
	40		Dollar									6			5|4					7
	41		Euro				3					6			4|7					7
	42		Pound				3					6			4|7					7
	43		Derham				3					6			4|7					7
	44		Canada Dollar		3					6			4|7					7
	45		Chinese Yuan		3					6			4|7
	46		Turkish Lira		3					6			4|7
	47		Japaniese Yen		3					6			4|7
	48		Indian Rupee		3					6			4
	49		Australian Dollar	3								4|7					7
	50		Swedish Kron		3					6			4|7
	51		Swiss Frank			3					6			4|7
	52		krone norvej		3					6			4|7
	53		hong kong dollar	3					6			4|7
	54		ringit malezi		3								4|7
	55		dinare koveit		3								4|7
	56		dinar iraq			3								4
	57		bat thailand		3								4
	58		roopie pakestan		3								4
	59		manate azarbaijan									4
	60		Afghani				3								4
	61		rial arabestan		3					6			4|7
	62		Rubl russie			3					6
	63		Sangapur			3								7
	64		Denmark				3					6			4|7
	65		Korea				3					6
	113		Spider(RateType:13)
	114		TSE(RateType:13)

	rate_type
		0	World Rate
		1	World Exchange Rate
		2 	Centeral Bank Rate
		3	Market Rate for physical
		4	Market rate for transfer - Company
		5	Market rate for transfer - Person
		6 	Currency local market guess
		13	Custom such as Spider
		15	2nd Central Bank Rate
		16	asso-exir
		17 	Sanaa
		20	Based on gold

	duration_type: exch_archive
		0 Daily
		1 Monthly
		2 Monthly Jalali
	addedon
		timestamp
	source_id
		1	kitco
		2	bulliondesk
		3	xe
		4	mesghal
		5	tala.ir
		6	Melli.ir
		7	abanexchange
		8	livedata
		9	tala.ir webservice
		10	kitcofa
		11 	tabloaramis
		13	Manual TGJU
		14 	eranico
		15 	goldinfo
		32	Spider
		33	TSE
		34	Central Bank - Second Rate
		35	asso-exir
		36	sana
		100	Tehran Exchange - Sarafi Watch
	buy
		price you can buy
	sell
		price you can sell
	Storage format
		id >= 40, 10 rial
		id < 40 , 10000 rial

