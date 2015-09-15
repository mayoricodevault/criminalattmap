<?php

class APP_Dynamic_Checkout{

	protected $steps;
	protected $current_step = false;
	protected $step_finished = false;

	protected $hash, $order_id, $checkout_type;

	public function __construct( $checkout_type, $hash = '' ){

		$this->hash = substr( $hash, 0, 20 );
		$this->checkout_type = substr( $checkout_type, 0, 25 );
		if( empty( $this->hash ) || !$this->verify_hash() ){
			$this->hash = substr( sha1( time() . mt_rand( 0, 1000 ) ), 0, 20 );
			$this->add_data( 'checkout_type', $this->checkout_type );
		}	

		$this->steps = new APP_Relational_Checkout_List;
	}

	public function add_step( $id, $process, $display ){
		$this->steps->add( $id, array( $process, $display ) );
	}

	public function add_step_before( $ref_id, $id, $process, $display ){
		$this->steps->add_before( $ref_id, $id, array( $process, $display ) );
	}

	public function add_step_after( $ref_id, $id, $process, $display ){
		$this->steps->add_after( $ref_id, $id, array( $process, $display ) );
	}

	public function unregister_step( $id ){
		$this->steps->remove( $id );
	}

	public function display_step( $id ){
		return $this->call_step( $id, 'display' );
	}

	public function process_step( $id ){
		return $this->call_step( $id, 'process' );
	}

	protected function call_step( $id, $type = 'display' ){

		$id = apply_filters( 'appthemes_checkout_call_step', $id );
		$this->current_step = $id;

		if( $this->steps->is_empty() )
			return false;

		if( ! $this->steps->contains( $id ) )
			return false;

		$callbacks = $this->steps->get( $this->current_step );
		$callback = $callbacks['payload'][ ( $type == 'display' ) ? 1 : 0 ];

		$order_id = $this->get_data( 'order_id' );
		if( ! $order_id )
			$order = new APP_Draft_Order;
		else
			$order = appthemes_get_order( $order_id );

		if( is_callable( $callback ) )
			call_user_func( $callback, $order, $this );
		elseif( is_string( $callback ) ){
			locate_template( $callback, true );
		}else{
			return false;
		}

		if( $order instanceof APP_Draft_Order && $order->is_modified() ){
			$new_order = APP_Order_Factory::duplicate( $order );
			$this->add_data( 'order_id', $new_order->get_id() );
		}

		if( $this->step_finished ){
			wp_redirect( appthemes_get_step_url( $this->get_next_step( $id ) ) );
			exit;
		}

		$this->current_step = false;
		return true;
	}

	public function get_current_step(){
		return $this->current_step;
	}

	public function get_next_step( $id = '' ){

		if( $this->steps->is_empty() )
			return false;

		if( empty( $id ) )
			return $this->steps->get_first_key();
		else
			return $this->steps->get_key_after( $id );		

	}

	public function get_previous_step( $id = '' ){

		if ( $this->steps->is_empty() )
			return false;

		if ( empty( $id ) )
			return $this->steps->get_first_key();
		else
			return $this->steps->get_key_before( $id );

	}

	public function finish_step(){
		$this->step_finished = true;
	}

	public function add_data( $key, $value ){

		$data = $this->get_data();
		if( ! $data )
			$data = array();

		$data[ $key ] = $value;
		set_transient( $this->get_transient_key(), $data, 60 * 60 * 24 );

	}

	public function get_data( $key = '' ){

		$data = get_transient( $this->get_transient_key() );
		if( empty( $key ) )
			return $data;
		else if( isset( $data[ $key ] ) )
			return $data[ $key ];
		else
			return false;
	}

	protected function verify_hash(){
		$checkout_type = $this->get_data( 'checkout_type' );
		if( $this->checkout_type != $checkout_type ){
			return false;
		}else{
			return true;
		}
	}

	protected function get_transient_key(){
		return $this->checkout_type . '_' . $this->hash;
	}

	public function get_hash(){
		return $this->hash;
	}

	public function get_checkout_type(){
		return $this->checkout_type;
	}

	public function get_steps(){
		return $this->steps->get_all();
	}

	public function get_steps_count(){
		return count( $this->steps->get_all() );
	}

}

