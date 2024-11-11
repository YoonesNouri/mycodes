line_status
	200 Is blocked line do not send
notify_sms
	current_status	
		00	Not Sent Yet
		1n	In Send
			11	Sent from trigger detection
			12 	Sent from package detection
			13	Send from two way processing
			14	Send from retry module
		2n	Sent, Wait for Confirmation
			20	OK response
			21	Strange gateway answer
		30	Timeout, Wait for Confirmation
		40	Successful
		50	Timeout, Unknown Condition
		60	Cannot Reach
source_id
	0 	Usual one
	1 	Ati Data
trigger_type
	1 	Target Price
	2 	Percentage Change
	3 	Step Change
	4	Target Date - First after it
	5	Target Date - Exact
	6	Every 2 hour - First after it
	7	Every 2 hour - Exact
	30	Recieved SMS
	50	Notification
	100	Package based trigger (trigger_id equal to pack_id)

notify_sms_incoming:result
	0 		Successful SMS with Money
	5 		Successful free SMS
	1,6		Credit
	2,7		Missmatch
	3,8		Credit+Missmatch
sms_cost_type
	0 		Normal
	5		Free Two Way
	7		Activation SMS
	10		Previous one was blocked this one is free
	50		Package finished
	100+	Send because of package subscription + pack_id
details
	extra_content => array(source_id,rate_type,currency_id)
	send_change_date => true,false
	min,max
	target_date
	change_percentage
	change_step
	target_price