<?php use DB\SQL\ggn_Mapper;
global $f3;
class DashboardController extends MainController {
	/******************************************************************************************/
	/*                                                                                        */
	/*                         home function to show data in `/` route                        */ 
	/*                                                                                        */
	/******************************************************************************************/
	function home($f3){	
		$data = array(
			'msg' => __("hello world godgiven")
		);
		$data = json_encode($data);
		die($data);
	}
	/******************************************************************************************/
	/*                                                                                        */
	/*      notifications function to get user notifications in `/notifications` route        */
	/*                                                                                        */
	/******************************************************************************************/
	function notifications($f3) {
	    $this->f3->set('show_title','اعلان ها و پیام ها');
	    $ticketing = new ggn_Mapper( $this->dbs , 'tickets_ggn');
	    $data = $f3->get('GET');
	    $data = $data['state'];
	    $data2 = $f3->get('POST');
	    $notify = $data2['notify'];
	    $notify_id = $data2['notify_id'];
	    $state = 0;
	    if(is_numeric ($data)){
	        $state = $data;
	    }
	    if($notify == 1 && $notify_id){
	        $ticketing-> ggn_change_db($this->dbm);
	        $notify = $ticketing->load (array ( 'tick_id = ?',$notify_id ) );
	        $notify->tick_state = 1;
	        $notify->save();
	        $message = 'جهت مشاهده مجدد این پیام به آرشیو پیام ها مراجعه کنید.';
	    }
	    else if($notify == 0 && $notify_id){
	        $ticketing-> ggn_change_db($this->dbm);
	        $notify = $ticketing->load (array ( 'tick_id = ?',$notify_id ) );
	        $notify->tick_state = 0;
	        $notify->save();
	        $message = ' این پیام به پیام ها بازگشت';
	    }
	    $ticketing->reset();
	    $ticketing-> ggn_change_db($this->dbs);
	    $notifications = $ticketing->find ( array( 'tick_type = 2 AND tick_state = ? AND tick_policy = ? ORDER BY tick_time DESC', $state, $f3->get('SESSION.auth')['id'] ) );
	    $f3->set('notifications' , $notifications);
	    if($message){
	        $f3->set('success', $message);
	    }
	    if($state == 1) {
	        echo $this->ggn_echo('visited_notifications.php');
	    } 
	    else {
	        echo $this->ggn_echo('notifications.php');
	    }
	}
	/******************************************************************************************/
	/*                                                                                        */
	/*                         error404 function for not valid routes                         */ 
	/*                                                                                        */
	/******************************************************************************************/
	function error404(){
		echo $this->ggn_echo('404.php');
	}
}