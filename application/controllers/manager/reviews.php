<?php

/**
 * OpenReviewScript
 *
 * An Open Source Review Site Script
 *
 * @package		OpenReviewScript
 * @subpackage          manager
 * @author		OpenReviewScript.org
 * @copyright           Copyright (c) 2011-2012, OpenReviewScript.org
 * @license		This file is part of OpenReviewScript - free software licensed under the GNU General Public License version 2 - http://OpenReviewScript.org/license
 * @link		http://OpenReviewScript.org
 */
// ------------------------------------------------------------------------

/**    This file is part of OpenReviewScript.
 *
 *    OpenReviewScript is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    OpenReviewScript is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OpenReviewScript.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Reviews listing controller class
 *
 * Displays a list of all reviews, and a list of pending reviews, paginated
 *
 * @package		OpenReviewScript
 * @subpackage          manager
 * @category            controller
 * @author		OpenReviewScript.org
 * @link		http://OpenReviewScript.org
 */
class Reviews extends CI_Controller {

    /*
     * Articles controller class constructor
     */

    function Reviews() {
	parent::__construct();
	$this->load->model('Review_model');
	// load all settings into an array
	$this->setting = $this->Setting_model->getEverySetting();
    }

    /*
     * index function (default)
     *
     * display list of reviews paginated
     */

    function index() {
	debug('manager/reviews page | index function');
	// check user is logged in with manager level permissions
	$this->secure->allowManagers($this->session);
	// load a page of reviews into an array for displaying in the view
	$data['allreviews'] = $this->Review_model->getAllReviews($this->setting['perpage_manager_reviews'], $this->uri->segment(4));
	if ($data['allreviews']) {
	    debug('loaded reviews');
	    // set up config data for pagination
	    $config['base_url'] = base_url() . 'manager/reviews/index';
	    $config['next_link'] = lang('results_next');
	    $config['prev_link'] = lang('results_previous');
	    $total = $this->Review_model->countReviews();
	    $config['total_rows'] = $total;
	    $config['per_page'] = $this->setting['perpage_manager_reviews'];
	    $config['uri_segment'] = 4;
	    $this->pagination->initialize($config);
	    $data['pagination'] = $this->pagination->create_links();
	    if (trim($data['pagination'] === '')) {
		$data['pagination'] = '&nbsp;<strong>1</strong>';
	    }
	    // show the reviews page
	    debug('loading "manager/reviews" view');
	    $sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/reviews', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
	    $this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	} else {
	    // no data... show the 'no reviews' page
	    debug('no reviews found - loading "manager/no_reviews" view');
	    $sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/no_reviews', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
	    $this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	}
    }

    function pending() {
	debug('manager/reviews page | pending function');
	// check user is logged in with manager level permissions
	$this->secure->allowManagers($this->session);
	// load a page of pending reviews into an array for displaying in the view
	$data['pendingreviews'] = $this->Review_model->getReviewsPending($this->setting['perpage_manager_reviews_pending'], $this->uri->segment(4));
	if ($data['pendingreviews']) {
	debug('loaded pending reviews');
	    // set up config data for pagination
	    $config['base_url'] = base_url() . 'manager/reviews/pending';
	    $config['next_link'] = lang('results_next');
	    $config['prev_link'] = lang('results_previous');
	    $total = $this->Review_model->countReviewsPending();
	    $config['total_rows'] = $total;
	    $config['per_page'] = $this->setting['perpage_manager_reviews_pending'];
	    $config['uri_segment'] = 4;
	    $this->pagination->initialize($config);
	    $data['pagination'] = $this->pagination->create_links();
	    if (trim($data['pagination'] === '')) {
		$data['pagination'] = '&nbsp;<strong>1</strong>';
	    }
	    // show the pending reviews page
	    debug('loading "manager/reviews/pending" view');
	    $sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/reviews_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
	    $this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	} else {
	    // no data... show the 'no pending reviews' page
	    debug('no pending review found - loading "manager/reviews/no_reviews_pending" view');
	    $sections = array('content' => 'manager/' . $this->setting['current_manager_theme'] . '/template/reviews/no_reviews_pending', 'sidebar' => 'manager/' . $this->setting['current_manager_theme'] . '/template/sidebar');
	    $this->template->load('manager/' . $this->setting['current_manager_theme'] . '/template/manager_template', $sections, $data);
	}
    }

}

/* End of file reviews.php */
/* Location: ./application/controllers/manager/reviews.php */