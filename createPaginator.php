<?php

function setPaginator($pageNum, $per_page) {
	$pagination = '';
	$pagination .= '<nav aria-label="Page navigation">';
	$pagination .= '<ul class="pagination">';
	
	for($page=1;$page<=$per_page;$page++) {
		if($page == $pageNum) {
			$pagination .= '<li class="active page-item"><a href="#" class="page-link" data-page="' . $page . '">' . $page . '</a></li>';
		} else {
			$pagination .= '<li class="page-item"><a href="#" class="page-link" data-page="' . $page . '">' . $page . '</a></li>';
		}
	}
	$pagination .= '</ul>';
	$pagination .= '</nav>';
	
	return $pagination;
}

?>