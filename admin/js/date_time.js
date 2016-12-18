
	function all_(){
		var date = document.getElementById('date');
		var time = document.getElementById('_time');
		var title = document.getElementById('title');
		var description = document.getElementById('description');
		var venue = document.getElementById('venue');

		if(date.value == '' && time.value =='' && title.value == '' && description.value == '' && venue.value == ''){
			document.getElementById('err_msg').innerHTML = "Complete the form";
			return false;
		}else{
			document.getElementById('err_msg').innerHTML = "";
		}
	}

	function date_time(){
		this.date_();
		this.time_();
	}

	function date_(){
		_date = document.getElementById('date');
		this.checkDate(_date);
	}
	function time_(){
		_time = document.getElementById('_time');
		this.checkTime(_time);
	}

	function title_(){
		title = document.getElementById('title');
		if(title.value == ''){
			document.getElementById('err_msg').innerHTML = 'Empty Field not allowed';
		}else{
			document.getElementById('err_msg').innerHTML = "";
		}
	}

	function description_(){
		description = document.getElementById('description');
		if(description.value == ''){
			document.getElementById('err_msg').innerHTML = 'Empty Field not allowed';
		}else{
			document.getElementById('err_msg').innerHTML = "";
		}
	}

	function venue_(){
		venue = document.getElementById('venue');
		if(venue.value == ''){
			document.getElementById('err_msg').innerHTML = 'Empty Field not allowed';
		}else{
			document.getElementById('err_msg').innerHTML = "";
		}
	}

	// Original JavaScript code by Chirp Internet: www.chirp.com.au
	function checkDate(field_date){
		try{
			var allowBlank = true;
			var minYear = 2015 
			//(new Date()).getFullYear();
			var maxYear = 2016;
			var errorMsg = "";

			//regular expression to match requeired date format
			regex_d = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;

			if(field_date.value != ''){
				if(regs = field_date.value.match(regex_d)){
					if(regs[1] < 1 || regs[1] > 31){
						errorMsg = "Invalid value for day: " + regs[1];
					}else if(regs[2] < 1 || regs[2] > 12){
						errorMsg = "Invalid value for month: " + regs[2];
					}else if(regs[3] < minYear || regs[3] > maxYear){
						errorMsg = "Invalid value for year: " + regs[3];
					}
				}else{
					errorMsg = "Invalid date format: " + field_date.value;
				}
			}else{
				errorMsg = "Empty date not allowed!";
			}
			if(errorMsg != ""){
				document.getElementById('err_msg').innerHTML = errorMsg;
				//alert(errorMsg);
				field_date.focus();
				return false;
			}else{
					document.getElementById('err_msg').innerHTML = "";
			}
			return true;
		}catch(err){
			alert(err.message)
		}
	}

	// Original JavaScript code by Chirp Internet: www.chirp.com.au
	function checkTime(field_time){
		try{
			var errorMsg = "";

			//regular expression to match required time format
			regex_t = /^(\d{1,2}):(\d{2})(:00)?([ap]m)?$/;

			if(field_time.value != ''){
				if(regs_t = field_time.value.match(regex_t)){
					//12-hour time format with am/pm
					if(regs_t[1] < 1 || regs_t[1] > 12){
						errorMsg = "Invalid value for 12-hour: " + regs[1];
					}else if(regs_t[1] > 23){
						errorMsg = "Invalid value for hours: " + regs_t[1];
					}else if(!errorMsg && regs_t[2] > 59){
						errorMsg = "Invalid time format: " + field_time.value;
					}
				}else{
					errorMsg = "Invalid time format. Time must be (eg. 19:38 or 7:38pm)"
				}
				if(errorMsg != ''){
					document.getElementById('err_msg').innerHTML = errorMsg;
					//alert(errorMsg);
					field_time.focus();
					return false;
				}else{
					document.getElementById('err_msg').innerHTML = "";
				}
				return true;
			}else{
				document.getElementById('err_msg').innerHTML = "Empty time not allowed";
			}
		}catch(err){
			alert(err.message);
		}
	}