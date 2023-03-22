<?php
	class Journeysmodel extends CI_Model {

		function journeyList() {
			$this->db->where( 'status', 1 );
			$query 		= $this->db->get( 'journeys' );
			$numRows 	= $query->num_rows();
			if( $numRows > 0 ) {
				return $query->result();
			} else { return false; }
		}

		function journeyInfo( $journeyID ) {
			$this->db->where( 'journey_id', $journeyID );
			$query 		= $this->db->get( 'journeys' );
			$numRows 	= $query->num_rows();
			if( $numRows > 0 ) {
				return $query->row();
			} else { return false; }
		}
		//Insert Journey
		function addjourney( $data ) {
			$table  = $this->db->dbprefix('journeys');
			$this->db->insert( $table, $data );
			return true; 
		}
	}
?>		