<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const DEL = 'del';
	
	const DEL_ALL = 'delAll';
	
	const ERROR = 'error';
	
	const PSW_ERROR = 'psw_error';
	
	const IMAGE_ERROR = 'image_errors';
	
	const STATUS = 'status';
	
	const RESULT = 'result';
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
	/*
	 * HOME PAGE
	 */
	public function handleHome($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;

		return $mvc;
	}
	
	public function handleForEntrepreneurs($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;

		return $mvc;
	}
	
	public function handleCompanies($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$users = UsersService::getUsersList ();
		isset ( $users ) ? $mvc->addObject ( UsersService::USERS, $users ) : null;

		return $mvc;
	}

	public function handleViewOrders($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		//check 
		$result = OrdersService::checkOrders();
		if($result!=NULL){
			foreach($result as $key => $order){
				$bids = OrdersService::buyOrder ($order[OrdersService::ID]);
				
				if(isset($bids)&&$bids!=NULL){
					$subject = $order[OrdersService::ORDER_NAME];
					$sender_id = 4;
					$user = SessionService::getAttribute ( SessionService::USERNAME );
					$text = "<b>Congratulation!</b><br\/> You have just won auction <a href=\'/view-bargain.html?id=" . $order[OrdersService::HASH] . "\'>" . $order[OrdersService::ORDER_NAME] . "<\/a>!";
					$getter_id = $bids[0][OrdersService::USER_ID];
					#prepare text for email 
					$plain = $mvc->getProperty ( 'newMessage' );
					MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
				}
			}
		}
		
		$where = OrdersService::BOUGHT . '=0';
		$onpage = isset ( $requestParams ['onpage'] )&& $requestParams ['onpage'] !=NULL ? $requestParams ['onpage'] : 5;
		if (isset ( $requestParams ['page'] )&& $requestParams ['page'] !=NULL ){ 
			$limit = ($requestParams ['page']-1)*$onpage . "," . $onpage;
		} else {
			$limit = "0,$onpage";
		}
		if (isset ( $requestParams [OrdersService::ORDER_NAME] )&& $requestParams [OrdersService::ORDER_NAME] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::ORDER_NAME . " LIKE '%" . $requestParams [OrdersService::ORDER_NAME] . "%'";
		}
		if (isset ( $requestParams [OrdersService::FROM_PRICE] )&& $requestParams [OrdersService::FROM_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::PRICE . ' >= ' . $requestParams [OrdersService::FROM_PRICE];
		}
		if (isset ( $requestParams [OrdersService::UNTIL_PRICE] )&& $requestParams [OrdersService::UNTIL_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::PRICE . ' <= ' . $requestParams [OrdersService::UNTIL_PRICE];
		}
		if (isset ( $requestParams [OrdersService::COUNTRY] ) && $requestParams [OrdersService::COUNTRY]!='all' ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::COUNTRY . '=' . $requestParams [OrdersService::COUNTRY];
		}
		if (isset ( $requestParams [OrdersService::REGION] ) && $requestParams [OrdersService::REGION]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::REGION . '=' . $requestParams [OrdersService::REGION];
		}
		if (isset ( $requestParams [OrdersService::CATEGORY_ID] ) && $requestParams [OrdersService::CATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::CATEGORY_ID . '=' . $requestParams [OrdersService::CATEGORY_ID];
		}
		if (isset ( $requestParams [OrdersService::SUBCATEGORY_ID] ) && $requestParams [OrdersService::SUBCATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::SUBCATEGORY_ID . '=' . $requestParams [OrdersService::SUBCATEGORY_ID];
		}
		$orders = OrdersService::getOrders ($where, $limit);
		if ($orders!=NULL){
			foreach($orders as $key => $value){
				if ($value[UploadsService::PATH]!=NULL){
					$part = explode('.',$value[UploadsService::PATH]);
					$orders[$key][UploadsService::PATH] = $part[0] . '-thumbnail.' . $part[1];
				} else {
					$orders[$key][UploadsService::PATH] = StOrageService::DEF_ORDER_AVATAR;
				}
			}
		}
		isset ( $orders ) ? $mvc->addObject ( OrdersService::ORDERS, $orders ) : null;
		
		$all_orders = OrdersService::countOrders ($where);
		isset ( $orders ) ? $mvc->addObject ( "count",  $all_orders[OrdersService::ORDERS] ) : null;
		
		$pages = $all_orders[OrdersService::ORDERS]/$onpage; 
		isset ( $orders ) ? $mvc->addObject ( "pages",  $pages ) : null;
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		if (isset ($requestParams[OrdersService::CATEGORY_ID])){
			$crntCategory = $requestParams[OrdersService::CATEGORY_ID]!='all' ? 
								$requestParams[OrdersService::CATEGORY_ID] : 
									$category[0][CategoryService::ID];  
			$subcategory = SubCategoryService::getSubcatByCat ($crntCategory);
			isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		}
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleViewOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		if(isset($requestParams[OrdersService::ID])){
			$where = OrdersService::HASH . "='" . $requestParams[OrdersService::ID] . "'";
			$order = OrdersService::getOrders ($where);
			if(isset ( $order )&&$order!=NULL) {
				$order[0]['time_left'] = self::countTimeLeft($order[0]['until_date']);
				$mvc->addObject ( OrdersService::ORDERS, $order[0] );
			}
		
		
			if (isset ($requestParams['addBookmark']) || isset ($requestParams['delBookmark'])){
				$role = SessionService::getAttribute ( SessionService::ROLE );
				if ($role!=NULL){
					if ($role==UsersService::ROLE_TR){
						if (isset ($requestParams['addBookmark'])){
							OrdersService::createBookmark($order[0][OrdersService::ID], $id);
							$mvc->addObject ( self::STATUS, "_i18n{You have successfully added a bookmark!}" );
						} else {
							OrdersService::removeBookmark($order[0][OrdersService::ID], $id);
							$mvc->addObject ( self::STATUS, "_i18n{You have successfully remove a bookmark!}" );
						}
					}
				} else {
					$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}'>_i18n{login}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html' title='_i18n{register}'>_i18n{register}</a>." );
				}
			}
			
			if (isset($requestParams['makeBid']) && $requestParams[OrdersService::BID]!=NULL){
				$role = SessionService::getAttribute ( SessionService::ROLE );
				if ($role!=NULL){
					if ($role==UsersService::ROLE_TR){
						if ($order[0][OrdersService::PRICE]>=$requestParams[OrdersService::BID]){
							$where = OrdersService::BID . '<=' . $requestParams[OrdersService::BID]; 
							$smaller = OrdersService::getBids($order[0][OrdersService::ID], $where);
							if ($smaller ==NULL && count($smaller)>=1){
								OrdersService::makeBid($requestParams[OrdersService::ID], $requestParams[OrdersService::BID]);
								$mvc->addObject ( self::STATUS, "_i18n{You bid successfully saved!}" );
							} else {
								$mvc->addObject ( self::ERROR, "_i18n{Your bid should be smaller then current bid} ".$smaller[0]['bid'] . " &euro;" );
							}
						} else {
							$mvc->addObject ( self::ERROR, "_i18n{Your bid should be smaller then order price} ".$order[0][OrdersService::PRICE] . " &euro;" );
						}
					} else {
						$mvc->addObject ( self::ERROR, "_i18n{Only tradesman can make a bid, you can} <a href='/registration.html?role=2' title='_i18n{register}'>_i18n{register}</a> like tradesman." );
					}
				} else {
					$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}'>_i18n{login}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html' title='_i18n{register}'>_i18n{register}</a>." );
				}
			}
			
			
			$images = OrdersService::getOrderImgs ($order[0][OrdersService::ID]);
			if ($images!=NULL){
				foreach($images as $key => $value){
					$part = explode('.',$value[UploadsService::PATH]);
					$images[$key][UploadsService::PATH2] = $part[0] . '-thumbnail.' . $part[1];
				}
			} else {
				$images[0][UploadsService::PATH] = StOrageService::DEF_ORDER_AVATAR;
			}
			isset ( $images ) ? $mvc->addObject ( UploadsService::PATH, $images ) : null;
			
			$bids = OrdersService::getBids($order[0][OrdersService::ID]);
			isset ( $bids ) ? $mvc->addObject ( OrdersService::BIDS, $bids ) : null;
			
			$bookmark = OrdersService::getOrderBookmarks($id, $order[0][OrdersService::ID]);
			isset ( $bookmark ) ? $mvc->addObject ( OrdersService::ORDER_BOOKMARKS, $bookmark ) : null;
		}
		return $mvc;
	}
	
	public function handleViewBargain($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		$where = BargainsService::HASH . "='" . $requestParams[BargainsService::ID] . "'";
		$bargain = BargainsService::getBargains($where);
		
		$bargain[0]['time_left'] = self::countTimeLeft($bargain[0]['until_date']);
		
		isset ( $bargain ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargain[0] ) : null;
		if (isset($requestParams['buy']) && $bargain[0][BargainsService::NUMBER]>0){
			$role = SessionService::getAttribute ( SessionService::ROLE );
			if ($role!=NULL && $role==UsersService::ROLE_UR){
				BargainsService::buyBargain($bargain[0][BargainsService::ID], $id);
				
				$subject = $bargain[0][BargainsService::BARGAIN_NAME];
				$sender_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$user = SessionService::getAttribute ( SessionService::USERNAME );
				$text = $user . " bought one item <a href=\'/view-bargain.html?id=" . $bargain[0][BargainsService::HASH] . "\'>" . $bargain[0][BargainsService::BARGAIN_NAME] . "<\/a>";
				$getter_id = $bargain[0][BargainsService::USER_ID];
				#prepare text for email 
				$plain = $mvc->getProperty ( 'newMessage' );
				MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
			}
		}
		
		
		$images = BargainsService::getOrderImgs ($bargain[0][BargainsService::ID]);
		foreach($images as $key => $value){
			$part = explode('.',$value[UploadsService::PATH]);
			$images[$key][UploadsService::PATH2] = $part[0] . '-thumbnail.' . $part[1];
		}
		isset ( $images ) ? $mvc->addObject ( UploadsService::PATH, $images ) : null;
		$where = BargainsService::BARGAINS . '.' . BargainsService::ID . '='. $bargain[0][BargainsService::ID] . ' AND ' . 
				BargainsService::BOUGHT_BARGAIN . '.' . BargainsService::USER_ID . '=' . $id;
		$boughtBargains = BargainsService::getBoughtBargain($where);
		$mvc->addObject ( BargainsService::BOUGHT_BARGAIN, $boughtBargains);
		
		return $mvc;
	}
	
	private function countTimeLeft($date_time_string){
		$currenttime = time();
	    $date_elements = explode('-',$date_time_string);
	    $newtime= mktime(null, null,null, $date_elements[1],$date_elements[2], $date_elements[0]);
	    
	    $days=floor(($newtime-time())/86400);
	    $hours=floor(($newtime-time())/3600-($days*24));
	    $mins=floor(($newtime-time())/60-($days*1440)-($hours*60));
	    $secs=floor(($newtime-time())-($days*86400)-($hours*3600)-($mins*60));
		return "$days _i18n{days} $hours _i18n{hours} $mins _i18n{mins} $secs _i18n{secs}";
	}
/*	public static function createTeaser ($list){
		if (count($list)>0){
			foreach($list as $key => $value){
				$chr = strpos($value[SchoolService::DESCRIPTION], '</p>');
				$value[SchoolService::DESCRIPTION] = $chr != NULL ? 
					substr($value[SchoolService::DESCRIPTION],0,$chr) : 
						$value[SchoolService::DESCRIPTION];
				$value[SchoolService::DESCRIPTION] = substr($value[SchoolService::DESCRIPTION], 0, 255);
				$list[$key][SchoolService::DESCRIPTION] = strrev(strstr(strrev($value[SchoolService::DESCRIPTION]), ' '));
			}
		}
		return $list;
	}
	public static function createTeaserWord ($word){
		$word = substr($word, 0, 255);
		$word = strrev(strstr(strrev($word), ' '));
		return $word;
	}*/
	
	
	public function handleNewMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );
		isset($requestParams['back']) ? $this->forwardActionRequest ( $location ) : NULL;
		if (isset($requestParams [MailService::RE]) || isset($requestParams [MailService::FWD])){
			$id = isset($requestParams [MailService::RE]) ? $requestParams [MailService::RE] : $requestParams [MailService::FWD];
			$mail = MailService::getMail($id);
			$replay = isset($requestParams [MailService::RE]) ? 1 : 0;
			$subject = $replay ? '_i18n{Re}: ' : '_i18n{Fwd}: ' ; 
			$subject .= $mail[MailService::SUBJECT];
			$date = $mail [MailService::CRDATE];
			$sender = $mail [MailService::SENDER];
			$getter = $mail [MailService::GETTER];
			$getter_id = $mail[MailService::GETTER_ID];
			$text = "<div><br/><br/><br/></div><div><font class='Apple-style-span'; color='#999999'>";
			$text .= '_i18n{On} ' . $date . ', ' . $sender . ' _i18n{wrote}:';
			$text .= "<div><br/></div>";
			$text .= $mail [MailService::TEXT];
			$text .= "</font></div>";
			$result = array(MailService::SUBJECT=>$subject,MailService::GETTER=>$sender,MailService::TEXT=>$text,MailService::RE=>$replay);
			$mvc->addObject ( self::RESULT, $result );
		}
		if (isset($requestParams [MailService::SEND])){
			$subject = htmlspecialchars($requestParams [MailService::SUBJECT], ENT_QUOTES);
			$text = htmlspecialchars($requestParams [MailService::TEXT], ENT_QUOTES);
			$sender_id = SessionService::getAttribute ( SessionService::USERS_ID );
			$getter = htmlspecialchars($requestParams [MailService::GETTER], ENT_QUOTES);
			$getter_id = UsersService::getUserIdByName($getter);
			if (isset($getter_id) && $getter_id!=NULL){
				#prepare text for email 
				$plain = $mvc->getProperty ( 'newMessage' );
				MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
				$this->forwardActionRequest ( $location );
			} else {
				$error [] .= '_i18n{No such user.}';
				$mvc->addObject ( self::ERROR, $error );
				$result = array(MailService::SUBJECT=>$subject,MailService::GETTER=>$getter,MailService::TEXT=>$text);
				$mvc->addObject ( self::RESULT, $result );
			}
		}
		return $mvc;
	}
	
	public function handleViewMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		MailService::readMail ( $requestParams[MailService::ID] );
		
		$mail = MailService::getMail( $requestParams[MailService::ID] );
		isset ( $mail ) ? $mvc->addObject (self::RESULT, $mail ) : null;
		
		return $mvc;
	}
	
	public function handleMyMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		isset ( $requestParams [self::DEL] ) ? MailService::deleteMail ($requestParams [self::DEL]) : null;
		
		$limit = '0,5';
		
		#get inbox
		$inbox = MailService::getInbox( $id , $limit );
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		
		#get outbox
		$outbox = MailService::getOutbox( $id , $limit );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		
		#get trash
		$trash = MailService::getTrash( $id , $limit );
		isset ( $trash ) ? $mvc->addObject ( MailService::TRASH, $trash ) : null;
		
		return $mvc;
	}
	
	public function handleInbox($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get inbox
		$inbox = MailService::getInbox( $id );
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		
		return $mvc;
	}
	
	public function handleOutbox($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get outbox
		$outbox = MailService::getOutbox( $id );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		
		return $mvc;
	}
	
	public function handleTrash($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get trash
		$trash = MailService::getTrash( $id );
		isset ( $trash ) ? $mvc->addObject ( MailService::TRASH, $trash ) : null;
		
		return $mvc;
	}
	
	public function createDate($date){
		$date = explode('/', $date);
		$result = $date[2] . '-' . $date[1] . '-' . $date[0];
		return $result;
	}
	
}
?>