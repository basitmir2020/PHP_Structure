<?php

	class validation{
		public function validateString($param){
			if(preg_match('%^[A-Za-z0-9 ]{3,200}$%',$param))
				return true;
			return false;
		}
		public function validateMobile($param){
			if(preg_match('%^[0-9 )(+-]{10,16}$%',$param))
				return true;
			return false;
		}
		public function validateDate($param){
			$param = date('d-m-Y',strtotime($param));
			if(preg_match('%^[0-9]{1,2}+[-/]+[0-9]{1,2}+[-/]+[0-9]{2,4}$%',$param))
				return true;
			return false;
		}
		public function validateEmail($param){
			if(filter_var($param,FILTER_VALIDATE_EMAIL))
				return true;
			return false;
		}
		public function validateURL($param){
			if(filter_var($param,FILTER_VALIDATE_URL))
				return true;
			return false;
		}
		public function validateNumber($param){
			if(preg_match('%^[0-9]{1,20}$%',$param))
				return true;
			return false;
		}
		public function longString($param){
			if(preg_match('%^[a-zA-Z0-9 \r?\n\"\'\?\!\,\.\&\%]{3,3000}$%',$param))
				return true;
			return false;
		}
		public function validateUsername($param){
			if(preg_match('%^[A-Za-z0-9_.]{4,50}$%',$param))
				return true;
			return false;
		}
		public function validatePassword($param){
			if(preg_match('%^[A-Za-z0-9\_\.\#\@\-\$\!\~]{6,50}$%',$param))
				return true;
			return false;
		}
	}
?>