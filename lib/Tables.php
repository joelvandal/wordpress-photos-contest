<?php

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class PSC_Table extends WP_List_Table {
        
    function prepare_items() {
	
	$this->table_data = $this->get_data();
	
	$columns = $this->get_columns();
	$hidden = array();
	$sortable = $this->get_sortable_columns();
	$this->_column_headers = array($columns, $hidden, $sortable);

	usort( $this->table_data, array( &$this, 'usort_reorder' ) );
	
	$per_page = 15;
	$current_page = $this->get_pagenum();
	$total_items = count($this->table_data);
	
	$this->found_data = array_slice($this->table_data,(($current_page-1)*$per_page),$per_page);
	
	$this->set_pagination_args( array( 'total_items' => $total_items, 'per_page'    => $per_page ) );
	$this->items = $this->found_data;
	
    }
    
    function column_cb($item) {    
	return sprintf('<input type="checkbox" name="item[]" value="%s" />', $item['id']);    
    }
    
    function usort_reorder( $a, $b ) {    
	$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
	$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result = strcmp( $a[$orderby], $b[$orderby] );
	return ( $order === 'asc' ) ? $result : -$result;
    }
    

}

class PSC_Participants_Table extends PSC_Table {
    var $table_data = array();

    function get_data() {
	global $wpdb;
	
	$sql = "SELECT p.*,count(distinct(v.id)) AS votes FROM " . PSC_TABLE_PARTICIPANTS . " AS p LEFT JOIN " . PSC_TABLE_VOTES . " AS v ON p.id=v.participant_id GROUP BY p.id";
	
	$rows = $wpdb->get_results($sql, ARRAY_A);
	
	foreach($rows as &$row) {
	    psc_image($row['email']);
	    $row['name'] = strtoupper($row['last_name']) . ', ' . $row['first_name'];
	}
	
	return $rows;
    }
    
    function get_columns(){
	$columns = array(
			 'cb'                   => '<input type="checkbox" />',
			 'image'                => __('Image', PSC_PLUGIN),
			 'name'                 => __('Name', PSC_PLUGIN),
			 'email'                => __('Email', PSC_PLUGIN),
			 'school'               => __('School', PSC_PLUGIN),
			 'project_name'         => __('Project Name', PSC_PLUGIN),
			 'project_category'     => __('Category', PSC_PLUGIN),
			 'project_description'  => __('Description', PSC_PLUGIN),
//			 'views'                => __('Views', PSC_PLUGIN),
			 'votes'                => __('Votes', PSC_PLUGIN),
			 'subscribe_date'       => __('Subscribe', PSC_PLUGIN),
			 'status'               => __('Status', PSC_PLUGIN)
		         );
	return $columns;
    }
    
    function column_default( $item, $column_name ) {    
	switch( $column_name ) {
	 case 'image':
	    
	    $img = '/uploads/' . md5($item['email']) . '-thumb.png';
	    if (!file_exists(PSC_ABSPATH . $img)) {
		$img = '/images/user.jpg';
	    }
		
	    return '<img src="' . PSC_PATH . $img . '" />';
	    break;

	 case 'status':
	    return $item['approved'] ? __('Accepted', PSC_PLUGIN) : __('Not Approved', PSC_PLUGIN);
	    break;

	 case 'views':
	 case 'votes':
	    return $item[ $column_name ] ? $item[ $column_name ] : __('N/A', PSC_PLUGIN);
	    break;

	 case 'subscribe_date':
	    return psc_format_datetime($item[ $column_name ]);
	    break;
	    
	 case 'school':
	 case 'name':
	 case 'email':
	 case 'project_name':
	 case 'project_category':
	 case 'project_description':
	    return $item[ $column_name ];
	    
	 default:
	    return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	}
    }

    function get_sortable_columns() {
	$sortable_columns = array(
				  'name'  => array('name',false),
				  'email' => array('email',false),
				  'school' => array('school',false),
				  'project_name'   => array('project_name',false),
				  'project_category'   => array('project_category',false),
				  'project_description'   => array('project_description',false),
				  'subscribe_date'   => array('subscribe_date',false),
				  'votes' => array('votes',false),
				  'status'   => array('status',false)
				  );
	return $sortable_columns;
    }

    function column_name($item) {    
	$actions = array(
			 'edit'     => sprintf('<a href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'edit',$item['id'], __('Edit', PSC_PLUGIN)),
			 'accept'   => sprintf('<a href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'approve',$item['id'], __('Approve', PSC_PLUGIN)),
			 'reject'   => sprintf('<a href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'unapprove',$item['id'], __('Reject', PSC_PLUGIN)),
			 'delete'   => sprintf('<a class="delete" href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'delete',$item['id'], __('Delete', PSC_PLUGIN)),
			 );
	
	if ($item['approved']) {
	    unset($actions['accept']);
	} else {
	    unset($actions['reject']);
	}
	
	return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {    
	$actions = array(
			 'delete'    => 'Delete',
			 'approve'   => 'Approve'
		         );
	return $actions;
    }

}


class PSC_Votes_Table extends PSC_Table {
    var $table_data = array();

    function get_data() {
	global $wpdb;
	
	$sql = "SELECT v.*,p.first_name,p.last_name,p.project_name FROM " . PSC_TABLE_VOTES . " AS v INNER JOIN " . PSC_TABLE_PARTICIPANTS . " AS p ON p.id=v.participant_id";
	
	$rows = $wpdb->get_results($sql, ARRAY_A);
	
	foreach($rows as &$row) {
	    psc_image($row['email']);
	    $row['name'] = strtoupper($row['last_name']) . ', ' . $row['first_name'];
	}
	
	return $rows;
    }
    
    function get_columns(){
	$columns = array(
			 'cb'                   => '<input type="checkbox" />',
			 'voter_name'           => __('Voter Name', PSC_PLUGIN),
			 'voter_email'          => __('Voter Email', PSC_PLUGIN),
			 'name'                 => __('Participant Name', PSC_PLUGIN),
			 'project_name'         => __('Project Name', PSC_PLUGIN),
			 'voter_ip'             => __('IP Address', PSC_PLUGIN),
			 'vote_date'            => __('Vote Date', PSC_PLUGIN),
		         );
	return $columns;
    }
    
    function column_default( $item, $column_name ) {    
	switch( $column_name ) {
	 case 'voter_name':
	 case 'voter_email':
	 case 'name':
	 case 'project_name':
	 case 'voter_ip':
	    return $item[ $column_name ];
	    break;
	    
	 case 'vote_date':
	    return psc_format_datetime($item[ $column_name ]);
	    break;
	    
	 default:
	    return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	    break;
	}
    }

    function get_sortable_columns() {
	$sortable_columns = array(
				  'name'  => array('name',false),
				  'project_name' => array('project_name',false),
				  'vote_date' => array('vote_date',false)
				  );
	return $sortable_columns;
    }

    function column_name($item) {    
	$actions = array(
			 'delete'   => sprintf('<a href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'delete',$item['id'], __('Delete', PSC_PLUGIN)),
			 );
	
	return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {    
	$actions = array(
			 'delete'    => 'Delete'
		         );
	return $actions;
    }

}
