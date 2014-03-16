<?php

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class PSC_Table extends WP_List_Table {

    function __construct(){
	global $status, $page;
	
	parent::__construct( array(
				   'singular'  => __( 'book', 'mylisttable' ),     //singular name of the listed records
				   'plural'    => __( 'books', 'mylisttable' ),   //plural name of the listed records
				   'ajax'      => false        //does this table support ajax?
				   
				   ) );
	
	add_action( 'admin_head', array( &$this, 'admin_header' ) );            
	
    }

    
    function display() {
	extract( $this->_args );
?>

<form method="post">
  <input type="hidden" name="page" value="psc_participants" />
  <?php $this->search_box('search', 'search_id'); ?>
</form>
<?php	
	$this->display_tablenav( 'top' );
?>	
<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>" cellspacing="0">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<?php $this->print_column_headers( false ); ?>
	</tr>
	</tfoot>

	<tbody id="the-list"<?php if ( $singular ) echo " data-wp-lists='list:$singular'"; ?>>
		<?php $this->display_rows_or_placeholder(); ?>
	</tbody>
</table>
<?php
	$this->display_tablenav( 'bottom' );
    }

    function print_column_headers( $with_id = true ) {
	list( $columns, $hidden, $sortable ) = $this->get_column_info();
	
	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$current_url = remove_query_arg( 'paged', $current_url );
	$current_url = remove_query_arg( 'action', $current_url );
	$current_url = remove_query_arg( 'item', $current_url );
	
	if ( isset( $_GET['orderby'] ) )
	  $current_orderby = $_GET['orderby'];
	else
	  $current_orderby = '';
	
	if ( isset( $_GET['order'] ) && 'desc' == $_GET['order'] )
	  $current_order = 'desc';
	else
	  $current_order = 'asc';
	
	if ( ! empty( $columns['cb'] ) ) {
	    static $cb_counter = 1;
	    $columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
	      . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
	    $cb_counter++;
	}
	
	foreach ( $columns as $column_key => $column_display_name ) {
	    $class = array( 'manage-column', "column-$column_key" );
	    
	    $style = '';
	    if ( in_array( $column_key, $hidden ) )
	      $style = 'display:none;';
	    
	    $style = ' style="' . $style . '"';
	    
	    if ( 'cb' == $column_key )
	      $class[] = 'check-column';
	    elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) )
	      $class[] = 'num';
	    
	    if ( isset( $sortable[$column_key] ) ) {
		list( $orderby, $desc_first ) = $sortable[$column_key];
		
		if ( $current_orderby == $orderby ) {
		    $order = 'asc' == $current_order ? 'desc' : 'asc';
		    $class[] = 'sorted';
		    $class[] = $current_order;
		} else {
		    $order = $desc_first ? 'desc' : 'asc';
		    $class[] = 'sortable';
		    $class[] = $desc_first ? 'asc' : 'desc';
		}
		
		$column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
	    }
	    
	    $id = $with_id ? "id='$column_key'" : '';
	    
	    if ( !empty( $class ) )
	      $class = "class='" . join( ' ', $class ) . "'";
	    
	    echo "<th scope='col' $id $class $style>$column_display_name</th>";
	}
    }
    
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
	$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
	$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result = strcmp( $a[$orderby], $b[$orderby] );
	return ( $order === 'asc' ) ? $result : -$result;
    }

    function search_box( $text, $input_id ) {
	if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
	  return;
	
	$input_id = $input_id . '-search-input';
	
	if ( ! empty( $_REQUEST['type'] ) )
	  echo '<input type="hidden" name="type" value="' . esc_attr( $_REQUEST['type'] ) . '" />';
	if ( ! empty( $_REQUEST['orderby'] ) )
	  echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
	if ( ! empty( $_REQUEST['order'] ) )
	  echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
	if ( ! empty( $_REQUEST['post_mime_type'] ) )
	  echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
	if ( ! empty( $_REQUEST['detached'] ) )
	  echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
	?>
<p class="search-box">
	<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
	<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
	<?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
</p>
	<?php
    }
    
    

}

class PSC_Participants_Table extends PSC_Table {
    var $table_data = array();

    function get_data() {
	global $wpdb;
	
	$sql = "SELECT p.*,count(distinct(v.id)) AS votes FROM " . PSC_TABLE_PARTICIPANTS . " AS p LEFT JOIN " . PSC_TABLE_VOTES . " AS v ON p.id=v.participant_id GROUP BY p.id ORDER BY p.subscribe_date DESC";
	
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
			 'class_name'           => __('Class Name', PSC_PLUGIN),
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
	    $cats = psc_get_category_by_id('school');
	    if (isset($cats[$item[$column_name]])) {
		$res = $cats[$item[$column_name]];
	    } else {
		$res = __('Undefined School', PSC_PLUGIN);
	    }
	    return $res;
	    
	 case 'class_name':
	    $cats = psc_get_category_by_id('class_name');
	    if (isset($cats[$item['class_name']])) {
		$res = $cats[$item['class_name']];
	    } else {
		$res = __('Undefined Class Name', PSC_PLUGIN);
	    }
	    return $res;
	    break;
	    
	 case 'project_category':
	    $cats = psc_get_category_by_id('project');
	    return $cats[$item[$column_name]];
	    break;
	    
	 case 'name':
	 case 'email':
	 case 'project_name':
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
				  'class_name' => array('class_name',false),
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
			 'view'     => sprintf('<a data-id="%s" class="view" href="#">%s</a>', $item['id'], __('Preview', PSC_PLUGIN)),
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
			 'delete'    => __('Delete', PSC_PLUGIN),
			 'approve'   => __('Approve', PSC_PLUGIN)
		         );
	return $actions;
    }

}


class PSC_Votes_Table extends PSC_Table {
    var $table_data = array();

    function get_data() {
	global $wpdb;
	
	$sql = "SELECT v.*,p.first_name,p.last_name,p.project_name FROM " . PSC_TABLE_VOTES . " AS v INNER JOIN " . PSC_TABLE_PARTICIPANTS . " AS p ON p.id=v.participant_id ORDER BY v.vote_date DESC";
	
	$rows = $wpdb->get_results($sql, ARRAY_A);
	
	foreach($rows as &$row) {
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

    function column_voter_name($item) {    
	$actions = array(
			 'delete'   => sprintf('<a class="delete" href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'delete',$item['id'], __('Delete', PSC_PLUGIN)),
			 );
	
	return sprintf('%1$s %2$s', $item['voter_name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {    
	$actions = array(
			 'delete'    => __('Delete', PSC_PLUGIN)
		         );
	return $actions;
    }

}



class PSC_Categories_Table extends PSC_Table {
    var $table_data = array();

    function get_data() {
	global $wpdb;
	
	$sql = "SELECT * FROM " . PSC_TABLE_CATEGORIES;
	if (isset($_GET['type']) && $_GET['type']) {
	    $sql .= " WHERE category_type = '" . esc_sql($_GET['type']) . "'";
	}
	$sql .= " ORDER BY category_type DESC, category_name DESC"; //, category_type ASC";

	$rows = $wpdb->get_results($sql, ARRAY_A);
	return $rows;
    }
    
    function get_columns(){
	$columns = array(
			 'cb'                   => '<input type="checkbox" />',
			 'category_name'        => __('Name', PSC_PLUGIN),
			 'category_type'        => __('Type', PSC_PLUGIN),
		         );
	return $columns;
    }
    
    function column_default( $item, $column_name ) {    
	switch( $column_name ) {
	 case 'category_name':
	    return $item[ $column_name ];
	    
	 case 'category_type':
	    global $psc_category_types;
	    return $psc_category_types[$item[ $column_name ]];
	    break;
	    
	 default:
	    return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	}
    }

    function get_sortable_columns() {
	$sortable_columns = array(
				  'category_name'  => array('category_name',false),
				  'category_type'  => array('category_type',false),
				  );
	return $sortable_columns;
    }

    function column_category_name($item) {    
	$actions = array(
			 'edit'     => sprintf('<a href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'edit',$item['id'], __('Edit', PSC_PLUGIN)),
			 'delete'   => sprintf('<a class="delete" href="?page=%s&action=%s&item=%s">%s</a>', $_REQUEST['page'],'delete',$item['id'], __('Delete', PSC_PLUGIN)),
			 );
	
	return sprintf('%1$s %2$s', $item['category_name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {    
	$actions = array(
			 'delete'    => __('Delete', PSC_PLUGIN),
		         );
	return $actions;
    }

}

